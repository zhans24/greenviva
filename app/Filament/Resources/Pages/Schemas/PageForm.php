<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\{Section, Grid, Tabs};
use Filament\Forms\Components\{
    Hidden, SpatieMediaLibraryFileUpload, TextInput, Textarea, Toggle, RichEditor, Placeholder
};
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ====== BASE ======
            Section::make('Основные')
                ->description('SEO')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('title.ru')->label('Заголовок (RU)')->required(),
                        TextInput::make('title.kz')->label('Заголовок (KZ)'),
                        TextInput::make('title.en')->label('Заголовок (EN)'),
                    ])->visible(false),
                    Grid::make(3)->schema([
                        TextInput::make('slug')->label('Слаг')->required()->unique(ignoreRecord: true),
                        TextInput::make('template')->label('Шаблон')->required()
                            ->helperText('Используй: home, about, privacy'),
                        Toggle::make('is_published')->label('Опубликовано')->default(true),
                    ])->visible(false),
                    Tabs::make('meta_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            TextInput::make('meta_title.ru')->label('Meta Title (RU)')->maxLength(70),
                            Textarea::make('meta_description.ru')->label('Meta Description (RU)')->rows(2)->maxLength(300),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            TextInput::make('meta_title.kz')->label('Meta Title (KZ)')->maxLength(70),
                            Textarea::make('meta_description.kz')->label('Meta Description (KZ)')->rows(2)->maxLength(300),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            TextInput::make('meta_title.en')->label('Meta Title (EN)')->maxLength(70),
                            Textarea::make('meta_description.en')->label('Meta Description (EN)')->rows(2)->maxLength(300),
                        ]),
                    ]),
                ])->columnSpanFull(),

            // ====== HOME ======
            Section::make('Главная: Hero')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    Tabs::make('home_hero_tabs')->tabs([

                        // RU → тексты + КАРТИНКИ (общие)
                        Tabs\Tab::make('RU')->schema([
                            Placeholder::make('hero_hint_ru')
                                ->content('Слайды: заголовок, подзаголовок, ссылка. Картинки загружаются ТОЛЬКО здесь (они общие для всех языков).'),
                            \Filament\Forms\Components\Repeater::make('content.ru.home.hero')
                                ->label('Слайды Hero (RU)')
                                ->reorderable()
                                ->defaultItems(1)
                                ->schema([
                                    Hidden::make('uid')->default(fn () => (string) Str::ulid()),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Заголовок')->required()->maxLength(180),
                                        TextInput::make('text')->label('Подзаголовок')->maxLength(200),
                                        TextInput::make('btn_text')->label('Текст кнопки')->default('Перейти в каталог')->maxLength(50),
                                        TextInput::make('btn_url')->label('Ссылка кнопки')->default('/catalog')->maxLength(255),
                                    ]),
                                    Grid::make(3)->schema([
                                        SpatieMediaLibraryFileUpload::make('left')
                                            ->label('Картинка слева (общая)')
                                            ->collection(fn ($get) => 'home_hero_left_'.$get('uid'))
                                            ->image()->maxFiles(1)->openable(),
                                        SpatieMediaLibraryFileUpload::make('center')
                                            ->label('Картинка по центру (общая)')
                                            ->collection(fn ($get) => 'home_hero_center_'.$get('uid'))
                                            ->image()->maxFiles(1)->openable(),
                                        SpatieMediaLibraryFileUpload::make('right')
                                            ->label('Картинка справа (общая)')
                                            ->collection(fn ($get) => 'home_hero_right_'.$get('uid'))
                                            ->image()->maxFiles(1)->openable(),
                                    ]),
                                ]),
                        ]),

                        // KZ → только ТЕКСТЫ
                        Tabs\Tab::make('KZ')->schema([
                            Placeholder::make('hero_hint_kz')
                                ->content('Только текст. Картинки общие и задаются во вкладке RU.'),
                            \Filament\Forms\Components\Repeater::make('content.kz.home.hero')
                                ->label('Слайды Hero (KZ)')
                                ->reorderable()
                                ->defaultItems(1)
                                ->schema([
                                    Hidden::make('uid')->default(fn () => (string) Str::ulid()),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Тақырып')->maxLength(180),
                                        TextInput::make('text')->label('Подтақырып')->maxLength(200),
                                        TextInput::make('btn_text')->label('Түйме мәтіні')->maxLength(50),
                                        TextInput::make('btn_url')->label('Сілтеме')->maxLength(255),
                                    ]),
                                ]),
                        ]),

                        // EN → только ТЕКСТЫ
                        Tabs\Tab::make('EN')->schema([
                            Placeholder::make('hero_hint_en')
                                ->content('Text only. Images are shared and uploaded in RU tab.'),
                            \Filament\Forms\Components\Repeater::make('content.en.home.hero')
                                ->label('Hero Slides (EN)')
                                ->reorderable()
                                ->defaultItems(1)
                                ->schema([
                                    Hidden::make('uid')->default(fn () => (string) Str::ulid()),
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->label('Title')->maxLength(180),
                                        TextInput::make('text')->label('Subtitle')->maxLength(200),
                                        TextInput::make('btn_text')->label('Button text')->maxLength(50),
                                        TextInput::make('btn_url')->label('Button url')->maxLength(255),
                                    ]),
                                ]),
                        ]),
                    ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Преимущества (фикс. 3 карточки)')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    Tabs::make('advantages_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            TextInput::make('content.ru.home.advantages.title')->label('Заголовок секции')->default('Преимущества')->maxLength(120),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.home.advantages.items.0.title')->label('Карта 1 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.home.advantages.items.0.text')->label('Карта 1 — Текст')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.home.advantages.items.1.title')->label('Карта 2 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.home.advantages.items.1.text')->label('Карта 2 — Текст')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.home.advantages.items.2.title')->label('Карта 3 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.home.advantages.items.2.text')->label('Карта 3 — Текст')->rows(2)->maxLength(400),
                            ]),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            TextInput::make('content.kz.home.advantages.title')->label('Бөлім тақырыбы')->maxLength(120),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.home.advantages.items.0.title')->label('1 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.home.advantages.items.0.text')->label('1 — Мәтін')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.home.advantages.items.1.title')->label('2 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.home.advantages.items.1.text')->label('2 — Мәтін')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.home.advantages.items.2.title')->label('3 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.home.advantages.items.2.text')->label('3 — Мәтін')->rows(2)->maxLength(400),
                            ]),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            TextInput::make('content.en.home.advantages.title')->label('Section title')->maxLength(120),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.home.advantages.items.0.title')->label('Card 1 — Title')->maxLength(120),
                                Textarea::make('content.en.home.advantages.items.0.text')->label('Card 1 — Text')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.home.advantages.items.1.title')->label('Card 2 — Title')->maxLength(120),
                                Textarea::make('content.en.home.advantages.items.1.text')->label('Card 2 — Text')->rows(2)->maxLength(400),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.home.advantages.items.2.title')->label('Card 3 — Title')->maxLength(120),
                                Textarea::make('content.en.home.advantages.items.2.text')->label('Card 3 — Text')->rows(2)->maxLength(400),
                            ]),
                        ]),
                    ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Акции (баннеры) – общие')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('home_banners')
                        ->label('Слайды баннеров (общие для всех языков)')
                        ->collection('home_banners')
                        ->image()->multiple()->reorderable()
                        ->openable()->downloadable()->preserveFilenames()
                        ->panelLayout('grid')
                        ->helperText('Загружайте в нужном порядке. Рекоменд. размер ~1440×420.'),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Логотипы брендов (текст)')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    Tabs::make('brands_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            \Filament\Forms\Components\Repeater::make('content.ru.home.brands.items')
                                ->label('Элементы карусели брендов (RU)')
                                ->reorderable()
                                ->minItems(12)
                                ->schema([ TextInput::make('label')->label('Название')->maxLength(40) ])
                                ->columns(1)
                                ->helperText('Минимум 12 элементов, чтобы бегущая строка выглядела как в макете.'),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            \Filament\Forms\Components\Repeater::make('content.kz.home.brands.items')
                                ->label('Элементтер (KZ)')
                                ->reorderable()
                                ->schema([ TextInput::make('label')->label('Атауы')->maxLength(40) ])
                                ->columns(1),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            \Filament\Forms\Components\Repeater::make('content.en.home.brands.items')
                                ->label('Items (EN)')
                                ->reorderable()
                                ->schema([ TextInput::make('label')->label('Label')->maxLength(40) ])
                                ->columns(1),
                        ]),
                    ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Отзывы')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    \Filament\Forms\Components\Repeater::make('content.home.reviews')
                        ->label('Отзывы клиентов (общий список, текст — переводимый)')
                        ->reorderable()->minItems(1)
                        ->schema([
                            Hidden::make('uid')->default(fn () => (string) Str::ulid()),
                            Grid::make(3)->schema([
                                TextInput::make('author_name')->label('Автор')->required()->maxLength(120),
                                Toggle::make('is_active')->label('Показывать')->default(true),
                            ]),
                            Tabs::make('review_text_tabs')->tabs([
                                Tabs\Tab::make('RU')->schema([
                                    Textarea::make('text.ru')->label('Текст (RU)')->rows(4)->maxLength(2000),
                                ]),
                                Tabs\Tab::make('KZ')->schema([
                                    Textarea::make('text.kz')->label('Текст (KZ)')->rows(4)->maxLength(2000),
                                ]),
                                Tabs\Tab::make('EN')->schema([
                                    Textarea::make('text.en')->label('Text (EN)')->rows(4)->maxLength(2000),
                                ]),
                            ]),
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->label('Аватар (общий)')
                                ->collection(fn ($get) => 'home_review_'.$get('uid'))
                                ->image()->maxFiles(1)->openable(),
                        ])->columns(1)
                        ->helperText('Порядок можно менять перетаскиванием. Неактивные скрываются.'),
                ])->columnSpanFull()->collapsed(),

            // ====== ABOUT ======
            Section::make('О компании: История')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    Tabs::make('about_history_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.about.history_title')->label('История: заголовок (RU)')->maxLength(120)->default('История компании'),
                                Textarea::make('content.ru.about.history_text')->label('История: текст (RU)')->rows(5)->maxLength(5000),
                            ])->columnSpanFull(),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.about.history_title')->label('Тарих: тақырып (KZ)')->maxLength(120),
                                Textarea::make('content.kz.about.history_text')->label('Тарих: мәтін (KZ)')->rows(5)->maxLength(5000),
                            ])->columnSpanFull(),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('content.en.about.history_title')->label('History: title (EN)')->maxLength(120),
                                Textarea::make('content.en.about.history_text')->label('History: text (EN)')->rows(5)->maxLength(5000),
                            ])->columnSpanFull(),
                        ]),
                    ]),
                    SpatieMediaLibraryFileUpload::make('about_history')
                        ->label('История: изображение (общее)')
                        ->collection('about_history')->image()->maxFiles(1)->openable(),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Миссия и ценности (ровно 3 + 3)')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    Tabs::make('about_mission_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            TextInput::make('content.ru.about.mission_title')->label('Миссия: заголовок (RU)')->maxLength(120)->default('Миссия и ценности'),
                            Textarea::make('content.ru.about.mission_subtitle')->label('Миссия: подзаголовок (RU)')->rows(3)->maxLength(1000),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.about.mission_stats.0.value')->label('Статистика 1 — Число')->placeholder('345+')->maxLength(20),
                                TextInput::make('content.ru.about.mission_stats.0.label')->label('Статистика 1 — Подпись')->placeholder('Сертифицированных товаров')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.about.mission_stats.1.value')->label('Статистика 2 — Число')->placeholder('120+')->maxLength(20),
                                TextInput::make('content.ru.about.mission_stats.1.label')->label('Статистика 2 — Подпись')->placeholder('Натуральных брендов')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.ru.about.mission_stats.2.value')->label('Статистика 3 — Число')->placeholder('15')->maxLength(20),
                                TextInput::make('content.ru.about.mission_stats.2.label')->label('Статистика 3 — Подпись')->placeholder('Стран поставщиков')->maxLength(120),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.ru.about.mission_cards.0.title')->label('Карточка 1 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.about.mission_cards.0.text')->label('Карточка 1 — Текст')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.ru.about.mission_cards.1.title')->label('Карточка 2 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.about.mission_cards.1.text')->label('Карточка 2 — Текст')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.ru.about.mission_cards.2.title')->label('Карточка 3 — Заголовок')->maxLength(120),
                                Textarea::make('content.ru.about.mission_cards.2.text')->label('Карточка 3 — Текст')->rows(3)->maxLength(500),
                            ]),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            TextInput::make('content.kz.about.mission_title')->label('Миссия: тақырып (KZ)')->maxLength(120),
                            Textarea::make('content.kz.about.mission_subtitle')->label('Миссия: подтақырып (KZ)')->rows(3)->maxLength(1000),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.about.mission_stats.0.value')->label('1 — Сан')->maxLength(20),
                                TextInput::make('content.kz.about.mission_stats.0.label')->label('1 — Жазу')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.about.mission_stats.1.value')->label('2 — Сан')->maxLength(20),
                                TextInput::make('content.kz.about.mission_stats.1.label')->label('2 — Жазу')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.kz.about.mission_stats.2.value')->label('3 — Сан')->maxLength(20),
                                TextInput::make('content.kz.about.mission_stats.2.label')->label('3 — Жазу')->maxLength(120),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.kz.about.mission_cards.0.title')->label('1 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.about.mission_cards.0.text')->label('1 — Мәтін')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.kz.about.mission_cards.1.title')->label('2 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.about.mission_cards.1.text')->label('2 — Мәтін')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.kz.about.mission_cards.2.title')->label('3 — Тақырып')->maxLength(120),
                                Textarea::make('content.kz.about.mission_cards.2.text')->label('3 — Мәтін')->rows(3)->maxLength(500),
                            ]),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            TextInput::make('content.en.about.mission_title')->label('Mission: title')->maxLength(120),
                            Textarea::make('content.en.about.mission_subtitle')->label('Mission: subtitle')->rows(3)->maxLength(1000),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.about.mission_stats.0.value')->label('Stat 1 — Value')->maxLength(20),
                                TextInput::make('content.en.about.mission_stats.0.label')->label('Stat 1 — Label')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.about.mission_stats.1.value')->label('Stat 2 — Value')->maxLength(20),
                                TextInput::make('content.en.about.mission_stats.1.label')->label('Stat 2 — Label')->maxLength(120),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('content.en.about.mission_stats.2.value')->label('Stat 3 — Value')->maxLength(20),
                                TextInput::make('content.en.about.mission_stats.2.label')->label('Stat 3 — Label')->maxLength(120),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.en.about.mission_cards.0.title')->label('Card 1 — Title')->maxLength(120),
                                Textarea::make('content.en.about.mission_cards.0.text')->label('Card 1 — Text')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.en.about.mission_cards.1.title')->label('Card 2 — Title')->maxLength(120),
                                Textarea::make('content.en.about.mission_cards.1.text')->label('Card 2 — Text')->rows(3)->maxLength(500),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('content.en.about.mission_cards.2.title')->label('Card 3 — Title')->maxLength(120),
                                Textarea::make('content.en.about.mission_cards.2.text')->label('Card 3 — Text')->rows(3)->maxLength(500),
                            ]),
                        ]),
                    ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Сертификаты качества (слайдер) – общие')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('about_certificates')
                        ->label('Сертификаты (картинки, общие)')
                        ->collection('about_certificates')
                        ->image()->multiple()->reorderable()
                        ->openable()->panelLayout('grid'),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Фото команды/производства (галерея) – общие')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('about_album')
                        ->label('Фотографии (общие)')
                        ->collection('about_album')
                        ->image()->multiple()->reorderable()
                        ->openable()->panelLayout('grid')
                        ->helperText('Загружайте в нужном порядке — выводится «как есть».'),
                ])->columnSpanFull()->collapsed(),

            // ====== PRIVACY ======
            Section::make('Политика конфиденциальности (privacy)')
                ->visible(fn ($get) => $get('template') === 'privacy')
                ->schema([
                    Tabs::make('privacy_tabs')->tabs([
                        Tabs\Tab::make('RU')->schema([
                            TextInput::make('content.ru.privacy.title')->label('Заголовок H1 (RU)')
                                ->default('Политика конфиденциальности')->maxLength(180),
                            RichEditor::make('content.ru.privacy.body')->label('Текст (RU)')
                                ->toolbarButtons([
                                    'bold','italic','underline','strike','h2','h3','blockquote','orderedList','bulletList','link','undo','redo','codeBlock',
                                ])->columnSpanFull()->required(),
                        ]),
                        Tabs\Tab::make('KZ')->schema([
                            TextInput::make('content.kz.privacy.title')->label('H1 (KZ)')->maxLength(180),
                            RichEditor::make('content.kz.privacy.body')->label('Мәтін (KZ)')
                                ->toolbarButtons([
                                    'bold','italic','underline','strike','h2','h3','blockquote','orderedList','bulletList','link','undo','redo','codeBlock',
                                ])->columnSpanFull(),
                        ]),
                        Tabs\Tab::make('EN')->schema([
                            TextInput::make('content.en.privacy.title')->label('H1 (EN)')->maxLength(180),
                            RichEditor::make('content.en.privacy.body')->label('Text (EN)')
                                ->toolbarButtons([
                                    'bold','italic','underline','strike','h2','h3','blockquote','orderedList','bulletList','link','undo','redo','codeBlock',
                                ])->columnSpanFull(),
                        ]),
                    ]),
                ])->columnSpanFull(),
        ]);
    }
}
