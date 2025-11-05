<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Support\PageBuilderInterface\PageBuilder;

final class AboutPageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        // История
        $historyImage = $page->getFirstMediaUrl('about_history', 'webp')
            ?: $page->getFirstMediaUrl('about_history');

        // Сертификаты
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
                // История
                'history_title' => data_get($page->content, 'about.history_title', 'История компании'),
                'history_text'  => data_get($page->content, 'about.history_text'),
                'history_image' => $historyImage,

                // Миссия и ценности
                'mission_title'    => data_get($page->content, 'about.mission_title', 'Миссия и ценности'),
                'mission_subtitle' => data_get($page->content, 'about.mission_subtitle'),
                'mission_stats'    => (array) data_get($page->content, 'about.mission_stats', []),   // ровно 3
                'mission_cards'    => (array) data_get($page->content, 'about.mission_cards', []),   // ровно 3

                // Преимущества сотрудничества
                'coop' => [
                    'title' => data_get($page->content, 'about.coop.title', 'Преимущества сотрудничества'),
                    'items' => (array) data_get($page->content, 'about.coop.items', []), // ровно 3
                ],

                // Медиа
                'certificates' => $certs,
                'album'        => $album,
            ],
        ];
    }
}
