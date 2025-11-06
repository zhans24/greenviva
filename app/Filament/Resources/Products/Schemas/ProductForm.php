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

                    // =======================
                    //      ДАННЫЕ
                    // =======================
                    Tabs\Tab::make('Данные')->schema([
                        Section::make()->schema([
                            Select::make('category_id')
                                ->relationship('category', 'id')
                                ->label('Категория')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => $record->getTranslation('name', app()->getLocale(), false)
                                        ?: $record->getTranslation('name', 'ru', false)
                                ),

                            Select::make('brand_id')
                                ->relationship('brand', 'id')
                                ->label('Бренд')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => $record->getTranslation('name', app()->getLocale(), false)
                                        ?: $record->getTranslation('name', 'ru', false)
                                ),


                            Hidden::make('slug_is_custom')->default(false),

                            TextInput::make('name.ru')->label('Название (RU)')->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if (! $get('slug_is_custom') && filled($state)) {
                                        $set('slug', Str::slug($state));
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
                                            $set('slug', Str::slug((string) $get('name.ru'))),
                                            $set('slug_is_custom', false),
                                        ])
                                )
                                ->afterStateUpdated(fn ($state, Set $set) => $set('slug_is_custom', filled($state))),

                            TextInput::make('sku')->label('Код товара')->maxLength(100),

                            TextInput::make('old_price')->label('Цена, ₸')
                                ->numeric()->minValue(0)->required()->suffix('₸'),

                            TextInput::make('price')->label('Цена со скидкой, ₸')
                                ->numeric()->minValue(0)->suffix('₸')
                                ->helperText('Не больше, чем «Цена».')
                                ->maxValue(fn (Get $get) => $get('old_price') ?? null),

                            Toggle::make('is_available')->label('В наличии')->default(true),
                            Toggle::make('is_best_seller')->label('Best seller'),
                            Toggle::make('is_popular')->label('Популярное'),
                        ])->columns(3),

                        // =======================
                        //    КОНТЕНТ ТОВАРА
                        // =======================
                        Section::make('Описание / Состав / Применение / Доставка')->schema([

                            Tabs::make('content_tabs')->tabs([
                                Tabs\Tab::make('RU')->schema([
                                    RichEditor::make('description.ru')->label('Описание (RU)')->columnSpanFull(),
                                    RichEditor::make('composition.ru')->label('Состав (RU)')->columnSpanFull(),
                                    RichEditor::make('usage.ru')->label('Применение (RU)')->columnSpanFull(),
                                    RichEditor::make('delivery_info.ru')->label('Доставка/оплата (RU)')->columnSpanFull(),
                                ]),
                                Tabs\Tab::make('KZ')->schema([
                                    RichEditor::make('description.kz')->label('Описание (KZ)'),
                                    RichEditor::make('composition.kz')->label('Состав (KZ)'),
                                    RichEditor::make('usage.kz')->label('Применение (KZ)'),
                                    RichEditor::make('delivery_info.kz')->label('Доставка/оплата (KZ)'),
                                ]),
                                Tabs\Tab::make('EN')->schema([
                                    RichEditor::make('description.en')->label('Description (EN)'),
                                    RichEditor::make('composition.en')->label('Composition (EN)'),
                                    RichEditor::make('usage.en')->label('Usage (EN)'),
                                    RichEditor::make('delivery_info.en')->label('Delivery (EN)'),
                                ]),
                            ])
                        ]),
                    ]),

                    // =======================
                    //        МЕДИА
                    // =======================
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

                    // =======================
                    //          SEO
                    // =======================
                    Tabs\Tab::make('SEO')->schema([
                        Section::make('Мета-теги')->schema([
                            Tabs::make()->tabs([
                                Tabs\Tab::make('RU')->schema([
                                    TextInput::make('seo_title.ru')->label('Meta title (RU)'),
                                    TextInput::make('seo_h1.ru')->label('H1 (RU)'),
                                    Textarea::make('seo_description.ru')->label('Meta description (RU)')->rows(3),
                                ]),
                                Tabs\Tab::make('KZ')->schema([
                                    TextInput::make('seo_title.kz')->label('Meta title (KZ)'),
                                    TextInput::make('seo_h1.kz')->label('H1 (KZ)'),
                                    Textarea::make('seo_description.kz')->label('Meta description (KZ)')->rows(3),
                                ]),
                                Tabs\Tab::make('EN')->schema([
                                    TextInput::make('seo_title.en')->label('Meta title (EN)'),
                                    TextInput::make('seo_h1.en')->label('H1 (EN)'),
                                    Textarea::make('seo_description.en')->label('Meta description (EN)')->rows(3),
                                ]),
                            ])
                        ]),
                    ]),

                ])
                ->columnSpanFull(),
        ]);
    }
}
