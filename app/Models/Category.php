<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name','slug','sort','is_active',
        'seo_title','seo_h1','seo_description',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug(Str::limit($m->name, 60, ''));
            }
        });
    }

    public function products() { return $this->hasMany(Product::class); }

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

    public function getSeoTitleAttribute($v) { return $v ?: $this->name; }
    public function getSeoH1Attribute($v)    { return $v ?: $this->name; }
    public function getSeoDescriptionAttribute($v)
    {
        if ($v) return $v;
        $text = $this->products()->value('description') ?? '';
        return $text ? Str::limit(strip_tags($text), 160) : null;
    }

    public function getTileUrlAttribute(): ?string
    {
        $m = $this->getFirstMedia('tile');
        return $m ? $m->getUrl('webp') : null;
    }
}
