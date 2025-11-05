{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('title', '404 — Страница не найдена')
@push('meta')
    <meta name="description" content="Страница не найдена">
@endpush

{{-- важно для отступа под фикс-шапку --}}
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ 404</li>
            </ul>
        </nav>
    </div>

    <section class="not-found">
        <div>
            <img src="{{ asset('assets/img/404.png') }}" alt="Страница не найдена">
        </div>
    </section>
@endsection
