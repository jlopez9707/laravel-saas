<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('usd', true)
                    ->sortable(),
                TextColumn::make('stock')
                    ->label(__('Stock'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                ->label(__('Categories'))
                ->options(fn (): array => Category::query()->pluck('name', 'id')->all())
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading(__('No products found'))
            ->emptyStateDescription(__('You have not created any products yet'))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
