{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('title', $data['meta']['title'] ?? 'О компании')
@section('main_classes', 'page-main page-main-about')

@push('meta')
    @if(!empty($data['meta']['description']))
        <meta name="description" content="{{ $data['meta']['description'] }}">
    @endif
@endpush

@section('content')
    @php
        $about = (array) ($data['about'] ?? []);

        // История
        $historyImage = $about['history_image'] ?? asset('assets/img/about/1.png');
        $historyTitle = $about['history_title'] ?? 'История компании';
        $historyText  = $about['history_text']  ?? 'Текст о компании ещё не добавлен.';

        // Миссия и ценности
        $missionTitle    = $about['mission_title']    ?? 'Миссия и ценности';
        $missionSubtitle = $about['mission_subtitle'] ?? null;

        // Статистика — показываем только непустые
        $statsRaw = (array) ($about['mission_stats'] ?? []);
        $stats = collect($statsRaw)
            ->map(function ($s) {
                $s = (array) $s;
                $s['value'] = trim((string)($s['value'] ?? ''));
                $s['label'] = trim((string)($s['label'] ?? ''));
                return $s;
            })
            ->filter(fn($s) => $s['value'] !== '' || $s['label'] !== '')
            ->values()
            ->all();

        // Карточки миссии — только непустые
        $cardsRaw = (array) ($about['mission_cards'] ?? []);
        $cards = collect($cardsRaw)
            ->map(function ($c) {
                $c = (array) $c;
                $c['title'] = trim((string)($c['title'] ?? ''));
                $c['text']  = trim((string)($c['text']  ?? ''));
                return $c;
            })
            ->filter(fn($c) => $c['title'] !== '' || $c['text'] !== '')
            ->values()
            ->all();

        // Преимущества сотрудничества — только непустые
        $coop = (array) ($about['coop'] ?? []);
        $coopTitle = $coop['title'] ?? 'Преимущества сотрудничества';
        $coopItemsRaw = (array) ($coop['items'] ?? []);
        $coopItems = collect($coopItemsRaw)
            ->map(function ($i) {
                $i = (array) $i;
                $i['title'] = trim((string)($i['title'] ?? ''));
                $i['text']  = trim((string)($i['text']  ?? ''));
                return $i;
            })
            ->filter(fn($i) => $i['title'] !== '' || $i['text'] !== '')
            ->values()
            ->all();

        // Иконки для преимуществ (по порядку)
        $icons = [
            asset('assets/img/list.png'),
            asset('assets/img/certificate.png'),
            asset('assets/img/rang.png'),
        ];

        // Сертификаты + Альбом (как было)
        $certificates = (array) ($about['certificates'] ?? []);
        $album = (array) ($about['album'] ?? []);
    @endphp

    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ О компании</li>
            </ul>
        </nav>
    </div>

    {{-- История --}}
    <section class="about">
        <div class="container">
            <div class="about__inner">
                <div class="about__img">
                    <img src="{{ $historyImage }}" alt="{{ $historyTitle }}">
                </div>
                <div class="about__info">
                    <h2 class="about__title title">{{ $historyTitle }}</h2>
                    <p class="about__text">{{ $historyText }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Миссия и ценности --}}
    <section class="about-mission">
        <div class="container">
            <div class="about-mission__inner">
                <div class="about-mission__left">
                    <h2 class="about-mission__title title">{{ $missionTitle }}</h2>

                    @if($missionSubtitle)
                        <p class="about-mission__text">{{ $missionSubtitle }}</p>
                    @endif

                    @if(!empty($stats))
                        <ul class="about-mission__list">
                            @foreach($stats as $s)
                                <li class="about-mission__item">
                                    @if($s['value'] !== '')
                                        <h3 class="about-mission__item-title">{{ $s['value'] }}</h3>
                                    @endif
                                    @if($s['label'] !== '')
                                        <p class="about-mission__item-text">{{ $s['label'] }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="about-mission__right">
                    @if(!empty($cards))
                        <ul class="about-mission__cards">
                            @foreach($cards as $card)
                                <li class="about-mission__card">
                                    <div class="about-mission__round"></div>
                                    <div class="about-mission__card-info">
                                        @if($card['title'] !== '')
                                            <h3 class="about-mission__card-title">{{ $card['title'] }}</h3>
                                        @endif
                                        @if($card['text'] !== '')
                                            <p class="about-mission__card-text">{{ $card['text'] }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Сертификаты качества (слайдер) --}}
    <section class="popular">
        <div class="popular__inner">
            <div class="container">
                <div class="popular__top">
                    <h2 class="title popular__title">Сертификаты качества</h2>
                    <div class="popular__navigtion">
                        <button class="popular__btn popular__btn-prev">
                            <img src="{{ asset('assets/icons/arrow-left.svg') }}" alt="arrow-left">
                        </button>
                        <button class="popular__btn popular__btn-next">
                            <img src="{{ asset('assets/icons/arrow-rigth.svg') }}" alt="arrow-right">
                        </button>
                    </div>
                </div>
            </div>

            <div class="popular__slider swiper">
                <div class="swiper-wrapper">
                    @forelse($certificates as $img)
                        <div class="swiper-slide"><img src="{{ $img }}" alt="certificate"></div>
                    @empty
                        <div class="swiper-slide"><img src="{{ asset('assets/img/about/cer1.png') }}" alt=""></div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/about/cer2.png') }}" alt=""></div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/about/cer3.png') }}" alt=""></div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Преимущества сотрудничества (покажем только заполненные, максимум 3) --}}
    @php
        $coopCount = min(count($coopItems), 3);
    @endphp
    @if($coopCount > 0)
        <section class="advantages">
            <div class="container">
                <div class="advantages__inner">
                    <h2 class="title advantages__title">{{ $coopTitle }}</h2>
                    <div class="advantages-bg-decor">
                        <ul class="advantages__list">
                            @for($i = 0; $i < $coopCount; $i++)
                                <li class="advantages__item">
                                    <div class="advantages__top">
                                        <img src="{{ $icons[$i] ?? $icons[0] }}" alt="">
                                    </div>
                                    <div class="advantages__content">
                                        @if(($coopItems[$i]['title'] ?? '') !== '')
                                            <h2 class="advantages__item-title">{{ $coopItems[$i]['title'] }}</h2>
                                        @endif
                                        @if(($coopItems[$i]['text'] ?? '') !== '')
                                            <p class="advantages__item-text">{{ $coopItems[$i]['text'] }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endfor
                        </ul>
                        <div class="advantages-bg-decor-img">
                            <img src="{{ asset('assets/img/bg-card-line-light.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Фото команды/производства --}}
    <section class="about-album">
        <div class="container">
            <h2 class="about-album__title title">Фото команды/производства</h2>

            @php
                // Паттерн “больших” изображений: 1-я и 6-я — большие, повторяется каждые 6.
                // true = big, false = обычная карточка.
                $bigPattern = [true, false, false, false, false, true];
                $patternLen = count($bigPattern);
            @endphp

            <ul class="about-album__list">
                @forelse($album as $idx => $photo)
                    @php
                        // поддержим и массив вида ['src' => '...'] и просто строку-URL
                        $src = is_array($photo) ? ($photo['src'] ?? '') : (string) $photo;
                        $isBig = $bigPattern[$idx % $patternLen] ?? false;
                    @endphp

                    @if($src !== '')
                        <li class="about-album__item">
                            <img src="{{ $src }}" alt=""
                                 class="about-album__img {{ $isBig ? 'about-album__img-big' : '' }}">
                        </li>
                    @endif
                @empty
                    {{-- Фоллбек-картинки, тоже по паттерну --}}
                    @php
                        $fallback = [
                            asset('assets/img/about/2.png'),
                            asset('assets/img/about/3.png'),
                            asset('assets/img/about/4.png'),
                            asset('assets/img/about/5.png'),
                            asset('assets/img/about/6.png'),
                            asset('assets/img/about/7.png'),
                        ];
                    @endphp
                    @foreach($fallback as $idx => $src)
                        @php $isBig = $bigPattern[$idx % $patternLen]; @endphp
                        <li class="about-album__item">
                            <img src="{{ $src }}" alt=""
                                 class="about-album__img {{ $isBig ? 'about-album__img-big' : '' }}">
                        </li>
                    @endforeach
                @endforelse
            </ul>
        </div>
    </section>

@endsection

