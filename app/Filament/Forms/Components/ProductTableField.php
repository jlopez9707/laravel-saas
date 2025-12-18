<?php

namespace App\Filament\Forms\Components;

use App\Models\Product;
use Filament\Forms\Components\Field;
use Illuminate\Support\Collection;

class ProductTableField extends Field
{
    protected string $view = 'filament.forms.components.product-table-field';

    protected Collection|null $products = null;

    public function getProducts(): Collection
    {
        if ($this->products === null) {
            $this->products = Product::query()
                ->orderBy('name')
                ->get();
        }

        return $this->products;
    }

    public function products(Collection $products): static
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get the selected products with their quantities and prices
     */
    public function getSelectedProducts(): array
    {
        $state = $this->getState();
        
        if (empty($state) || !is_array($state)) {
            return [];
        }

        return $state;
    }

    /**
     * Calculate the total for all selected products
     */
    public function calculateTotal(): float
    {
        $selectedProducts = $this->getSelectedProducts();
        $total = 0;

        foreach ($selectedProducts as $product) {
            if (isset($product['price']) && isset($product['quantity'])) {
                $total += $product['price'] * $product['quantity'];
            }
        }

        return $total;
    }
}
