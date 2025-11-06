@php
    $first = request()->segment(1);
    $isPref = in_array($first, ['kz','en']);
    $tailSegments = $isPref ? array_slice(request()->segments(), 1) : request()->segments();
    $tail = $tailSegments ? '/'.implode('/', $tailSegments) : '';
    $qs = request()->getQueryString();
    $qs = $qs ? '?'.$qs : '';

    $urlFor = fn (string $lang) => $lang === 'ru' ? url($tail ?: '/') . $qs : url('/'.$lang.$tail) . $qs;

    $locale = app()->getLocale();
    $pref = $locale === 'ru' ? '' : '/'.$locale;
@endphp

<header class="header">
    <div class="container">
        <div class="header__inner">
            <div class="logo-wrapper">
                <a class="logo" href="{{ url($pref . '/') }}">
                    <img class="logo__img" src="{{ asset('assets/img/logo.png') }}" alt="GREENVIVA">
                </a>
            </div>

            <div class="header__center">
                <nav class="header__nav">
                    <ul class="header__list">
                        <li class="header__item">
                            <a href="{{ url($pref . '/catalog') }}" class="header__link">{{ __('app.nav.catalog') }}</a>
                        <li class="header__item">
                            <a href="{{ url($pref . '/about') }}"   class="header__link">{{ __('app.nav.about') }}</a>
                        </li>
                        <li class="header__item">
                            <a href="{{ url($pref . '/contacts') }}" class="header__link">{{ __('app.nav.contacts') }}</a>
                        </li>
                    </ul>
                </nav>

                <div class="header__lang">
                    <a class="lang {{ $locale==='en' ? 'active' : '' }}" href="{{ $urlFor('en') }}">EN</a>
                    <a class="lang {{ $locale==='ru' ? 'active' : '' }}" href="{{ $urlFor('ru') }}">RUS</a>
                    <a class="lang {{ $locale==='kz' ? 'active' : '' }}" href="{{ $urlFor('kz') }}">KAZ</a>
                </div>
            </div>

            <div class="cart-btn-wrap">
                <a class="cart-btn" href="{{ url($pref . '/cart') }}">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="8.25" cy="18.75" r="1" fill="#01714B" stroke="#01714B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <circle cx="16.25" cy="18.75" r="1" fill="#01714B" stroke="#01714B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path d="M0.75 0.75H3.75L6.45 13.15C6.64295 14.0962 7.48448 14.7695 8.45 14.75H16.15C17.1155 14.7695 17.9571 14.0962 18.15 13.15L19.75 4.75H5.35" stroke="#01714B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <span>{{ __('app.cart.title') }}</span>
                    <span class="cart-count"></span>
                </a>
            </div>

            <div class="burger-wrap">
                <button class="burger" aria-label="Открыть меню">
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</header>
