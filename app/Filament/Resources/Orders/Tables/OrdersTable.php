<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('# Order'))
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label(__('User'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'info',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => __('Created'),
                        'completed' => __('Completed'),
                        'canceled' => __('Canceled'),
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('total')
                    ->label(__('Total'))
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'created' => __('Created'),
                        'completed' => __('Completed'),
                        'canceled' => __('Canceled'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('No orders found'))
            ->emptyStateDescription(__('You have not created any orders yet.'))
            ->defaultSort('created_at', 'desc');
    }
}
