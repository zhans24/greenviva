<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Позиции заказа';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product_id')->label('ID товара')->toggleable(),
                Tables\Columns\TextColumn::make('sku')->label('SKU')->toggleable(),
                Tables\Columns\TextColumn::make('name')->label('Наименование')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('price')->label('Цена, ₸')->numeric(),
                Tables\Columns\TextColumn::make('quantity')->label('Кол-во'),
                Tables\Columns\TextColumn::make('total_price')->label('Итого, ₸')->numeric(),
                Tables\Columns\TextColumn::make('created_at')->label('Добавлено')->dateTime('d.m.Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])
            // читаем только — позиции формируются при оформлении заказа
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
