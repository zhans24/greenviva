<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([
                Tabs\Tab::make('Данные')->schema([
                    Section::make()->schema([
                        Hidden::make('slug_is_custom')->default(false),

                        TextInput::make('name')
                            ->label('Название')->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (! $get('slug_is_custom')) {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Слаг')->required()->unique(ignoreRecord: true)
                            ->rule('alpha_dash')->maxLength(60)
                            ->suffixAction(
                                Action::make('generateSlug')
                                    ->icon('heroicon-m-arrow-path')
                                    ->tooltip('Сгенерировать из названия')
                                    ->action(fn (Get $get, Set $set) => [
                                        $set('slug', Str::slug((string) $get('name'))),
                                        $set('slug_is_custom', false),
                                    ])
                            )
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('slug_is_custom', filled($state));
                            }),

                        TextInput::make('sort')->numeric()->default(0),
                        Toggle::make('is_active')->default(true)->label('Активна'),
                    ])->columns(2),

                ]),
                Tabs\Tab::make('Медиа')->schema([
                    Section::make('Плитка')->schema([
                        SpatieMediaLibraryFileUpload::make('tile')
                            ->collection('tile')
                            ->label('Изображение категории')
                            ->image()->responsiveImages()
                            ->openable()->downloadable()
                            ->preserveFilenames()
                            ->panelLayout('grid')
                            ->columnSpanFull(),
                    ]),
                ]),
                Tabs\Tab::make('SEO')->schema([
                    Section::make()->schema([
                        TextInput::make('seo_title')->label('Meta title')->maxLength(255)
                            ->helperText('Пусто → возьмётся название'),
                        TextInput::make('seo_h1')->label('H1')->maxLength(255)
                            ->helperText('Пусто → возьмётся название'),
                        Textarea::make('seo_description')->label('Meta description')->rows(3)
                            ->helperText('Пусто → 160 символов'),
                    ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
