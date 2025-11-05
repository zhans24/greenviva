<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Support\PageBuilderInterface\PageBuilder;

final class PrivacyPageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        return [
            'policy' => [
                'title'   => data_get($page->content, 'privacy.title', 'Политика конфиденциальности'),
                'content' => data_get($page->content, 'privacy.body'), // HTML из редактора
            ],
        ];
    }
}
