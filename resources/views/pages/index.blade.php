@extends('layouts.app')

@section('title', $data['meta']['title'] ?: 'GREENVIVA')
@push('meta')
    @if(!empty($data['meta']['description']))
        <meta name="description" content="{{ $data['meta']['description'] }}">
    @endif
@endpush

@section('content')
    @php
        $hero        = $data['home']['hero']       ?? [];
        $advantages  = $data['home']['advantages'] ?? ['title' => 'Преимущества', 'items' => []];
        /** @var \Illuminate\Support\Collection|\App\Models\Product[] $popular */
        $popular     = $data['home']['popular']    ?? collect();
        $banners     = $data['home']['banners']    ?? [];
        $brands      = $data['home']['brands']     ?? [];
        $reviews     = $data['home']['reviews']    ?? [];
    @endphp

    <section class="hero">
        <div class="hero__slider hero-swiper">
            <div class="swiper-wrapper">
                @foreach($hero as $s)
                    <div class="swiper-slide hero__slide">
                        <div class="hero__image hero__image--left">
                            @if(!empty($s['left'])) <img src="{{ $s['left'] }}" alt="Левая упаковка"> @endif
                        </div>
                        <div class="hero__image hero__image--center">
                            @if(!empty($s['center'])) <img src="{{ $s['center'] }}" alt="Центральная банка"> @endif
                        </div>
                        <div class="hero__image hero__image--right">
                            @if(!empty($s['right'])) <img src="{{ $s['right'] }}" alt="Капсулы"> @endif
                        </div>

                        <div class="hero__content">
                            <h2 class="hero__title">{!! nl2br(e($s['title'] ?? '')) !!}</h2>
                            @if(!empty($s['text'])) <p class="hero__text">{{ $s['text'] }}</p> @endif
                            @if(!empty($s['btn_text']))
                                <a href="{{ $s['btn_url'] ?: '/catalog' }}" class="btn btn--white">{{ $s['btn_text'] }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="advantages">
        <div class="decor-element-left">
            <img src="{{ asset('assets/img/decor-1.png') }}" alt="Преимущества декор">
        </div>
        <div class="container">
            <div class="advantages__inner">
                <h1 class="title advantages__title">{{ $advantages['title'] ?? 'Преимущества' }}</h1>
                <div class="advantages-bg-decor">
                    <ul class="advantages__list">
                        @php $items = $advantages['items'] ?? []; @endphp
                        <li class="advantages__item">
                            <div class="advantages__top"><img src="{{ asset('assets/img/list.png') }}" alt=""></div>
                            <div class="advantages__content">
                                <h2 class="advantages__item-title">{{ $items[0]['title'] ?? 'Натуральность' }}</h2>
                                <p class="advantages__item-text">{{ $items[0]['text'] ?? '' }}</p>
                            </div>
                        </li>
                        <li class="advantages__item">
                            <div class="advantages__top"><img src="{{ asset('assets/img/certificate.png') }}" alt=""></div>
                            <div class="advantages__content">
                                <h2 class="advantages__item-title">{{ $items[1]['title'] ?? 'Сертификация' }}</h2>
                                <p class="advantages__item-text">{{ $items[1]['text'] ?? '' }}</p>
                            </div>
                        </li>
                        <li class="advantages__item">
                            <div class="advantages__top"><img src="{{ asset('assets/img/rang.png') }}" alt=""></div>
                            <div class="advantages__content">
                                <h2 class="advantages__item-title">{{ $items[2]['title'] ?? 'Эффективность' }}</h2>
                                <p class="advantages__item-text">{{ $items[2]['text'] ?? '' }}</p>
                            </div>
                        </li>
                    </ul>
                    <div class="advantages-bg-decor-img">
                        <img src="{{ asset('assets/img/bg-card-line-light.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="popular">
        <div class="popular__inner">
            <div class="container">
                <div class="popular__top">
                    <h2 class="title popular__title">Популярные товары</h2>
                    <div class="popular__navigtion">
                        <button class="popular__btn popular__btn-prev"><img src="{{ asset('assets/icons/arrow-left.svg') }}" alt=""></button>
                        <button class="popular__btn popular__btn-next"><img src="{{ asset('assets/icons/arrow-rigth.svg') }}" alt=""></button>
                    </div>
                </div>
            </div>
            <div class="popular__slider swiper">
                <div class="swiper-wrapper">
                    @foreach($popular as $p)
                        @php $eff = $p->price ?? $p->old_price; $effF = number_format($eff, 0, '.', ' '); @endphp
                        <div class="swiper-slide">
                            <div class="popular__item">
                                <a href="{{ route('product.show', $p->slug) }}" class="popular__item-top">
                                    <img src="{{ $p->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $p->name }}">
                                </a>
                                <div class="popular__item-content">
                                    <h3 class="popular__item-name">{{ $p->name }}</h3>
                                    <p class="popular__item-text">{{ \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 90) }}</p>
                                    <button class="popular__item-btn add-to-cart"
                                            data-product='@json(["id"=>$p->id,"name"=>$p->name,"price"=>$eff])'>
                                        Добавить в корзину ({{ $effF }} T)
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="banner">
        <div class="container">
            <div class="banner__inner">
                <div class="banner-slider swiper">
                    <div class="swiper-wrapper">
                        @foreach($banners as $src)
                            <div class="banner-slide swiper-slide">
                                <div class="banner-slide-top">Акции</div>
                                <img src="{{ $src }}" alt="Акции">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <img class="banner__inner-decor" src="{{ asset('assets/img/decor-4.png') }}" alt="">
            </div>
        </div>
    </div>

    <div class="brands">
        <div class="brands-slider swiper">
            <div class="swiper-wrapper">
                @foreach($brands as $item)
                    <div class="swiper-slide">
                        <div class="brands-item">{{ $item['label'] ?? 'LOGO' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <section class="main-reviews">
        <img class="main-reviews__decor" src="{{ asset('assets/img/decor-5.png') }}" alt="">
        <div class="container">
            <div class="main-reviews__top">
                <h2 class="title main-reviews__title">Отзывы клиентов</h2>
                <div class="main-reviews__navigtion">
                    <button class="main-reviews__btn main-reviews__btn-prev">
                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 13L1 7L7 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="main-reviews__btn main-reviews__btn-next">
                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 13L7 7L1 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="main-reviews__content">
            <div class="main-reviews-slider swiper">
                <div class="swiper-wrapper">
                    @foreach($reviews as $r)
                        <div class="swiper-slide">
                            <div class="main-reviews-slide">
                                <div class="main-reviews__ava">
                                    <img src="{{ $r['avatar'] ?? asset('assets/img/user/1.png') }}" alt="User">
                                </div>
                                <h3 class="main-reviews__name">{{ $r['author'] ?? 'Гость' }}</h3>
                                <p class="main-reviews__text">{{ $r['text'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="form-section form-section-main" style="background-image: url('{{ asset('assets/img/form/form-bg.png') }}');">
        <img class="form-section-image form-section-image-left" src="{{ asset('assets/img/form/1.png') }}" alt="hand">
        <img class="form-section-image form-section-image-right" src="{{ asset('assets/img/form/2.png') }}" alt="pul">
        <div class="container">
            <h2 class="form-section__title title">Контакты</h2>
            <div class="form-section__col">
                <div class="form-section__socials">
                    <a class="form-section__social" href="#!"><img src="{{ asset('assets/icons/whatsapp.svg') }}" alt=""></a>
                    <a class="form-section__social" href="#!"><img src="{{ asset('assets/icons/youtube.svg') }}" alt=""></a>
                    <a class="form-section__social" href="#!"><img src="{{ asset('assets/icons/telegram.svg') }}" alt=""></a>
                </div>
                <form class="form" method="POST" action="{{ route('leads.store') }}">
                    @csrf
                    <input placeholder="Имя" class="form__input" name="name" id="name" type="text" required>
                    <input placeholder="+7 747 123 45 67" class="form__input" name="phone" id="phone" type="tel" required>
                    <textarea placeholder="Сообщение" class="form__area" name="message" id="message"></textarea>
                    <button class="form__btn" type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </section>
@endsection
