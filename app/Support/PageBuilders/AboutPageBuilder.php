<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Support\PageBuilderInterface\PageBuilder;
use App\Support\Trans;

final class AboutPageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        // Медиа (общие)
        $historyImage = $page->getFirstMediaUrl('about_history', 'webp')
            ?: $page->getFirstMediaUrl('about_history');

        $certs = [];
        foreach ($page->getMedia('about_certificates') as $m) {
            $certs[] = $m->getUrl('webp') ?: $m->getUrl();
        }

        $album = [];
        foreach ($page->getMedia('about_album') as $m) {
            $album[] = $m->getUrl('webp') ?: $m->getUrl();
        }

        return [
            'about' => [
                // История (локализовано)
                'history_title' => Trans::pick($page->content, 'about.history_title') ?? 'История компании',
                'history_text'  => Trans::pick($page->content, 'about.history_text'),
                'history_image' => $historyImage,

                // Миссия и ценности (локализовано)
                'mission_title'    => Trans::pick($page->content, 'about.mission_title') ?? 'Миссия и ценности',
                'mission_subtitle' => Trans::pick($page->content, 'about.mission_subtitle'),
                'mission_stats'    => (array) (Trans::pick($page->content, 'about.mission_stats') ?? []), // ожидаем 3
                'mission_cards'    => (array) (Trans::pick($page->content, 'about.mission_cards') ?? []), // ожидаем 3

                // Преимущества сотрудничества (локализовано)
                'coop' => [
                    'title' => Trans::pick($page->content, 'about.coop.title') ?? 'Преимущества сотрудничества',
                    'items' => (array) (Trans::pick($page->content, 'about.coop.items') ?? []), // ожидаем 3
                ],

                // Медиа (общие)
                'certificates' => $certs,
                'album'        => $album,
            ],
        ];
    }
}
