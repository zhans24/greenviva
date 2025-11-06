@php use App\Models\ContactSetting; @endphp
@extends('layouts.app')

@section('title', __('app.contacts.title'))
@section('main_classes', 'page-main page-main-about')
@php
    try {
        $c = $contacts ?? \App\Models\ContactSetting::getCached();
    } catch (\Throwable $e) {
        $c = null;
    }

@endphp
@section('content')

    <div class="container">
        <nav class="breadcrumbs" aria-label="{{ __('app.breadcrumbs.aria') }}">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">{{ __('app.breadcrumbs.home') }}</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ {{ __('app.contacts.title') }}</li>
            </ul>
        </nav>
    </div>

    <section class="contacts">
        <div class="container">
            <div class="contacts__inner">
                <div class="contacts__info">

                    <div class="contacts__group">
                        <h2 class="contacts__title">{{ __('app.contacts.title') }}</h2>
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
                        <h2 class="contacts__title">{{ __('app.contacts.address.title') }}</h2>
                        <ul class="contacts__list">
                            @if(!empty($c?->address))
                                <li class="contacts__item">
                                    <a href="#!" class="contacts__link">{{ $c->address }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="contacts__group">
                        <h2 class="contacts__title">{{ __('app.footer.socials') }}</h2>
                        <div class="footer__socials">
                            <a href="{{ $c?->whatsapp_link ?: '#!' }}" target="_blank"><img
                                    src="{{ asset('assets/icons/whatsapp.svg') }}" alt="whatsapp"></a>
                            <a href="{{ $c?->youtube_link  ?: '#!' }}" target="_blank"><img
                                    src="{{ asset('assets/icons/youtube.svg') }}" alt="youtube"></a>
                            <a href="{{ $c?->telegram_link ?: '#!' }}" target="_blank"><img
                                    src="{{ asset('assets/icons/telegram.svg') }}" alt="telegram"></a>
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

    <section class="form-section form-section-contacts"
             style="background-image: url('{{ asset('assets/img/form/form-bg.png') }}');">
        <img class="form-section-image form-section-image-left" src="{{ asset('assets/img/form/1.png') }}" alt="hand">
        <img class="form-section-image form-section-image-right" src="{{ asset('assets/img/form/2.png') }}" alt="pul">
        <div class="container">
            <h2 class="form-section__title title">{{ __('app.contacts.title') }}</h2>
            <div class="form-section__col">
                @php
                    try {
                        $c = $contacts ?? ContactSetting::getCached();
                    } catch (\Throwable $e) {
                        $c = null;
                    }
                @endphp
                <div class="form-section__socials">
                    <a class="form-section__social" href="{{ $c?->whatsapp_link ?: '#!' }}" target="_blank">
                        <img src="{{ asset('assets/icons/whatsapp.svg') }}" alt="whatsapp">
                    </a>
                    <a class="form-section__social" href="{{ $c?->youtube_link ?: '#!' }}" target="_blank">
                        <img src="{{ asset('assets/icons/youtube.svg') }}" alt="youtube">
                    </a>
                    <a class="form-section__social" href="{{ $c?->telegram_link ?: '#!' }}" target="_blank">
                        <img src="{{ asset('assets/icons/telegram.svg') }}" alt="telegram">
                    </a>
                </div>
                <form class="form" method="POST" action="{{ route('leads.store') }}">
                    @csrf
                    <input placeholder="{{ __('app.form.name') }}" class="form__input" name="name" autocomplete="name"
                           id="name" type="text" required>
                    <input placeholder="{{ __('app.form.phone.ph') }}" class="form__input" name="phone"
                           autocomplete="tel" id="phone" type="tel" required>
                    <textarea placeholder="{{ __('app.form.message') }}" class="form__area" name="message"
                              id="message"></textarea>
                    <button class="form__btn" type="submit">{{ __('app.form.send') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
