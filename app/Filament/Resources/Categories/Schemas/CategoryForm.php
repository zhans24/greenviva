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

                        TextInput::make('name.ru')->label('Название (RU)')->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (! $get('slug_is_custom') && filled($state)) {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),

                        TextInput::make('name.kz')->label('Атауы (KZ)'),
                        TextInput::make('name.en')->label('Name (EN)'),

                        TextInput::make('slug')
                            ->label('Слаг')->required()->unique(ignoreRecord: true)
                            ->rule('alpha_dash')->maxLength(60)
                            ->suffixAction(
                                Action::make('generateSlug')
                                    ->icon('heroicon-m-arrow-path')
                                    ->tooltip('Сгенерировать из RU названия')
                                    ->action(fn (Get $get, Set $set) => [
                                        $set('slug', \Illuminate\Support\Str::slug((string) $get('name.ru'))),
                                        $set('slug_is_custom', false),
                                    ])
                            )
                            ->afterStateUpdated(fn ($state, Set $set) => $set('slug_is_custom', filled($state))),

                        TextInput::make('sort')->numeric()->default(0),
                        Toggle::make('is_active')->default(true)->label('Активна'),
                    ])->columns(2),
                ]),

                // ===== МЕДИА
                Tabs\Tab::make('Медиа')->schema([
                    Section::make('Фото')->schema([
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

                // ===== SEO (локализовано)
                Tabs\Tab::make('SEO')->schema([
                    Section::make()->schema([
                        Tabs::make('SEO локали')->tabs([
                            Tabs\Tab::make('RU')->schema([
                                TextInput::make('seo_title.ru')->label('Meta title (RU)')->maxLength(255)
                                    ->helperText('Пусто → возьмётся название'),
                                TextInput::make('seo_h1.ru')->label('H1 (RU)')->maxLength(255)
                                    ->helperText('Пусто → возьмётся название'),
                                Textarea::make('seo_description.ru')->label('Meta description (RU)')->rows(3)
                                    ->helperText('Пусто → 160 символов'),
                            ]),
                            Tabs\Tab::make('KZ')->schema([
                                TextInput::make('seo_title.kz')->label('Meta title (KZ)')->maxLength(255),
                                TextInput::make('seo_h1.kz')->label('H1 (KZ)')->maxLength(255),
                                Textarea::make('seo_description.kz')->label('Meta description (KZ)')->rows(3),
                            ]),
                            Tabs\Tab::make('EN')->schema([
                                TextInput::make('seo_title.en')->label('Meta title (EN)')->maxLength(255),
                                TextInput::make('seo_h1.en')->label('H1 (EN)')->maxLength(255),
                                Textarea::make('seo_description.en')->label('Meta description (EN)')->rows(3),
                            ]),
                        ])->columnSpanFull(),
                    ]),
                ]),

            ])->columnSpanFull(),
        ]);
    }
}
