<?php

namespace App\Http\Controllers;

use App\Support\PageData;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function show(Request $request): View
    {
        // достаём template, который задан в routes ->defaults('template', '...')
        $template = (string) $request->route('template', 'home');

        // на всякий случай: если по ошибке прилетела локаль — принудительно в home
        if (in_array($template, ['ru','kz','en'], true)) {
            $template = 'home';
        }

        Log::debug('PageController@show: resolved template', [
            'uri'      => $request->getRequestUri(),
            'locale'   => app()->getLocale(),
            'template' => $template,
        ]);

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
