<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Services\ContactNormalizer;

class ContactSetting extends Model
{
    protected $table = 'contact_settings';

    protected $fillable = [
        'id',
        'company_name',
        'company_text',
        'phone',
        'email_link',
        'whatsapp_link',
        'youtube_link',
        'telegram_link',
        'address',
        'map_embed',
    ];

    protected $casts = [
        // без массивов, один телефон как строка
    ];

    public static function cacheKey(): string
    {
        return 'site.contacts';
    }

    public static function singleton(): self
    {
        return static::firstOrCreate(['id' => 1], ['id' => 1]);
    }

    public static function getCached(): self
    {
        return Cache::rememberForever(self::cacheKey(), fn () => self::singleton()->fresh());
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = ContactNormalizer::normalizeTel($value);
    }

    public function setEmailLinkAttribute($value): void
    {
        $this->attributes['email_link'] = ContactNormalizer::normalizeMailto($value);
    }

    public function setWhatsappLinkAttribute($value): void
    {
        $this->attributes['whatsapp_link'] = ContactNormalizer::normalizeWhatsapp($value);
    }

    public function setYoutubeLinkAttribute($value): void
    {
        $this->attributes['youtube_link'] = ContactNormalizer::normalizeYouTube($value);
    }

    public function setTelegramLinkAttribute($value): void
    {
        $this->attributes['telegram_link'] = ContactNormalizer::normalizeTelegram($value);
    }

    public function setMapEmbedAttribute($value): void
    {
        $value = is_string($value) ? trim($value) : null;
        $this->attributes['map_embed'] = $value ?: null;
    }
}
