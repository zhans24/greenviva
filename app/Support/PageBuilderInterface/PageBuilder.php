<?php

namespace App\Support\PageBuilderInterface;

use App\Models\Page;

interface PageBuilder
{
    /**
     * Собирает данные страницы в удобную структуру для вьюхи.
     */
    public function build(Page $page): array;
}
