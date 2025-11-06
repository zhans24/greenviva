<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Trans
{
    /**
     * Для плоских переводимых полей, которые лежат JSON'ом:
     * ['ru' => '...', 'kz' => '...', 'en' => '...']
     */
    public static function field(null|array|string $value, ?string $locale = null, string $fallback = 'ru')
    {
        if (is_string($value)) {
            // На всякий — вдруг в БД ещё лежит строка без переводов.
            return $value;
        }

        if (! is_array($value)) {
            return null;
        }

        $locale = $locale ?? app()->getLocale();
        $picked = $value[$locale] ?? null;

        // если пусто — берём RU
        if (self::isEmpty($picked)) {
            $picked = $value[$fallback] ?? null;
        }

        return $picked;
    }

    /**
     * Для вложенных структур страницы вида:
     * content = { ru: { home: { hero: [...] } }, kz: {...}, en: {...} }
     *
     * Пример: pick($page->content, 'home.hero')
     */
    public static function pick(?array $content, string $path, ?string $locale = null, string $fallback = 'ru')
    {
        if (! is_array($content)) return null;

        $locale = $locale ?? app()->getLocale();

        // если это УЖЕ локализованный срез (нет ключей локалей в корне)
        $hasLocaleKeys = array_intersect(['ru','kz','en'], array_keys($content));
        if (empty($hasLocaleKeys)) {
            // просто берём по прямому пути: home.hero и т.п.
            $val = data_get($content, $path);
            return self::isEmpty($val) ? null : $val;
        }

        // классический случай: словарь локалей
        $val = data_get($content, "{$locale}.{$path}");
        if (self::isEmpty($val)) {
            $val = data_get($content, "{$fallback}.{$path}");
        }
        return $val;
    }

    /**
     * Для моделей со spatie/laravel-translatable:
     * безопасно берёт модельное поле с фолбэком на RU.
     */
    public static function model(Model $model, string $attr, ?string $locale = null, string $fallback = 'ru')
    {
        // если у модели есть метод getTranslation (Spatie HasTranslations)
        if (method_exists($model, 'getTranslation')) {
            $locale = $locale ?? app()->getLocale();

            $val = $model->getTranslation($attr, $locale, false); // без встроенного фолбэка
            if (self::isEmpty($val)) {
                $val = $model->getTranslation($attr, $fallback, false);
            }

            return $val;
        }

        // иначе пробуем как обычный JSON (['ru'=>..])
        return self::field($model->{$attr} ?? null, $locale, $fallback);
    }

    private static function isEmpty(mixed $v): bool
    {
        if (is_array($v)) return count($v) === 0;
        return $v === null || $v === '';
    }
}
