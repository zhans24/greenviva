<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // 👈 для кастомной сортировки

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->conversion('webp')
                    ->label('Фото'),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('sku')
                    ->label('Код')
                    ->toggleable(),

                // 🔹 ЕДИНЫЙ столбец "Цена, ₸"
                TextColumn::make('effective_price')
                    ->label('Цена, ₸')
                    ->state(fn ($record) => $record->price
                        ? $record->price_formatted
                        : $record->old_price_formatted
                    )
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // сортируем по COALESCE(price, old_price)
                        return $query->orderByRaw('COALESCE(price, old_price) ' . $direction);
                    })
                    ->alignRight(),

                ToggleColumn::make('is_popular')
                    ->label('Популярность')
                    ->sortable(),

                ToggleColumn::make('is_best_seller')
                    ->label('Best')
                    ->sortable(),


                TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
