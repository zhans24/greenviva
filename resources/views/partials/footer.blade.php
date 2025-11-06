@php
    use Illuminate\Support\Arr;

    $loc = app()->getLocale();

    try {
        $c = $contacts ?? \App\Models\ContactSetting::getCached();
    } catch (\Throwable $e) {
        $c = null;
    }

    // Всегда работаем с локальной переменной, НЕ с $c->company_text напрямую в array_* функциях
    $raw = $c?->company_text;

    // приведение к массиву
    if (is_string($raw)) {
        $raw = ['ru' => $raw];
    } elseif ($raw instanceof Traversable) {
        $raw = iterator_to_array($raw);
    } elseif (!is_array($raw)) {
        $raw = [];
    }

    // предпочтения локали
    $prefer = [$loc, 'ru', 'kz', 'en'];

    $companyText = '';
    foreach ($prefer as $lng) {
        if (!empty($raw[$lng])) {
            $companyText = trim((string) $raw[$lng]);
            break;
        }
    }

    // если по предпочтениям ничего нет — берём первый непустой
    if ($companyText === '') {
        $companyText = trim((string) (Arr::first(
            array_filter($raw, fn ($v) => is_string($v) && trim($v) !== '')
        ) ?? ''));
    }
@endphp

<footer class="footer">
    <div class="footer__wave">
        <svg width="1444" height="103" viewBox="0 0 1444 103" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M0 0C0 0 138 80.7226 294 35.6334C450 -9.45588 521 28.5009 584 47.2726C647 66.0442 759.493 71.9017 901.493 55.9916C1043.49 40.0815 1100.76 -1.26651 1240 10.5C1382 22.5 1444 55.9916 1444 55.9916V102.5H0V0Z"
                  fill="#0C2529" />
        </svg>
    </div>

    <div class="footer__top">
        <div class="container footer__inner">
            <div class="footer__col">
                <div class="footer__logo">{{ $c?->company_name ?? ' ' }}</div>
                <p class="footer__desc">{{ $companyText ?? '' }}</p>
            </div>

            <div class="footer__col">
                <ul>
                    <li>
                        <a class="footer__link"
                           href="{{ $loc==='ru'
                                ? route('catalog.index')
                                : route('catalog.index.localized', ['locale'=>$loc]) }}">
                            {{ __('app.nav.catalog') }}
                        </a>
                    </li>
                    <li>
                        <a class="footer__link"
                           href="{{ $loc==='ru'
                                ? route('about')
                                : route('about.localized', ['locale'=>$loc]) }}">
                            {{ __('app.nav.about') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">{{ __('app.footer.contacts') }}</h4>
                <ul>
                    @if(!empty($c?->phone))
                        <li>
                            <a href="{{ $c->phone }}">
                                {{ preg_replace('/^tel:\+?/', '+', $c->phone) }}
                            </a>
                        </li>
                    @endif

                    @if(!empty($c?->email_link))
                        <li>
                            <a href="{{ $c->email_link }}">{{ str_replace('mailto:','',$c->email_link) }}</a>
                        </li>
                    @endif

                    @if(!empty($c?->address))
                        <li>{{ $c->address }}</li>
                    @endif
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">{{ __('app.footer.socials') }}</h4>
                <div class="footer__socials">
                    <a href="{{ $c?->whatsapp_link ?: '#!' }}" target="_blank"><img src="{{ asset('assets/icons/whatsapp.svg') }}" alt=""></a>
                    <a href="{{ $c?->youtube_link  ?: '#!' }}" target="_blank"><img src="{{ asset('assets/icons/youtube.svg') }}" alt=""></a>
                    <a href="{{ $c?->telegram_link ?: '#!' }}" target="_blank"><img src="{{ asset('assets/icons/telegram.svg') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <div><a href="#!">@greenviva.kz</a></div>
            <div>
                <a href="{{ $loc==='ru' ? route('privacy') : route('privacy.localized', ['locale'=>$loc]) }}">
                    {{ __('app.nav.privacy') }}
                </a>
            </div>
            <div>Разработка сайтов <a href="https://astanacreative.kz/" target="_blank">Astana Creative</a></div>
        </div>
    </div>
</footer>
@php $telHref = $c?->phone ? (str_starts_with($c->phone,'tel:') ? $c->phone : 'tel:'.$c->phone) : '#!'; @endphp

<div class="call-wrap">
    <a href="{{ $telHref }}" class="call">
        <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.19922 9.34833C8.98113 7.56642 11.7205 7.87104 12.7832 9.77509L13.7031 11.4225C14.4115 12.6919 14.143 14.4017 12.9756 15.569L13.2207 15.8141C13 15.5936 12.978 15.5719 12.9756 15.57L12.9746 15.571L12.9727 15.572C12.9717 15.573 12.9709 15.5747 12.9697 15.5759C12.9673 15.5783 12.964 15.5814 12.9609 15.5847C12.9546 15.5913 12.9473 15.6001 12.9385 15.61C12.9205 15.6303 12.8973 15.6571 12.8711 15.6911C12.8187 15.7591 12.7519 15.8554 12.6855 15.9792C12.5525 16.2276 12.4192 16.5878 12.3965 17.0505C12.3505 17.9906 12.7642 19.2344 14.2646 20.735C15.7651 22.2355 17.0099 22.6491 17.9502 22.6032C18.4127 22.5805 18.7722 22.447 19.0205 22.3141C19.1442 22.2479 19.2405 22.1819 19.3086 22.1296C19.3427 22.1033 19.3703 22.0802 19.3906 22.0622C19.4006 22.0533 19.4093 22.046 19.416 22.0397C19.4194 22.0366 19.4223 22.0334 19.4248 22.0309C19.4261 22.0297 19.4267 22.0281 19.4277 22.027L19.4297 22.0261L19.4316 22.0241C20.5991 20.857 22.3088 20.5892 23.5781 21.2975L25.2256 22.2165C27.1297 23.2791 27.4342 26.0194 25.6523 27.8015C24.5078 28.9459 23.1683 29.7699 21.7432 29.8239C19.1553 29.9221 14.7076 29.2746 10.2168 24.7839C5.72596 20.293 5.07767 15.8455 5.17578 13.2575C5.22981 11.8323 6.05462 10.4929 7.19922 9.34833Z" fill="white" stroke="#01714B"></path>
            <path d="M17.7839 4.66308C17.8777 4.08381 18.4253 3.69087 19.0045 3.78464C19.0404 3.79151 19.1558 3.81308 19.2162 3.82653C19.3372 3.85346 19.5058 3.8949 19.716 3.95615C20.1365 4.0786 20.7238 4.28035 21.4287 4.60353C22.8402 5.25061 24.7184 6.38253 26.6674 8.33158C28.6165 10.2806 29.7484 12.1589 30.3954 13.5703C30.7187 14.2752 30.9204 14.8626 31.0428 15.283C31.104 15.4933 31.1455 15.6619 31.1724 15.7827C31.1859 15.8432 31.1958 15.8917 31.2026 15.9276L31.2108 15.9718C31.3045 16.551 30.9152 17.1213 30.3359 17.2151C29.7583 17.3086 29.2142 16.9175 29.1181 16.341C29.1152 16.3255 29.1069 16.2839 29.0983 16.2447C29.0807 16.1662 29.0506 16.0418 29.0027 15.8773C28.9068 15.5481 28.7403 15.0589 28.4638 14.4559C27.9116 13.2512 26.9185 11.5878 25.1648 9.83418C23.4112 8.08054 21.7478 7.08747 20.5432 6.53521C19.9401 6.25874 19.4509 6.09224 19.1217 5.99636C18.9572 5.94843 18.7504 5.90096 18.6719 5.88349C18.0953 5.7874 17.6904 5.24068 17.7839 4.66308Z" fill="white"></path>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.1046 9.54982C18.2658 8.98559 18.8539 8.65888 19.4181 8.82008L19.1263 9.84171C19.4181 8.82008 19.4181 8.82008 19.4181 8.82008L19.4203 8.82068L19.4224 8.8213L19.4271 8.82268L19.4381 8.82596L19.4662 8.83469C19.4874 8.84152 19.5142 8.85041 19.5459 8.86171C19.6095 8.88429 19.6937 8.91639 19.7968 8.96059C20.0032 9.04904 20.2851 9.18564 20.6317 9.39065C21.325 9.80103 22.2716 10.4827 23.3837 11.5947C24.4958 12.7068 25.1773 13.6534 25.5877 14.3468C25.7927 14.6932 25.9293 14.9752 26.0178 15.1816C26.062 15.2847 26.0942 15.3688 26.1167 15.4324C26.128 15.4642 26.1368 15.4909 26.1438 15.5123L26.1524 15.5403L26.1557 15.5513L26.1571 15.556L26.1577 15.5582C26.1577 15.5582 26.1584 15.5602 25.1367 15.8521L26.1584 15.5602C26.3196 16.1244 25.9928 16.7125 25.4286 16.8737C24.8692 17.0335 24.2862 16.7138 24.1192 16.1583L24.114 16.1431C24.1065 16.1218 24.0909 16.0799 24.0647 16.0187C24.0123 15.8963 23.9172 15.6963 23.7591 15.4291C23.4432 14.8954 22.8727 14.0889 21.881 13.0973C20.8895 12.1057 20.083 11.5353 19.5493 11.2194C19.2821 11.0612 19.0821 10.9662 18.9597 10.9138C18.8985 10.8876 18.8566 10.8719 18.8353 10.8644L18.82 10.8591C18.2647 10.6921 17.9448 10.1092 18.1046 9.54982Z" fill="white"></path>
        </svg>
    </a>
</div>
