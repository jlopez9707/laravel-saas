<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use App\Models\Product;

class EditOrder extends EditRecord implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = OrderResource::class;

    public $quantities = [];
    public $selectedProductIds = [];
    protected array $cachedProducts = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $products = $this->record->products;
        
        foreach ($products as $product) {
            $this->quantities[$product->id] = $product->pivot->quantity;
            $this->selectedProductIds[$product->id] = true;
        }
    }
    
    public function table(Table $table): Table
    {
        $recordId = $this->record->id;
        return $table
            ->query(Product::query()->inStock()->orWhereHas('orders', function ($query) use ($recordId) {
                $query->where('order_id', $recordId);
            }))
            ->columns([
                ViewColumn::make('selected_product')
                    ->label('')
                    ->width('60px')
                    ->alignCenter()
                    ->view('filament.tables.columns.select-checkbox'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label(__('Stock available'))
                    ->sortable(),
                ViewColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->view('filament.tables.columns.quantity-input')
                    ->width('150px')
            ]);
    }

    public function updated($name): void
    {
        if (str_contains($name, 'selectedProductIds') || $name === 'selectedProductIds') {
            foreach ($this->selectedProductIds as $id => $isSelected) {
                if ($isSelected && !isset($this->quantities[$id])) {
                    $this->quantities[$id] = 1;
                }
            }
            $this->updateTotal();
        }

        if (str_contains($name, 'quantities')) {
            $this->updateTotal();
        }
    }

    public function updateTotal(): void
    {
        $selectedIds = array_keys(array_filter($this->selectedProductIds));
        
        if (empty($selectedIds)) {
            $this->data['total'] = 0;
            return;
        }

        $products = Product::whereIn('id', $selectedIds)->get();
        $total = 0;

        foreach ($products as $product) {
            $qty = $this->quantities[$product->id] ?? 1;
            $qty = max(1, (int)$qty);
            $total += $product->price * $qty;
        }

        $this->data['total'] = $total;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['products']);
        
        $selectedIds = array_keys(array_filter($this->selectedProductIds));
        $selectedProducts = Product::whereIn('id', $selectedIds)->get();
        
        $productsToSave = [];
        $total = 0;

        foreach ($selectedProducts as $product) {
            $quantity = $this->quantities[$product->id] ?? 1;
            $quantity = max(1, (int)$quantity);
            
            $productsToSave[$product->id] = [
                'quantity' => $quantity,
                'price' => $product->price,
            ];

            $total += $product->price * $quantity;
        }

        $data['total'] = $total;
        $this->cachedProducts = $productsToSave;

        return $data;
    }

    protected function afterSave(): void
    {
        if (isset($this->cachedProducts) && !empty($this->cachedProducts)) {
            $syncData = [];
            
            foreach ($this->cachedProducts as $productId => $data) {
                $syncData[$productId] = [
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                ];
            }

            $this->record->products()->sync($syncData);
        }
    }
}
