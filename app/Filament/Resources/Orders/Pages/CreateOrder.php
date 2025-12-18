<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use App\Models\Product;

class CreateOrder extends CreateRecord implements HasTable
{
    use InteractsWithTable;

    protected array $cachedProducts = [];

    protected static string $resource = OrderResource::class;

    public $quantities = [];
    public $selectedProductIds = [];

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->inStock())
            ->columns([
                ViewColumn::make('selected_product')
                    ->label('')
                    ->width('60px')
                    ->alignCenter()
                    ->view('filament.tables.columns.select-checkbox'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('USD')
                    ->sortable()
                    ->width('1%'),
                TextColumn::make('stock')
                    ->label(__('Stock available'))
                    ->sortable()
                    ->alignEnd()
                    ->width('1%'),
                ViewColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->alignEnd()
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

    protected function beforeCreate(): void
    {
        $selectedIds = array_keys(array_filter($this->selectedProductIds));

        if (empty($selectedIds)) {
            Notification::make()
                ->title(__('Validation Error'))
                ->body(__('You must select at least one product.'))
                ->danger()
                ->send();

            $this->halt();
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
            $qty = max(1, (int) $qty);
            $total += $product->price * $qty;
        }

        $this->data['total'] = $total;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['products']);

        $selectedIds = array_keys(array_filter($this->selectedProductIds));
        $selectedProducts = Product::whereIn('id', $selectedIds)->get();
        
        $productsToSave = [];
        $total = 0;

        foreach ($selectedProducts as $product) {
            $quantity = $this->quantities[$product->id] ?? 1;
            $quantity = max(1, (int)$quantity);
            
            if ($data['status'] === 'completed' && $product->stock < $quantity) {
                Notification::make()
                    ->title(__('Stock Error'))
                    ->body(__('Insufficient stock to complete the order with the product: :name.', ['name' => $product->name]))
                    ->danger()
                    ->send();
                
                $this->halt();
            }

            $productsToSave[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ];

            $total += $product->price * $quantity;
        }

        $data['total'] = $total;
        $this->cachedProducts = $productsToSave;

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->cachedProducts as $productData) {
            $this->record->products()->attach($productData['product_id'], [
                'quantity' => $productData['quantity'],
                'price' => $productData['price'],
            ]);
        }

        if ($this->record->status === 'completed') {
            foreach ($this->cachedProducts as $productData) {
                $product = Product::find($productData['product_id']);
                if ($product) {
                    $product->decrement('stock', $productData['quantity']);
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
