@extends('layouts.app')

@section('title', __('app.catalog.title'))
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">{{ __('app.breadcrumbs.home') }}</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ {{ __('app.breadcrumbs.catalog') }}</li>
            </ul>
        </nav>
    </div>

    <section class="catalog">
        <div class="container">
            <div class="catalog__inner">
                <h2 class="title catalog__title">{{ __('app.catalog.title') }}</h2>

                <ul class="catalog__list">
                    @forelse($categories as $c)
                        <li class="catalog__item">
                            @php $loc = app()->getLocale(); @endphp
                            <a href="{{ $loc === 'ru'
                                ? route('catalog.category', $c->slug)
                                : route('catalog.category.localized', ['locale' => $loc, 'slug' => $c->slug]) }}"
                               class="catalog__link">
                                <div class="catalog__img">
                                    <img src="{{ $c->tile_url ?? $c->cover_url ?? asset('assets/img/catalog/1.png') }}"
                                         alt="{{ $c->name }}">
                                </div>
                                <h3 class="catalog__name">{{ $c->name }}</h3>
                            </a>
                        </li>
                    @empty
                        <li class="catalog__item">
                            <div class="catalog__link" aria-disabled="true">
                                <div class="catalog__img">
                                    <img src="{{ asset('assets/img/catalog/1.png') }}" alt="">
                                </div>
                                <h3 class="catalog__name">{{ __('app.catalog.empty') }}</h3>
                            </div>
                        </li>
                    @endforelse
                </ul>

            </div>
        </div>
    </section>
@endsection
