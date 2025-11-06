<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // ✅ нужно для кастомного поиска/сортировки

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ✅ коллекция tile (как в модели)
                SpatieMediaLibraryImageColumn::make('tile')
                    ->collection('tile')
                    ->conversion('webp')
                    ->label('Фото'),

                TextColumn::make('name')
                    ->label('Название')
                    // показываем перевод (текущая локаль → RU)
                    ->formatStateUsing(fn ($state, $record) =>
                    $record->getTranslation('name', app()->getLocale(), false)
                        ?: $record->getTranslation('name', 'ru', false)
                    )
                    // опционально: простой поиск по RU (и текущей локали, если надо)
                    ->searchable(query: function (Builder $query, string $search) {
                        $loc = app()->getLocale();
                        return $query->where(function (Builder $q) use ($search, $loc) {
                            $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"ru\"')) LIKE ?", ["%{$search}%"])
                                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"{$loc}\"')) LIKE ?", ["%{$search}%"]);
                        });
                    }),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                TextColumn::make('sort')
                    ->label('Сорт.')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Актив.'),

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
