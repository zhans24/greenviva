@extends('layouts.app')

@section('title','Контакты')
@section('main_classes', 'page-main page-main-about')

@section('content')
    @php($c = $contacts ?? \App\Models\ContactSetting::getCached())

    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ Контакты</li>
            </ul>
        </nav>
    </div>

    <section class="contacts">
        <div class="container">
            <div class="contacts__inner">
                <div class="contacts__info">

                    <div class="contacts__group">
                        <h2 class="contacts__title">Контакты</h2>
                        <ul class="contacts__list">
                            @if(!empty($c?->phone))
                                <li class="contacts__item">
                                    <a href="{{ $c->phone }}" class="contacts__link">
                                        {{ preg_replace('/^tel:\+?/', '+', $c->phone) }}
                                    </a>
                                </li>
                            @endif
                            @if(!empty($c?->email_link))
                                <li class="contacts__item">
                                    <a href="{{ $c->email_link }}" class="contacts__link">
                                        {{ str_replace('mailto:','',$c->email_link) }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="contacts__group">
                        <h2 class="contacts__title">Адрес</h2>
                        <ul class="contacts__list">
                            @if(!empty($c?->address))
                                <li class="contacts__item">
                                    <a href="#!" class="contacts__link">{{ $c->address }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="contacts__group">
                        <h2 class="contacts__title">Социальные сети</h2>
                        <div class="footer__socials">
                            <a href="{{ $c?->whatsapp_link ?: '#!' }}"><img src="{{ asset('assets/icons/whatsapp.svg') }}" alt=""></a>
                            <a href="{{ $c?->youtube_link  ?: '#!' }}"><img src="{{ asset('assets/icons/youtube.svg') }}" alt=""></a>
                            <a href="{{ $c?->telegram_link ?: '#!' }}"><img src="{{ asset('assets/icons/telegram.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>

                <div class="contacts__map">
                    @if(!empty($c?->map_embed))
                        {!! $c->map_embed !!}
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="form-section form-section-contacts" style="background-image: url('{{ asset('assets/img/form/form-bg.png') }}');">
        <img class="form-section-image form-section-image-left" src="{{ asset('assets/img/form/1.png') }}" alt="hand">
        <img class="form-section-image form-section-image-right" src="{{ asset('assets/img/form/2.png') }}" alt="pul">
        <div class="container">
            <h2 class="form-section__title title">Контакты</h2>
            <div class="form-section__col">
                <div class="form-section__socials">
                    <a class="form-section__social" href="{{ $c?->whatsapp_link ?: '#!' }}">
                        <img src="{{ asset('assets/icons/whatsapp.svg') }}" alt="">
                    </a>
                    <a class="form-section__social" href="{{ $c?->youtube_link ?: '#!' }}">
                        <img src="{{ asset('assets/icons/youtube.svg') }}" alt="">
                    </a>
                    <a class="form-section__social" href="{{ $c?->telegram_link ?: '#!' }}">
                        <img src="{{ asset('assets/icons/telegram.svg') }}" alt="">
                    </a>
                </div>
                <form class="form" method="POST" action="{{ route('leads.store') }}">
                    @csrf
                    <input placeholder="Имя" class="form__input" name="name" autocomplete="name" id="name" type="text" required>
                    <input placeholder="+7 747 123 45 67" class="form__input" name="phone" autocomplete="phone" id="phone" type="tel" required>
                    <textarea placeholder="Сообщение" class="form__area" name="message" id="message"></textarea>
                    <button class="form__btn" type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </section>
@endsection
