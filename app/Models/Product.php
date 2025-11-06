<?php

namespace App\Models;

use App\Support\PageData;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;

    public array $translatable = [
        'name',
        'description',
        'composition',
        'usage',
        'delivery_info',
        'seo_title',
        'seo_h1',
        'seo_description',
    ];

    protected $fillable = [
        'category_id','brand_id','sku','name','slug',
        'price','old_price','is_available','is_best_seller','is_popular',
        'description','composition','usage','delivery_info',
        'seo_title','seo_h1','seo_description',
    ];

    protected $casts = [
        'is_available'   => 'bool',
        'is_popular'     => 'bool',
        'is_best_seller' => 'bool',
        'price'          => 'int',
        'old_price'      => 'int',
    ];

    // slug здесь не трогаем — только формы.
    protected static function booted(): void
    {
        static::saved(function () {
            PageData::forgetByTemplate('home');
        });
        static::deleted(function () {
            PageData::forgetByTemplate('home');
        });
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function brand()    { return $this->belongsTo(Brand::class); }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('certificates');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->nonQueued()
            ->performOnCollections('cover', 'gallery', 'certificates');
    }

    // SEO фолбэки (локализованные)
    public function getSeoTitleAttribute($v) { return $v ?: $this->name; }
    public function getSeoH1Attribute($v)    { return $v ?: $this->name; }
    public function getSeoDescriptionAttribute($v)
    {
        if ($v) return $v;
        $text = $this->description ? strip_tags($this->description) : '';
        return $text ? Str::limit($text, 160) : null;
    }

    // helpers
    public function getCoverUrlAttribute(): ?string
    {
        $m = $this->getFirstMedia('cover');
        return $m ? $m->getUrl('webp') : null;
    }
    public function getPriceFormattedAttribute(): string
    {
        return number_format((int) $this->price, 0, '.', ' ');
    }
    public function getOldPriceFormattedAttribute(): ?string
    {
        return $this->old_price ? number_format((int) $this->old_price, 0, '.', ' ') : null;
    }

    public function scopePopular($q) { return $q->where('is_popular', true); }

    public function nameCurrent(): ?string
    {
        return $this->getTranslation('name', app()->getLocale(), false)
            ?: $this->getTranslation('name', 'ru', false);
    }
}
