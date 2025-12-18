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
                    ->label('User')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'created' => 'Created',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
                    ->default('created')
                    ->required(),
                ProductTableField::make('products')
                    ->label('Select Products')
                    ->columnSpanFull(),
                TextInput::make('total')
                    ->label('Total Order Value')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->readOnly()
                    ->dehydrated()
                    ->columnStart(2),
            ]);
    }
}
