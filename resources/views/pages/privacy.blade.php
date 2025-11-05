{{-- resources/views/pages/privacy.blade.php --}}
@extends('layouts.app')

@section('title', $data['meta']['title'] ?? 'Политика конфиденциальности')
@push('meta')
    @if(!empty($data['meta']['description']))
        <meta name="description" content="{{ $data['meta']['description'] }}">
    @endif
@endpush

@section('main_classes', 'page-main page-main-about')

@section('content')
    @php
        $policy = $data['policy'] ?? [];
        $title  = $policy['title'] ?? 'Политика конфиденциальности';
        $html   = $policy['content'] ?? null; // уже размеченный HTML из редактора
    @endphp

    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
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
                    <li class="privacy__item">Содержимое политики ещё не добавлено.</li>
                </ul>
            @endif
        </div>
    </section>
@endsection
