<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\{Section, Grid};
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
                ->description('Заголовок, слаг, шаблон и мета-инфо')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('title')->label('Заголовок')->required(),
                        TextInput::make('slug')->label('Слаг')->required()->unique(ignoreRecord: true),
                        TextInput::make('template')->label('Шаблон')->required()
                            ->helperText('Используй: home, about, privacy'),
                    ])->visible(false),
                    Grid::make(3)->schema([
                        Toggle::make('is_published')->label('Опубликовано')->default(true),
                        TextInput::make('meta_title')->label('Meta Title')->maxLength(70),
                        Textarea::make('meta_description')->label('Meta Description')->rows(2)->maxLength(300),
                    ]),
                ])->columnSpanFull(),

            // ====== HOME ======
            Section::make('Главная: Hero')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    Placeholder::make('hero_hint')->content('Добавляй слайды: заголовок, подзаголовок, ссылка, 3 картинки (лево/центр/право).'),
                    \Filament\Forms\Components\Repeater::make('content.home.hero')
                        ->label('Слайды Hero')
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
                                    ->label('Картинка слева')->collection(fn ($get) => 'home_hero_left_'.$get('uid'))
                                    ->image()->maxFiles(1)->openable(),
                                SpatieMediaLibraryFileUpload::make('center')
                                    ->label('Картинка по центру')->collection(fn ($get) => 'home_hero_center_'.$get('uid'))
                                    ->image()->maxFiles(1)->openable(),
                                SpatieMediaLibraryFileUpload::make('right')
                                    ->label('Картинка справа')->collection(fn ($get) => 'home_hero_right_'.$get('uid'))
                                    ->image()->maxFiles(1)->openable(),
                            ]),
                        ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Преимущества (фикс. 3 карточки)')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    TextInput::make('content.home.advantages.title')
                        ->label('Заголовок секции')->default('Преимущества')->maxLength(120),

                    // Карточка 1
                    Grid::make(2)->schema([
                        TextInput::make('content.home.advantages.items.0.title')->label('Карточка 1 — Заголовок')->maxLength(120),
                        Textarea::make('content.home.advantages.items.0.text')->label('Карточка 1 — Текст')->rows(2)->maxLength(400),
                    ]),
                    // Карточка 2
                    Grid::make(2)->schema([
                        TextInput::make('content.home.advantages.items.1.title')->label('Карточка 2 — Заголовок')->maxLength(120),
                        Textarea::make('content.home.advantages.items.1.text')->label('Карточка 2 — Текст')->rows(2)->maxLength(400),
                    ]),
                    // Карточка 3
                    Grid::make(2)->schema([
                        TextInput::make('content.home.advantages.items.2.title')->label('Карточка 3 — Заголовок')->maxLength(120),
                        Textarea::make('content.home.advantages.items.2.text')->label('Карточка 3 — Текст')->rows(2)->maxLength(400),
                    ])->columnSpanFull(),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Акции (баннеры)')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('home_banners')
                        ->label('Слайды баннеров')
                        ->collection('home_banners')
                        ->image()->multiple()->reorderable()
                        ->openable()->downloadable()->preserveFilenames()
                        ->panelLayout('grid')
                        ->helperText('Загружайте в нужном порядке. Рекоменд. размер ~1440×420.'),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Логотипы брендов (текст)')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    \Filament\Forms\Components\Repeater::make('content.home.brands.items')
                        ->label('Элементы карусели брендов')
                        ->reorderable()
                        ->minItems(12)
                        ->schema([ TextInput::make('label')->label('Название (текст)')->maxLength(40) ])
                        ->columns(1)
                        ->helperText('Минимум 12 элементов, чтобы бегущая строка выглядела как в макете.'),
                ])->columnSpanFull()->collapsed(),

            Section::make('Главная: Отзывы')
                ->visible(fn ($get) => $get('template') === 'home')
                ->schema([
                    \Filament\Forms\Components\Repeater::make('content.home.reviews')
                        ->label('Отзывы клиентов (рендерятся на главной)')
                        ->reorderable()->minItems(1)
                        ->schema([
                            Hidden::make('uid')->default(fn () => (string) Str::ulid()),
                            Grid::make(3)->schema([
                                TextInput::make('author_name')->label('Автор')->required()->maxLength(120),
                                Toggle::make('is_active')->label('Показывать')->default(true),
                            ]),
                            Textarea::make('text')->label('Текст отзыва')->rows(4)->required()->maxLength(2000),
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->label('Аватар')
                                ->collection(fn ($get) => 'home_review_'.$get('uid'))
                                ->image()->maxFiles(1)->openable(),
                        ])->columns(1)
                        ->helperText('Порядок можно менять перетаскиванием. Неактивные скрываются.'),
                ])->columnSpanFull()->collapsed(),

            // ====== ABOUT (ИНПУТЫ ВМЕСТО РЕПИТЕРОВ) ======
            Section::make('О компании: История')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.about.history_title')
                            ->label('История: заголовок')
                            ->maxLength(120)->default('История компании'),
                        Textarea::make('content.about.history_text')
                            ->label('История: текст')
                            ->rows(5)->maxLength(5000),
                    ])->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('about_history')
                        ->label('История: изображение')
                        ->collection('about_history')->image()->maxFiles(1)->openable(),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Миссия и ценности (ровно 3 + 3)')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    TextInput::make('content.about.mission_title')
                        ->label('Миссия: заголовок')
                        ->maxLength(120)->default('Миссия и ценности'),

                    Textarea::make('content.about.mission_subtitle')
                        ->label('Миссия: подзаголовок (1 абзац)')
                        ->rows(3)->maxLength(1000),

                    // ===== Статистика (3 штуки, явные поля) =====
                    Grid::make(2)->schema([
                        TextInput::make('content.about.mission_stats.0.value')->label('Статистика 1 — Число')->placeholder('345+')->maxLength(20),
                        TextInput::make('content.about.mission_stats.0.label')->label('Статистика 1 — Подпись')->placeholder('Сертифицированных товаров')->maxLength(120),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('content.about.mission_stats.1.value')->label('Статистика 2 — Число')->placeholder('120+')->maxLength(20),
                        TextInput::make('content.about.mission_stats.1.label')->label('Статистика 2 — Подпись')->placeholder('Натуральных брендов')->maxLength(120),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('content.about.mission_stats.2.value')->label('Статистика 3 — Число')->placeholder('15')->maxLength(20),
                        TextInput::make('content.about.mission_stats.2.label')->label('Статистика 3 — Подпись')->placeholder('Стран поставщиков')->maxLength(120),
                    ]),

                    // ===== Карточки (3 штуки, явные поля) =====
                    Grid::make(1)->schema([
                        TextInput::make('content.about.mission_cards.0.title')->label('Карточка 1 — Заголовок')->maxLength(120),
                        Textarea::make('content.about.mission_cards.0.text')->label('Карточка 1 — Текст')->rows(3)->maxLength(500),
                    ]),
                    Grid::make(1)->schema([
                        TextInput::make('content.about.mission_cards.1.title')->label('Карточка 2 — Заголовок')->maxLength(120),
                        Textarea::make('content.about.mission_cards.1.text')->label('Карточка 2 — Текст')->rows(3)->maxLength(500),
                    ]),
                    Grid::make(1)->schema([
                        TextInput::make('content.about.mission_cards.2.title')->label('Карточка 3 — Заголовок')->maxLength(120),
                        Textarea::make('content.about.mission_cards.2.text')->label('Карточка 3 — Текст')->rows(3)->maxLength(500),
                    ]),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Преимущества сотрудничества (ровно 3, только текст)')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    TextInput::make('content.about.coop.title')
                        ->label('Заголовок секции')
                        ->default('Преимущества сотрудничества')->maxLength(180),

                    Grid::make(2)->schema([
                        TextInput::make('content.about.coop.items.0.title')->label('Преимущество 1 — Заголовок')->placeholder('Натуральность')->maxLength(120),
                        Textarea::make('content.about.coop.items.0.text')->label('Преимущество 1 — Текст')->rows(2)->maxLength(400),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('content.about.coop.items.1.title')->label('Преимущество 2 — Заголовок')->placeholder('Сертификация')->maxLength(120),
                        Textarea::make('content.about.coop.items.1.text')->label('Преимущество 2 — Текст')->rows(2)->maxLength(400),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('content.about.coop.items.2.title')->label('Преимущество 3 — Заголовок')->placeholder('Эффективность')->maxLength(120),
                        Textarea::make('content.about.coop.items.2.text')->label('Преимущество 3 — Текст')->rows(2)->maxLength(400),
                    ]),
                    Placeholder::make('coop_hint')->content('Иконки фиксированные из верстки. Здесь редактируется только текст.'),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Сертификаты качества (слайдер)')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('about_certificates')
                        ->label('Сертификаты (картинки)')
                        ->collection('about_certificates')
                        ->image()->multiple()->reorderable()
                        ->openable()->panelLayout('grid'),
                ])->columnSpanFull()->collapsed(),

            Section::make('О компании: Фото команды/производства (галерея)')
                ->visible(fn ($get) => $get('template') === 'about')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('about_album')
                        ->label('Фотографии (картинки)')
                        ->collection('about_album')
                        ->image()->multiple()->reorderable()
                        ->openable()->panelLayout('grid')
                        ->helperText('Загружайте в нужном порядке — выводится «как есть».'),
                ])->columnSpanFull()->collapsed(),

            // ====== PRIVACY ======
            Section::make('Политика конфиденциальности (privacy)')
                ->visible(fn ($get) => $get('template') === 'privacy')
                ->schema([
                    TextInput::make('content.privacy.title')->label('Заголовок H1')
                        ->default('Политика конфиденциальности')->maxLength(180),

                    RichEditor::make('content.privacy.body')->label('Текст политики')
                        ->toolbarButtons([
                            'bold','italic','underline','strike','h2','h3','blockquote','orderedList','bulletList','link','undo','redo','codeBlock',
                        ])->columnSpanFull()->required(),
                ])->columnSpanFull(),
        ]);
    }
}
