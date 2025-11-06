{{-- resources/views/pages/cart.blade.php --}}
@extends('layouts.app')

@section('title', __('app.cart.title'))
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="{{ __('app.breadcrumbs.aria') }}">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">{{ __('app.breadcrumbs.home') }}</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ {{ __('app.breadcrumbs.cart') }}</li>
            </ul>
        </nav>
    </div>

    <section class="cart-wrapper">
        <div class="container">
            <div class="cart-col">
                <div class="cart">
                    <h2 class="cart__title title">{{ __('app.cart.title') }}</h2>
                    {{-- ПУСТОЙ UL — JS сам заполнит из localStorage --}}
                    <ul class="cart__list"></ul>
                </div>

                <div class="cart__form">
                    <h2 class="cart__title cart__form-title title">{{ __('app.cart.form.title') }}</h2>

                    <form class="order-form" id="orderForm">
                        <div class="order-form__group">
                            <label for="name" class="order-form__label">{{ __('app.form.name') }}</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="order-form__input"
                                placeholder="{{ __('app.form.name.ph') }}"
                                autocomplete="name"
                                required
                            />
                        </div>

                        <div class="order-form__group">
                            <label for="tel" class="order-form__label">{{ __('app.form.phone') }}</label>
                            <input
                                type="tel"
                                id="tel"
                                name="tel"
                                class="order-form__input"
                                placeholder="{{ __('app.form.phone.ph') }}"
                                autocomplete="tel"
                                required
                            />
                        </div>

                        <div class="order-form__group order-form__group--icon">
                            <label for="address" class="order-form__label">{{ __('app.form.address') }}</label>
                            <div class="order-form__input-wrapper">
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    class="order-form__input"
                                    placeholder="{{ __('app.form.address.ph') }}"
                                    autocomplete="street-address"
                                    required
                                />
                                {{-- иконка по желанию --}}
                            </div>
                        </div>

                        <div class="order-form__group">
                            <textarea
                                id="comment"
                                name="comment"
                                class="order-form__textarea"
                                placeholder="{{ __('app.form.comment.ph') }}"
                                rows="3"
                            ></textarea>
                        </div>

                        <button type="submit" class="order-form__button">
                            {{ __('app.form.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
