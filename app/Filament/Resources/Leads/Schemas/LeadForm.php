<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Имя')->required()->maxLength(255),
            TextInput::make('phone')->label('Телефон')->tel()->required()->maxLength(64),
            Textarea::make('message')->label('Сообщение')->columnSpanFull(),
            TextInput::make('source')->label('Источник')->disabled()->dehydrated(false),
            Select::make('status')->label('Статус')->native(false)->options([
                'new'       => 'Новая',
                'processed' => 'Обработана',
                'spam'      => 'Спам',
            ])->required(),
        ])->columns(2);
    }
}
