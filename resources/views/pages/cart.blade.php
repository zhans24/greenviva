{{-- resources/views/pages/cart.blade.php --}}
@extends('layouts.app')

@section('title', 'Корзина')
@section('main_classes', 'page-main')

@section('content')
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <ul>
                <li><a class="breadcrumbs__home" href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumbs__link" aria-current="page">/ Корзина</li>
            </ul>
        </nav>
    </div>

    <section class="cart-wrapper">
        <div class="container">
            <div class="cart-col">
                <div class="cart">
                    <h2 class="cart__title title">Корзина</h2>
                    {{-- ПУСТОЙ UL — JS сам заполнит из localStorage --}}
                    <ul class="cart__list"></ul>
                </div>

                <div class="cart__form">
                    <h2 class="cart__title cart__form-title title">Форма оформления заказа</h2>

                    <form class="order-form" id="orderForm">
                        <div class="order-form__group">
                            <label for="name" class="order-form__label">Имя</label>
                            <input type="text" id="name" name="name" class="order-form__input" placeholder="Введите имя"
                                   autocomplete="name" required />
                        </div>

                        <div class="order-form__group">
                            <label for="tel" class="order-form__label">Телефон</label>
                            <input type="tel" id="tel" name="tel" class="order-form__input" placeholder="+7 747 123 45 67"
                                   autocomplete="tel" required />
                        </div>

                        <div class="order-form__group order-form__group--icon">
                            <label for="address" class="order-form__label">Адрес доставки</label>
                            <div class="order-form__input-wrapper">
                                <input type="text" id="address" name="address" class="order-form__input" placeholder="Абай Саина 34"
                                       autocomplete="street-address" required />
                                {{-- иконка лупы/гео по желанию --}}
                            </div>
                        </div>

                        <div class="order-form__group">
                            <textarea id="comment" name="comment" class="order-form__textarea" placeholder="Комментарии" rows="3"></textarea>
                        </div>

                        <button type="submit" class="order-form__button">Отправить заявку</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
