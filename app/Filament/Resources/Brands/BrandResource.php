<?php

// app/Filament/Resources/Brands/BrandResource.php
namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|null|\UnitEnum $navigationGroup = 'Каталог';
    protected static ?int $navigationSort = 0;
    protected static ?string $modelLabel = 'Бренд';
    protected static ?string $pluralModelLabel = 'Бренды';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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

            Toggle::make('is_active')->label('Активен')->default(true),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->searchable()->sortable(),
                TextColumn::make('slug')->label('Слаг')->searchable(),
                ToggleColumn::make('is_active')->label('Активен'),
                TextColumn::make('updated_at')->label('Обновлено')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit'   => EditBrand::route('/{record}/edit'),
        ];
    }
}
