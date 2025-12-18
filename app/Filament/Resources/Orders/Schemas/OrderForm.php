<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Forms\Components\ProductTableField;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('User'))
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'created' => __('Created'),
                        'completed' => __('Completed'),
                        'canceled' => __('Canceled'),
                    ])
                    ->default('created')
                    ->required(),
                ProductTableField::make('products')
                    ->label(__('Select Products'))
                    ->columnSpanFull(),
                TextInput::make('total')
                    ->label(__('Total Order Value'))
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->readOnly()
                    ->dehydrated()
                    ->columnStart(2),
            ]);
    }
}
