<?php

namespace App\Models;

use App\Support\PageData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;

    public array $translatable = [
        'title',
        'meta_title',
        'meta_description',
        'content', // локализованный JSON
    ];

    protected $fillable = [
        'title','slug','template','is_published',
        'meta_title','meta_description','content','published_at',
    ];

    protected $casts = [
        'is_published' => 'bool',
        'published_at' => 'datetime',
    ];

    public function section(string $key, $default = null) {
        return data_get($this->content, $key, $default);
    }

    public function scopePublished($q) {
        return $q->where('is_published', true);
    }

    public static function byTemplate(string $template): ?self {
        return Cache::remember("page:template:$template", 86400, fn () =>
        static::query()->published()->where('template', $template)->first()
        );
    }

    public function registerMediaCollections(): void
    {
        // ABOUT
        $this->addMediaCollection('about_history')->singleFile();
        $this->addMediaCollection('about_album');
        $this->addMediaCollection('about_certificates');

        // HOME
        $this->addMediaCollection('home_banners');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media && str_starts_with((string) $media->mime_type, 'image/')) {
            $this->addMediaConversion('webp')
                ->format('webp')
                ->quality(85)
                ->nonQueued();
        }
    }

    protected static function booted(): void {
        $forget = function (self $p) {
            Cache::forget('page:template:' . (string) $p->template);
            PageData::forgetByTemplate((string) $p->template);
        };
        static::saved($forget);
        static::deleted($forget);
    }
}
