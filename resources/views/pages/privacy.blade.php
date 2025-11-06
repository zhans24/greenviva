{{-- resources/views/pages/privacy.blade.php --}}
@extends('layouts.app')

@section('title', $data['meta']['title'] ?? __('app.privacy.title'))
@push('meta')
    @if(!empty($data['meta']['description']))
        <meta name="description" content="{{ $data['meta']['description'] }}">
    @endif
@endpush

@section('main_classes', 'page-main page-main-privacy')

@section('content')
    @php
        $policy = (array) ($data['policy'] ?? []);
        $title  = $policy['title']   ?? __('app.privacy.title');
        $html   = $policy['content'] ?? null; // уже размеченный HTML из редактора
        $loc    = app()->getLocale();
        $pref   = $loc === 'ru' ? '' : '/'.$loc;
    @endphp

    <div class="container">
        <nav class="breadcrumbs" aria-label="{{ __('app.breadcrumbs.aria') }}">
            <ul>
                <li>
                    <a class="breadcrumbs__home" href="{{ url($pref . '/') }}">
                        {{ __('app.breadcrumbs.home') }}
                    </a>
                </li>
                <li class="breadcrumbs__link" aria-current="page">/ {{ $title }}</li>
            </ul>
        </nav>
    </div>

    <section class="privacy">
        <div class="container">
            <h2 class="privacy__title">{{ $title }}</h2>

            @if(!empty($html))
                <div class="privacy__content">
                    {!! $html !!}
                </div>
            @else
                <ul class="privacy__list">
                    <li class="privacy__item">{{ __('app.privacy.empty') }}</li>
                </ul>
            @endif
        </div>
    </section>
@endsection
