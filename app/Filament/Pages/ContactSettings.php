<?php

namespace App\Filament\Pages;

use App\Models\ContactSetting;
use App\Services\ContactNormalizer;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Validator;

class ContactSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationLabel = 'Контакты';
    protected static ?string $title = 'Контакты сайта';
    protected static ?string $slug = 'contact-settings';
    protected static string|null|\UnitEnum $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.contact-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $s = ContactSetting::singleton();

        $ct = is_array($s->company_text)
            ? $s->company_text
            : (filled($s->company_text) ? ['ru' => $s->company_text] : []);

        $this->data = [
            'company_name' => $s->company_name,
            'company_text' => [
                'ru' => $ct['ru'] ?? '',
                'kz' => $ct['kz'] ?? '',
                'en' => $ct['en'] ?? '',
            ],
            'phone'        => $s->phone,
            'email'        => $s->email_link,
            'whatsapp'     => $s->whatsapp_link,
            'youtube'      => $s->youtube_link,
            'telegram'     => $s->telegram_link,
            'address'      => $s->address,
            'map_embed'    => $s->map_embed,
        ];
    }
    public function schema(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Компания')
                    ->schema([
                        TextInput::make('company_name')->label('Название компании')->maxLength(255),
                        Tabs::make('company_text_tabs')
                            ->tabs([
                                Tabs\Tab::make('RU')->schema([
                                    Textarea::make('company_text.ru')
                                        ->label('Описание (RU)')
                                        ->rows(3)->maxLength(2000),
                                ]),
                                Tabs\Tab::make('KZ')->schema([
                                    Textarea::make('company_text.kz')
                                        ->label('Сипаттама (KZ)')
                                        ->rows(3)->maxLength(2000),
                                ]),
                                Tabs\Tab::make('EN')->schema([
                                    Textarea::make('company_text.en')
                                        ->label('Description (EN)')
                                        ->rows(3)->maxLength(2000),
                                ]),
                            ])->columnSpan(6),
                    ])
                    ->columns(1)->columnSpan(6),

                Section::make('Контакты')
                    ->schema([
                        TextInput::make('phone')->label('Телефон')->placeholder('+7 747 123 45 67')->maxLength(50),
                        TextInput::make('email')->label('Email')->placeholder('greenviva.kz@gmail.com или mailto:...')->maxLength(255),
                    ])
                    ->columns(1)->columnSpan(6),

                Section::make('Адрес и карта')
                    ->schema([
                        Textarea::make('address')->label('Адрес')->rows(2)->maxLength(2000),
                        Textarea::make('map_embed')->label('Карта (iframe код)')->rows(6)->maxLength(10000),
                    ])->columns(1)->columnSpan(6),

                Section::make('Соцсети')
                    ->schema([
                        TextInput::make('whatsapp')->label('WhatsApp')->placeholder('+7 747 123 45 67 или https://wa.me/77471234567')->maxLength(255),
                        TextInput::make('youtube')->label('YouTube')->placeholder('@greenviva или https://youtube.com/@greenviva')->maxLength(255),
                        TextInput::make('telegram')->label('Telegram')->placeholder('@greenviva или https://t.me/greenviva')->maxLength(255),
                    ])->columns(1)->columnSpan(6),

                Action::make('save')
                    ->label('Сохранить')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->action(function () {
                        try {
                            // Собираем JSON по языкам, выбрасывая пустые
                            $text = [
                                'ru' => trim((string) data_get($this->data, 'company_text.ru')) ?: null,
                                'kz' => trim((string) data_get($this->data, 'company_text.kz')) ?: null,
                                'en' => trim((string) data_get($this->data, 'company_text.en')) ?: null,
                            ];
                            $text = array_filter($text, fn ($v) => filled($v));

                            $payload = [
                                'company_name'   => trim((string) ($this->data['company_name'] ?? '')) ?: null,
                                'company_text'   => $text ?: null,
                                'phone'          => ContactNormalizer::normalizeTel($this->data['phone'] ?? null),
                                'email_link'     => ContactNormalizer::normalizeMailto($this->data['email'] ?? null),
                                'whatsapp_link'  => ContactNormalizer::normalizeWhatsapp($this->data['whatsapp'] ?? null),
                                'youtube_link'   => ContactNormalizer::normalizeYouTube($this->data['youtube'] ?? null),
                                'telegram_link'  => ContactNormalizer::normalizeTelegram($this->data['telegram'] ?? null),
                                'address'        => ($this->data['address'] ?? null) ?: null,
                                'map_embed'      => trim((string) ($this->data['map_embed'] ?? '')) ?: null,
                            ];

                            $rules = [
                                'company_name'       => ['nullable','string','max:255'],
                                'company_text'       => ['nullable','array'],
                                'company_text.ru'    => ['nullable','string','max:2000'],
                                'company_text.kz'    => ['nullable','string','max:2000'],
                                'company_text.en'    => ['nullable','string','max:2000'],

                                'phone'              => ['nullable','regex:~^tel:\+\d{6,}$~'],
                                'email_link'         => ['nullable','regex:~^mailto:[^@\s]+@[^@\s]+\.[^@\s]+$~i'],
                                'whatsapp_link'      => ['nullable','url','regex:~^https?://(wa\.me|api\.whatsapp\.com)/~i'],
                                'youtube_link'       => ['nullable','url'],
                                'telegram_link'      => ['nullable','url'],
                                'address'            => ['nullable','string','max:2000'],
                                'map_embed'          => ['nullable','string','max:10000'],
                            ];

                            Validator::make($payload, $rules, [
                                'phone.regex'         => 'Телефон должен быть ссылкой вида tel:+7747...',
                                'email_link.regex'    => 'Email должен быть ссылкой вида mailto:example@domain.kz',
                                'whatsapp_link.regex' => 'WhatsApp должен быть ссылкой wa.me или api.whatsapp.com',
                            ])->validate();

                            $model = ContactSetting::singleton();
                            $model->fill($payload)->save();

                            cache()->forget(ContactSetting::cacheKey());

                            Notification::make()->title('Сохранено')->success()->send();
                        } catch (\Throwable $e) {
                            Notification::make()->title('Ошибка сохранения')->body($e->getMessage())->danger()->send();
                            throw $e;
                        }
                    }),
            ])
            ->columns(12);
    }
}
