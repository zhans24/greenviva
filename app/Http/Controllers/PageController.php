<?php

namespace App\Http\Controllers;

use App\Support\PageData;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $template = 'home'): View
    {
        $data = PageData::getByTemplate($template);


        $view = match ($template) {
            'home'    => 'pages.index',
            'about'   => 'pages.about',
            'privacy' => 'pages.privacy',
            default   => abort(404),
        };

        return view($view, compact('data'));
    }
}
