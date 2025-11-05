@php($c = $contacts ?? \App\Models\ContactSetting::getCached())
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
                <p class="footer__desc">{{ $c?->company_text ?? ' ' }}</p>
            </div>

            <div class="footer__col">
                <ul>
                    <li><a class="footer__link" href="{{ url('/catalog') }}">Каталог продукции</a></li>
                    <li><a class="footer__link" href="{{ url('/about') }}">О компании</a></li>
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">Контакты</h4>
                <ul>
                    @if(!empty($c?->phone))
                        <li>
                            <a href="{{ $c->phone }}">
                                {{ preg_replace('/^tel:\+?/', '+', $c->phone) }}
                            </a>
                        </li>
                    @endif
                    @if(!empty($c?->email_link))
                        <li><a href="{{ $c->email_link }}">{{ str_replace('mailto:','',$c->email_link) }}</a></li>
                    @endif
                    @if(!empty($c?->address))
                        <li>{{ $c->address }}</li>
                    @endif
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">Социальные сети</h4>
                <div class="footer__socials">
                    <a href="{{ $c?->whatsapp_link ?: '#!' }}"><img src="{{ asset('assets/icons/whatsapp.svg') }}" alt=""></a>
                    <a href="{{ $c?->youtube_link  ?: '#!' }}"><img src="{{ asset('assets/icons/youtube.svg') }}" alt=""></a>
                    <a href="{{ $c?->telegram_link ?: '#!' }}"><img src="{{ asset('assets/icons/telegram.svg') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <div><a href="#!">@greenviva.kz</a></div>
            <div><a href="{{ url('/policy') }}">Политика конфиденциальности</a></div>
            <div>Разработка сайтов <a href="#!">Astana Creative</a></div>
        </div>
    </div>
</footer>
