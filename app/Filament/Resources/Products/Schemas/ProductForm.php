<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->tabs([
                    Tabs\Tab::make('Данные')->schema([
                        Section::make()->schema([
                            Select::make('category_id')
                                ->relationship('category','name')
                                ->label('Категория')->searchable()->preload(),

                            Select::make('brand_id')
                                ->label('Бренд')
                                ->relationship('brand', 'name')
                                ->searchable()->preload()->required(),

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
                                ->afterStateUpdated(fn ($state, Set $set) => $set('slug_is_custom', filled($state))),


                            TextInput::make('sku')->label('Код товара')->maxLength(100),

                            // 🔁 Цены: «Цена» = old_price (required), «Цена со скидкой» = price (<= old_price)
                            TextInput::make('old_price')
                                ->label('Цена, ₸')
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->suffix('₸'),

                            TextInput::make('price')
                                ->label('Цена со скидкой, ₸')
                                ->numeric()
                                ->minValue(0)
                                ->suffix('₸')
                                ->helperText('Не больше, чем «Цена».')
                                ->maxValue(fn (Get $get) => $get('old_price') !== null ? (int) $get('old_price') : null),

                            Toggle::make('is_available')->label('В наличии')->default(true),
                            Toggle::make('is_best_seller')->label('Best seller'),
                            Toggle::make('is_popular')->label('Популярное')->default(false),
                        ])->columns(3),

                        Section::make('Вкладки карточки')->schema([
                            RichEditor::make('description')->label('Описание')->columnSpanFull(),
                            RichEditor::make('composition')->label('Состав')->columnSpanFull(),
                            RichEditor::make('usage')->label('Применение')->columnSpanFull(),
                            Textarea::make('delivery_info')->label('Доставка/оплата')->rows(3)->columnSpanFull(),
                        ]),
                    ]),

                    Tabs\Tab::make('Медиа')->schema([
                        Section::make('Обложка')->schema([
                            SpatieMediaLibraryFileUpload::make('cover')
                                ->collection('cover')->label('Обложка')
                                ->image()->responsiveImages()
                                ->openable()->downloadable()
                                ->preserveFilenames()
                                ->panelLayout('compact')
                                ->columnSpanFull(),
                        ]),
                        Section::make('Галерея')->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->collection('gallery')->multiple()
                                ->image()->reorderable()->responsiveImages()
                                ->openable()->downloadable()
                                ->preserveFilenames()
                                ->panelLayout('grid')
                                ->columnSpanFull(),
                        ]),
                        Section::make('Сертификаты')->schema([
                            SpatieMediaLibraryFileUpload::make('certificates')
                                ->collection('certificates')->multiple()
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
                                ->helperText('Пусто → 160 символов из описания'),
                        ])->columns(1),
                    ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
