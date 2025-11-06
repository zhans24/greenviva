<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Models\Product;
use App\Support\PageBuilderInterface\PageBuilder;
use App\Support\Trans;

final class HomePageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        /**
         * Контент страницы:
         * - локализованные ветки берём через Trans::pick(...)
         * - где нужно — делаем фолбэк на «глобальный» путь (для совместимости со старыми данными)
         */
        $content = method_exists($page, 'getTranslations')
            ? $page->getTranslations('content')   // карта всех локалей
            : (array) $page->content;

        // HERO (локализованные тексты, медиа общие по коллекциям)
        $hero = [];
        $heroSlides = (array) (Trans::pick($content, 'home.hero') ?? []);
        foreach ($heroSlides as $slide) {
            $uid = $slide['uid'] ?? null;
            $hero[] = [
                'title'    => $slide['title']    ?? '',
                'text'     => $slide['text']     ?? '',
                'btn_text' => $slide['btn_text'] ?? '',
                'btn_url'  => $slide['btn_url']  ?? '/catalog',
                'left'     => $uid ? ($page->getFirstMediaUrl("home_hero_left_{$uid}", 'webp')
                    ?:  $page->getFirstMediaUrl("home_hero_left_{$uid}"))   : null,
                'center'   => $uid ? ($page->getFirstMediaUrl("home_hero_center_{$uid}", 'webp')
                    ?:  $page->getFirstMediaUrl("home_hero_center_{$uid}")) : null,
                'right'    => $uid ? ($page->getFirstMediaUrl("home_hero_right_{$uid}", 'webp')
                    ?:  $page->getFirstMediaUrl("home_hero_right_{$uid}"))  : null,
            ];
        }

        // Преимущества (локализованные тексты)
        $advantages = [
            'title' => Trans::pick($content, 'home.advantages.title') ?? 'Преимущества',
            'items' => (array) (Trans::pick($content, 'home.advantages.items') ?? []),
        ];

        // Популярные товары (динамика)
        $popular = Product::query()
            ->where('is_popular', true)
            ->where('is_available', true)
            ->with('media')
            ->latest('updated_at')
            ->take(12)
            ->get();

        // Баннеры (медиа общие для всех языков)
        $banners = [];
        foreach ($page->getMedia('home_banners') as $m) {
            $banners[] = $m->getUrl('webp') ?: $m->getUrl();
        }

        // Логотипы (локализованный текст)
        $brands = (array) (Trans::pick($content, 'home.brands.items') ?? []);

        /**
         * Отзывы:
         * - Сначала пытаемся взять локализованный список: ru.home.reviews / kz.home.reviews / en.home.reviews
         * - Если пусто — берём ГЛОБАЛЬНЫЙ путь home.reviews (как у тебя сейчас в БД)
         * - Поле text — это JSON вида ['ru'=>..., 'kz'=>..., 'en'=>...], поэтому используем Trans::field(...)
         * - Аватар — из общей медиа-коллекции по uid
         */
        $reviews = [];
        $reviewsRaw = (array) (
            Trans::pick($content, 'home.reviews')
            ?? data_get($content, 'home.reviews', [])
        );

        foreach ($reviewsRaw as $item) {
            if (!($item['is_active'] ?? true)) {
                continue;
            }
            $uid = $item['uid'] ?? null;
            $reviews[] = [
                'author' => $item['author_name'] ?? 'Гость',
                'text'   => Trans::field($item['text'] ?? null), // ru/kz/en с фолбэком на ru
                'avatar' => $uid
                    ? ($page->getFirstMediaUrl("home_review_{$uid}", 'webp')
                        ?: $page->getFirstMediaUrl("home_review_{$uid}"))
                    : null,
            ];
        }

        return [
            'home' => [
                'hero'       => $hero,
                'advantages' => $advantages,
                'popular'    => $popular,
                'banners'    => $banners,
                'brands'     => $brands,
                'reviews'    => $reviews,
            ],
        ];
    }
}
