<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public array $translatable = ['name'];

    // Отношения:
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function nameCurrent(): ?string
    {
        return $this->getTranslation('name', app()->getLocale(), false)
            ?: $this->getTranslation('name', 'ru', false);
    }
}
