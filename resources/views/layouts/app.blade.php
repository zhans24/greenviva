<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','GREENVIVA')</title>

    @stack('meta')

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}" />

    <link href="https://fonts.googleapis.com/css?family=El+Messiri:regular,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
<div class="wrapper">
    @include('partials.header')

    <main class="main @yield('main_classes')">
        @yield('content')
    </main>

    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/inputmask.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    @php
        $i18n = [
            'cart' => [
                            'add'        => __('app.cart.add'),
            'add_short'  => __('app.cart.add_short'),
            'in_cart'    => __('app.cart.in_cart'),
            'empty'      => __('app.cart.empty'),
            'code'       => __('app.cart.code'),
            'currency'   => __('app.cart.currency'),
            ],
            'toast' => [
            'added'         => __('app.toast.added'),
            'cart_empty'    => __('app.toast.cart_empty'),
            'parse_error'   => __('app.toast.parse_error'),
            'fill_required' => __('app.toast.fill_required'),
            'order_error'   => __('app.toast.order_error'),
            'order_ok'      => __('app.toast.order_ok'),
            'lead_ok'       => __('app.toast.lead_ok'),
            'lead_error'    => __('app.toast.lead_error'),
            'network'       => __('app.toast.network'),
            ],
            'form' => [
                'name'    => __('app.form.name'),
                'phone'   => __('app.form.phone'),
                'address' => __('app.form.address'),
                'message' => __('app.form.message'),
                'send'    => __('app.form.send'),
            ],
            'breadcrumbs' => [
                'home' => __('app.breadcrumbs.home'),
                'aria' => __('app.breadcrumbs.aria'),
                'cart' => __('app.breadcrumbs.cart'),
            ],
            'buttons' => [
                'add_to_cart' => __('app.buttons.add_to_cart'),
                'in_cart'     => __('app.buttons.in_cart'),
            ],
        ];
    @endphp

    window.I18N = {!! json_encode($i18n, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};
</script>
<script>
    (function () {
        const get = (obj, path) => path.split('.').reduce((o,k)=> (o && o[k]!=null) ? o[k] : undefined, obj);
        window.T = (path, fallback='') => {
            const v = get(window.I18N || {}, path);
            return (typeof v === 'string' && v.trim() !== '') ? v : fallback;
        };
    })();
</script>
@stack('scripts')


</body>
</html>
