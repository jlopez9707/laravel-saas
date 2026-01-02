<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('description')
                    ->label(__('Description'))
                    ->required(),
                TextInput::make('price')
                    ->label(__('Price'))
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->label(__('Stock'))
                    ->numeric()
                    ->required(),
                Select::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
