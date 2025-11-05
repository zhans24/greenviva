@extends('layouts.app')

@section('title', ($product->seo_title ?? $product->name))
@push('meta')
    <meta name="description" content="{{ $product->seo_description ?? '' }}">
@endpush
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li><a class="breadcrumbs__link" href="{{ route('catalog.index') }}">/ Каталог продукции</a></li>
                @if($product->category)
                    <li><a class="breadcrumbs__link" href="{{ route('catalog.category', $product->category->slug) }}">/ {{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumbs__link" aria-current="page">/ {{ $product->name }}</li>
            </ul>
        </nav>
    </div>

    <section class="about-product">
        <div class="container">
            <div class="about-product__inner">

                {{-- LEFT: Карусель --}}
                <div class="about-product__left">
                    <div class="about-product-swiper swiper">
                        <div class="swiper-wrapper">
                            {{-- Сначала обложка --}}
                            <div class="swiper-slide">
                                <div class="about-product-swiper__slide">
                                    @if($product->is_best_seller)
                                        <div class="badge">Best seller</div>
                                    @endif
                                    <img src="{{ $product->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                            {{-- Затем галерея --}}
                            @foreach($gallery as $media)
                                <div class="swiper-slide">
                                    <div class="about-product-swiper__slide">
                                        @if($product->is_best_seller)
                                            <div class="badge">Best seller</div>
                                        @endif
                                        <img src="{{ $media->getUrl('webp') }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="about-product-small swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="about-product-small__slide">
                                    <img src="{{ $product->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                            @foreach($gallery as $media)
                                <div class="swiper-slide">
                                    <div class="about-product-small__slide">
                                        <img src="{{ $media->getUrl('webp') }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Инфо и цены --}}
                <div class="about-product__right">
                    @if($product->sku)
                        <div class="about-product__code">Код товара: {{ $product->sku }}</div>
                    @endif

                    <h2 class="about-product__title title">
                        {{ $product->seo_h1 ?? $product->name }}
                        @if($product->is_available)
                            <span>В наличии</span>
                        @else
                            <span style="background:#aaa;">Нет в наличии</span>
                        @endif
                    </h2>

                        @php
                            $hasDiscount = filled($product->price) && filled($product->old_price) && $product->price < $product->old_price;
                            $effective   = $hasDiscount ? $product->price : ($product->old_price ?? $product->price);
                            $effectiveFormatted = number_format($effective, 0, '.', ' ');
                        @endphp


                        <div class="about-product__prices">
                        <p class="about-product__price"><span>{{ $effectiveFormatted }}</span> T</p>
                        @if($hasDiscount)
                            <p class="about-product__old-price">{{ number_format($product->old_price, 0, '.', ' ') }} T</p>
                        @endif
                    </div>

                    <div class="about-product__cart">
                        <div class="cart-btns">
                            <button class="cart-button cart-button-minus" type="button" aria-label="Минус">
                                <svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 1.2V0H8V1.2H0Z" fill="#01714B" />
                                </svg>
                            </button>
                            <span class="cart-count">1</span>
                            <button class="cart-button cart-button-plus" type="button" aria-label="Плюс">
                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.312 7.744V4.448H0V3.296H3.312V0H4.512V3.296H7.824V4.448H4.512V7.744H3.312Z" fill="white" />
                                </svg>
                            </button>
                        </div>

                        @if($product->is_available)
                            <button class="cart-link add-to-cart"
                                    data-product='@json(["id"=>$product->id,"name"=>$product->name,"price"=>$effective])'>
                                Добавить в корзину
                            </button>
                        @else
                            <button class="cart-link" disabled style="opacity:.7;cursor:not-allowed;">Нет в наличии</button>
                        @endif
                    </div>

                    <div class="line"></div>
                    @if($product->delivery_info)
                        <div class="info-pay">{!! $product->delivery_info !!}</div>
                    @else
                        <div class="info-pay">Информация про доставку и оплату</div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <section class="product-info">
        <div class="container product-info__container">
            <div class="product-info__tabs">
                <button class="product-info__tab product-info__tab--active" data-tab="description">Описание</button>
                <button class="product-info__tab" data-tab="composition">Состав</button>
                <button class="product-info__tab" data-tab="usage">Применение</button>
                <button class="product-info__tab" data-tab="certificates">Сертификаты</button>
            </div>

            <div class="product-info__content">
                <div class="product-info__panel product-info__panel--active" id="description">
                    <div class="description-text">
                        {!! $product->description ?: 'Описание скоро появится.' !!}
                    </div>
                </div>

                <div class="product-info__panel" id="composition">
                    <div>{!! $product->composition ?: '—' !!}</div>
                </div>

                <div class="product-info__panel" id="usage">
                    <div>{!! $product->usage ?: '—' !!}</div>
                </div>

                <div class="product-info__panel" id="certificates">
                    <div class="product-info__certificates">
                        @forelse($certs as $c)
                            <div class="certificate-card">
                                <img src="{{ $c->getUrl('webp') }}" alt="Сертификат">
                            </div>
                        @empty
                            <p>Сертификаты отсутствуют.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="related-products">
            <div class="container">
                <h2 class="title related-products__title">С этим товаром часто покупают</h2>
            </div>
            <div class="related-products__swiper swiper">
                <div class="swiper-wrapper">
                    @foreach($related as $r)
                        @php
                            $relEffective = $r->price ?? $r->old_price;
                            $relFormatted = number_format($relEffective, 0, '.', ' ');
                        @endphp
                        <div class="swiper-slide">
                            <div class="related-products__item">
                                <a href="{{ route('product.show', $r->slug) }}" class="related-products__top">
                                    <img src="{{ $r->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $r->name }}">
                                </a>
                                <div class="related-products__bottom">
                                    <h3 class="related-products__name">{{ $r->name }}</h3>
                                    <p class="related-products__text">
                                        {{ Str::limit(strip_tags($r->description ?? ''), 80) }}
                                    </p>
                                    <button class="related-products__btn add-to-cart"
                                            data-product='@json(["id"=>$r->id,"name"=>$r->name,"price"=>$relEffective])'>
                                        В корзину
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        // табы (оставил твой скрипт, только завернул в push)
        (function () {
            const tabs = document.querySelectorAll('.product-info__tab');
            const panels = document.querySelectorAll('.product-info__panel');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('product-info__tab--active'));
                    tab.classList.add('product-info__tab--active');
                    panels.forEach(panel => {
                        panel.classList.remove('product-info__panel--active');
                        if (panel.id === tab.dataset.tab) panel.classList.add('product-info__panel--active');
                    });
                });
            });
        })();
    </script>
@endpush
