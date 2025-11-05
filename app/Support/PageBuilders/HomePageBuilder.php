<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Models\Product;
use App\Support\PageBuilderInterface\PageBuilder;

final class HomePageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        // HERO
        $hero = [];
        foreach ((array) data_get($page->content, 'home.hero', []) as $slide) {
            $uid = $slide['uid'] ?? null;
            $hero[] = [
                'title'    => $slide['title'] ?? '',
                'text'     => $slide['text'] ?? '',
                'btn_text' => $slide['btn_text'] ?? '',
                'btn_url'  => $slide['btn_url'] ?? '/catalog',
                'left'     => $uid ? ($page->getFirstMediaUrl("home_hero_left_{$uid}", 'webp')
                    ?: $page->getFirstMediaUrl("home_hero_left_{$uid}")) : null,
                'center'   => $uid ? ($page->getFirstMediaUrl("home_hero_center_{$uid}", 'webp')
                    ?: $page->getFirstMediaUrl("home_hero_center_{$uid}")) : null,
                'right'    => $uid ? ($page->getFirstMediaUrl("home_hero_right_{$uid}", 'webp')
                    ?: $page->getFirstMediaUrl("home_hero_right_{$uid}")) : null,
            ];
        }

        // Преимущества
        $advantages = [
            'title' => data_get($page->content, 'home.advantages.title', 'Преимущества'),
            'items' => (array) data_get($page->content, 'home.advantages.items', []),
        ];

        // Популярные товары
        $popular = Product::query()
            ->where('is_popular', true)
            ->where('is_available', true)
            ->with('media')
            ->latest('updated_at')
            ->take(12)
            ->get();

        // Баннеры
        $banners = [];
        foreach ($page->getMedia('home_banners') as $m) {
            $banners[] = $m->getUrl('webp') ?: $m->getUrl();
        }

        // Логотипы (текст)
        $brands = (array) data_get($page->content, 'home.brands.items', []);

        // Отзывы (из content + динамические коллекции)
        $reviews = [];
        foreach ((array) data_get($page->content, 'home.reviews', []) as $item) {
            if (!($item['is_active'] ?? true)) {
                continue;
            }
            $uid = $item['uid'] ?? null;
            $reviews[] = [
                'author' => $item['author_name'] ?? 'Гость',
                'text'   => $item['text'] ?? '',
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
