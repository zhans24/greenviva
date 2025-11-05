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
        $key = self::cacheKey($template);

        return Cache::rememberForever($key, function () use ($template) {
            $page = Page::byTemplate($template);

            if (!$page) {
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

            // базовая унификация
            return array_replace_recursive([
                'template' => $template,
                'exists'   => true,
                'title'    => $page->title,
                'meta'     => [
                    'title'       => $page->meta_title,
                    'description' => $page->meta_description,
                ],
            ], $payload);
        });
    }

    public static function forgetByTemplate(string $template): void
    {
        Cache::forget(self::cacheKey($template));
    }

    private static function cacheKey(string $template): string
    {
        return 'pagedata:template:' . $template;
    }


}
