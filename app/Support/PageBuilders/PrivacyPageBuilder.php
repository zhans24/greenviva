<?php

namespace App\Support\PageBuilders;

use App\Models\Page;
use App\Support\PageBuilderInterface\PageBuilder;
use App\Support\Trans;

final class PrivacyPageBuilder implements PageBuilder
{
    public function build(Page $page): array
    {
        return [
            'policy' => [
                'title'   => Trans::pick($page->content, 'privacy.title') ?? 'Политика конфиденциальности',
                'content' => Trans::pick($page->content, 'privacy.body'),
            ],
        ];
    }
}
