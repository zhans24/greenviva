<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('Номер заказа')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->required()
                    ->maxLength(32),

                TextInput::make('address')
                    ->label('Адрес')
                    ->required()
                    ->maxLength(500),

                Textarea::make('comment')
                    ->label('Комментарий')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Статус')
                    ->required()
                    ->native(false)
                    ->options([
                        'pending'   => 'Новый',
                        'confirmed' => 'Подтверждён',
                        'shipped'   => 'Отправлен',
                        'cancelled' => 'Отменён',
                    ]),

                TextInput::make('total_price')
                    ->label('Сумма, ₸')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
            ])
            ->columns(2);
    }
}
