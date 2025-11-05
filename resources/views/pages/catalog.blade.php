@extends('layouts.app')

@section('title', 'Каталог')
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ Каталог продукции</li>
            </ul>
        </nav>
    </div>

    <section class="catalog">
        <div class="container">
            <div class="catalog__inner">
                <h2 class="title catalog__title">Каталог продукции</h2>

                <ul class="catalog__list">
                    @forelse($categories as $c)
                        <li class="catalog__item">
                            <a href="{{ route('catalog.category', $c->slug) }}" class="catalog__link">
                                <div class="catalog__img">
                                    <img
                                        src="{{ $c->tile_url ?? $c->cover_url ?? asset('assets/img/catalog/1.png') }}"
                                        alt="{{ $c->name }}">
                                </div>
                                <h3 class="catalog__name">{{ $c->name }}</h3>
                            </a>
                        </li>
                    @empty
                        {{-- Если категорий нет — можно показать плейсхолдер --}}
                        <li class="catalog__item">
                            <div class="catalog__link" aria-disabled="true">
                                <div class="catalog__img">
                                    <img src="{{ asset('assets/img/catalog/1.png') }}" alt=" ">
                                </div>
                                <h3 class="catalog__name">Категории скоро появятся</h3>
                            </div>
                        </li>
                    @endforelse
                </ul>

            </div>
        </div>
    </section>
@endsection
