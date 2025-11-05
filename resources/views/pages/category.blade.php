@extends('layouts.app')

@section('title', ($category->seo_title ?? $category->name))
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li><a class="breadcrumbs__link" href="{{ route('catalog.index') }}">/ Каталог продукции</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ {{ $category->name }}</li>
            </ul>
        </nav>
    </div>

    <section class="categories">
        <div class="container">
            <div class="categories__inner">
                <h2 class="categories__title title">{{ $category->seo_h1 ?? $category->name }}</h2>
                <p class="categories__result">{{ $totalCount }} товаров</p>

                <div class="categories__col">
                    {{-- ФИЛЬТРЫ --}}
                    <div class="categories__filter">
                        <form class="filter" method="GET" action="{{ route('catalog.category', $category->slug) }}">
                            <h3 class="filter__title">Фильтр</h3>

                            {{-- Бренды --}}
                            <div class="filter__group">
                                <p class="filter__label">Бренд</p>
                                @foreach($brands as $brand)
                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="brands[]"
                                            value="{{ $brand->id }}"
                                            {{ in_array($brand->id, $selected, true) ? 'checked' : '' }}
                                        />
                                        <span>{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Цена --}}
                            <div class="filter__price">
                                <p class="filter__label">Цена</p>
                                <div class="filter__range">
                                    <label class="price-field">
                                        <span class="price-field__label">От</span>
                                        <input type="number" name="price_from" inputmode="numeric"
                                               value="{{ old('price_from', $price_from) }}" placeholder="0" />
                                    </label>

                                    <span class="filter__dash" aria-hidden="true">—</span>

                                    <label class="price-field">
                                        <span class="price-field__label">До</span>
                                        <input type="number" name="price_to" inputmode="numeric"
                                               value="{{ old('price_to', $price_to) }}" placeholder="0" />
                                    </label>
                                </div>
                            </div>


                            <div class="filter__actions">
                                <button type="submit" class="filter__btn">Показать</button>
                                <a href="{{ route('catalog.category', $category->slug) }}" class="filter__reset" id="filter-reset">Сбросить</a>
                            </div>

                        </form>
                    </div>

                    {{-- СПИСОК ТОВАРОВ --}}
                    <div class="categories__info">
                        <ul class="categories__list">
                            @forelse ($products as $p)
                                @php
                                    $effectivePrice = $p->old_price ?? $p->price;
                                    $formattedPrice = number_format($effectivePrice, 0, '.', ' ');
                                @endphp
                                <li class="categories__item">
                                    <a class="categories__link" href="{{ route('product.show', $p->slug) }}">
                                        <div class="categories__link-top">
                                            @if($p->is_best_seller)
                                                <div class="badge">Best seller</div>
                                            @endif
                                            <img src="{{ $p->cover_url ?? asset('assets/img/products/placeholder.png') }}" alt="{{ $p->name }}" />
                                        </div>
                                    </a>
                                    <div class="categories__item-bt">
                                        <p class="categories__name">{{ $p->name }}</p>
                                        <p class="categories__price"><span>{{ $formattedPrice }}</span> Т</p>
                                        <button class="categories__btn add-to-cart"
                                                data-product='@json(["id"=>$p->id,"name"=>$p->name,"price"=>$effectivePrice])'>
                                            Добавить в корзину
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="categories__item">
                                    <div class="categories__link" style="pointer-events: none">
                                        <div class="categories__link-top">
                                            <img src="{{ asset('assets/img/products/placeholder.png') }}" alt="Нет товаров" />
                                        </div>
                                    </div>
                                    <div class="categories__item-bt">
                                        <p class="categories__name">Товары не найдены</p>
                                        <p class="categories__price"><span>—</span></p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>

                        {{-- ПАГИНАЦИЯ (под твои селекторы) --}}
                        @if($products->hasPages())
                            <ul class="pagination-list">
                                {{-- Prev --}}
                                <li>
                                    @if ($products->onFirstPage())
                                        <span class="pagination-btn pagination-btn-prev" aria-disabled="true">
                      <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.75 10.75L0.75 5.75L5.75 0.75" stroke="white" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                                    @else
                                        <a class="pagination-btn pagination-btn-prev" href="{{ $products->previousPageUrl() }}">
                                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.75 10.75L0.75 5.75L5.75 0.75" stroke="white" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </li>

                                {{-- Нумерация --}}
                                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    <li>
                                        @if ($page == $products->currentPage())
                                            <span class="pagination-item active">{{ $page }}</span>
                                        @else
                                            <a class="pagination-item" href="{{ $url }}">{{ $page }}</a>
                                        @endif
                                    </li>
                                @endforeach

                                {{-- Next --}}
                                <li>
                                    @if ($products->hasMorePages())
                                        <a class="pagination-btn pagination-btn-next" href="{{ $products->nextPageUrl() }}">
                                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.75 10.75L5.75 5.75L0.75 0.75" stroke="white" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="pagination-btn pagination-btn-next" aria-disabled="true">
                      <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 10.75L5.75 5.75L0.75 0.75" stroke="white" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                                    @endif
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        (() => {
            const resetEl = document.getElementById('filter-reset');
            if (!resetEl) return;

            resetEl.addEventListener('click', (e) => {
                const form = resetEl.closest('form');
                if (form) {
                    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    form.querySelectorAll('input[type="number"], input[type="text"]').forEach(i => i.value = '');
                }


                e.preventDefault();
                window.location.href = resetEl.getAttribute('href');
            });
        })();
    </script>

@endsection

