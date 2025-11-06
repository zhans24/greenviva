<?php

namespace App\Support;

use App\Models\Page;
use App\Support\PageBuilderInterface\PageBuilder;
use App\Support\PageBuilders\AboutPageBuilder;
use App\Support\PageBuilders\HomePageBuilder;
use App\Support\PageBuilders\PrivacyPageBuilder;
use Illuminate\Support\Facades\Cache;

final class PageData
{
    public static function getByTemplate(string $template): array
    {
        $locale = app()->getLocale();
        $key = self::cacheKey($template, $locale);

        // лог до обращения к кэшу
        $cached = Cache::get($key);


        return Cache::rememberForever($key, function () use ($template, $key, $locale) {

            $page = Page::byTemplate($template);



            if (! $page) {
                return ['template' => $template, 'exists' => false];
            }

            /** @var PageBuilder $builder */
            $builder = match ($template) {
                'home'    => new HomePageBuilder(),
                'about'   => new AboutPageBuilder(),
                'privacy' => new PrivacyPageBuilder(),
                default   => throw new \RuntimeException("No builder for template [$template]"),
            };

            $payload = $builder->build($page);

            $result = array_replace_recursive([
                'template' => $template,
                'exists'   => true,
                'title'    => Trans::model($page, 'title'),
                'meta'     => [
                    'title'       => Trans::model($page, 'meta_title'),
                    'description' => Trans::model($page, 'meta_description'),
                ],
            ], $payload);



            return $result;
        });
    }

    public static function forgetByTemplate(string $template): void
    {
        $locales = config('app.available_locales', ['ru','kz','en']);
        foreach ($locales as $loc) {
            $key = self::cacheKey($template, $loc);
            Cache::forget($key);

        }
    }

    private static function cacheKey(string $template, ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return "pagedata:{$locale}:template:{$template}";
    }
}
