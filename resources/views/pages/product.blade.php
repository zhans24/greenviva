@extends('layouts.app')

@section('title', ($product->seo_title ?? $product->name))
@push('meta')
    <meta name="description" content="{{ $product->seo_description ?? '' }}">
@endpush
@section('main_classes', 'page-main')
@php $loc = app()->getLocale(); @endphp

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="{{ __('app.breadcrumbs.aria') }}">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">{{ __('app.breadcrumbs.home') }}</a></li>
                <li><a class="breadcrumbs__link"
                       href="{{ $loc === 'ru'
                            ? route('catalog.index')
                            : route('catalog.index.localized', ['locale' => $loc]) }}">
                        / {{ __('app.breadcrumbs.catalog') }}
                    </a>
                </li>

                @if($product->category)
                    <li><a class="breadcrumbs__link"
                           href="{{ $loc === 'ru'
                                ? route('catalog.category', $product->category->slug)
                                : route('catalog.category.localized', ['locale' => $loc, 'slug' => $product->category->slug]) }}">
                            / {{ $product->category->name }}
                        </a>
                    </li>
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
                                        <div class="badge">{{ __('app.badge.best_seller') }}</div>
                                    @endif
                                    <img src="{{ $product->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                            {{-- Затем галерея --}}
                            @foreach($gallery as $media)
                                <div class="swiper-slide">
                                    <div class="about-product-swiper__slide">
                                        @if($product->is_best_seller)
                                            <div class="badge">{{ __('app.badge.best_seller') }}</div>
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
                        <div class="about-product__code">{{ __('app.product.sku') }} {{ $product->sku }}</div>
                    @endif

                    <h2 class="about-product__title title">
                        {{ $product->seo_h1 ?? $product->name }}
                        @if($product->is_available)
                            <span>{{ __('app.product.in_stock') }}</span>
                        @else
                            <span style="background:#aaa;">{{ __('app.product.out_of_stock') }}</span>
                        @endif
                    </h2>

                    @php
                        $hasDiscount = filled($product->price) && filled($product->old_price) && $product->price < $product->old_price;
                        $effective   = $hasDiscount ? $product->price : ($product->old_price ?? $product->price);
                        $effectiveFormatted = number_format($effective, 0, '.', ' ');
                    @endphp

                    <div class="about-product__prices">
                        <p class="about-product__price"><span>{{ $effectiveFormatted }}</span> {{ __('app.currency') }}</p>
                        @if($hasDiscount)
                            <p class="about-product__old-price">{{ number_format($product->old_price, 0, '.', ' ') }} {{ __('app.currency') }}</p>
                        @endif
                    </div>

                    <div class="about-product__cart">
                        <div class="cart-btns">
                            <button class="cart-button cart-button-minus" type="button" aria-label="{{ __('app.product.qty.minus') }}">
                                <svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 1.2V0H8V1.2H0Z" fill="#01714B" />
                                </svg>
                            </button>
                            <span class="cart-count">1</span>
                            <button class="cart-button cart-button-plus" type="button" aria-label="{{ __('app.product.qty.plus') }}">
                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.312 7.744V4.448H0V3.296H3.312V0H4.512V3.296H7.824V4.448H4.512V7.744H3.312Z" fill="white" />
                                </svg>
                            </button>
                        </div>

                        @if($product->is_available)
                            <button class="cart-link add-to-cart"
                                    data-product='@json(["id"=>$product->id,"name"=>$product->name,"price"=>$effective])'>
                                {{ __('app.cart.add') }}
                            </button>
                        @else
                            <button class="cart-link" disabled style="opacity:.7;cursor:not-allowed;">{{ __('app.product.out_of_stock') }}</button>
                        @endif
                    </div>

                    <div class="line"></div>
                    @if($product->delivery_info)
                        <div class="info-pay">{!! $product->delivery_info !!}</div>
                    @else
                        <div class="info-pay">{{ __('app.product.delivery_info') }}</div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <section class="product-info">
        <div class="container product-info__container">
            <div class="product-info__tabs">
                <button class="product-info__tab product-info__tab--active" data-tab="description">{{ __('app.product.tabs.description') }}</button>
                <button class="product-info__tab" data-tab="composition">{{ __('app.product.tabs.composition') }}</button>
                <button class="product-info__tab" data-tab="usage">{{ __('app.product.tabs.usage') }}</button>
                <button class="product-info__tab" data-tab="certificates">{{ __('app.product.tabs.certificates') }}</button>
            </div>

            <div class="product-info__content">
                <div class="product-info__panel product-info__panel--active" id="description">
                    <div class="description-text">
                        {!! $product->description ?: __('app.product.description_soon') !!}
                    </div>
                </div>

                <div class="product-info__panel" id="composition">
                    <div>{!! $product->composition ?: __('app.common.na') !!}</div>
                </div>

                <div class="product-info__panel" id="usage">
                    <div>{!! $product->usage ?: __('app.common.na') !!}</div>
                </div>

                <div class="product-info__panel" id="certificates">
                    <div class="product-info__certificates">
                        @forelse($certs as $c)
                            <div class="certificate-card">
                                <img src="{{ $c->getUrl('webp') }}" alt="{{ __('app.product.certificate.alt') }}">
                            </div>
                        @empty
                            <p>{{ __('app.product.certificate.empty') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="related-products">
            <div class="container">
                <h2 class="title related-products__title">{{ __('app.product.related') }}</h2>
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
                                <a href="{{ $loc === 'ru'
                                    ? route('product.show', $r->slug)
                                    : route('product.show.localized', ['locale' => $loc, 'slug' => $r->slug]) }}"
                                   class="related-products__top">
                                    <img src="{{ $r->cover_url ?? asset('assets/img/404.png') }}" alt="{{ $r->name }}">
                                </a>
                                <div class="related-products__bottom">
                                    <h3 class="related-products__name">{{ $r->name }}</h3>
                                    <p class="related-products__text">
                                        {{ Str::limit(strip_tags($r->description ?? ''), 80) }}
                                    </p>
                                    <button class="related-products__btn add-to-cart"
                                            data-product='@json(["id"=>$r->id,"name"=>$r->name,"price"=>$relEffective])'>
                                        {{ __('app.cart.add_short') }} ({{ $relFormatted }} {{ __('app.currency') }})
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
