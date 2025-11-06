<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;

    public array $translatable = [
        'name',
        'seo_title',
        'seo_h1',
        'seo_description',
    ];

    protected $fillable = [
        'name','slug','sort','is_active',
        'seo_title','seo_h1','seo_description',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tile')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->nonQueued()
            ->performOnCollections('tile');
    }

    public function getTileUrlAttribute(): ?string
    {
        $m = $this->getFirstMedia('tile');
        return $m ? $m->getUrl('webp') : null;
    }

    public function nameCurrent(): ?string
    {
        return $this->getTranslation('name', app()->getLocale(), false)
            ?: $this->getTranslation('name', 'ru', false);
    }
    public function getSeoTitleAttribute($v) { return $v ?: $this->name; }
    public function getSeoH1Attribute($v)    { return $v ?: $this->name; }
    public function getSeoDescriptionAttribute($v)
    {
        if ($v) return $v;
        $text = $this->description ? strip_tags($this->description) : '';
        return $text ? Str::limit($text, 160) : null;
    }
}
