<?php

return [
    // Навигация
    'nav.catalog'  => 'КАТАЛОГ ПРОДУКЦИИ',
    'nav.about'    => 'О КОМПАНИИ',
    'nav.contacts' => 'КОНТАКТЫ',
    'nav.privacy'  => 'Политика конфиденциальности',

    // Хлебные крошки
    'breadcrumbs.aria'    => 'Хлебные крошки',
    'breadcrumbs.home'    => 'Главная',
    'breadcrumbs.catalog' => 'Каталог продукции',
    'breadcrumbs.cart'    => 'Корзина',

    // Корзина / кнопки
    'cart.title'      => 'Корзина',
    'cart.add'        => 'Добавить в корзину',
    'cart.add_short'  => 'В корзину',
    'cart.form.title' => 'Форма оформления заказа',

    // Формы
    'form.name'        => 'Имя',
    'form.name.ph'     => 'Введите имя',
    'form.phone'       => 'Телефон',
    'form.phone.ph'    => '+7 747 123 45 67',
    'form.address'     => 'Адрес доставки',
    'form.address.ph'  => 'Абай Саина 34',
    'form.comment.ph'  => 'Комментарии',
    'form.message'     => 'Сообщение',
    'form.send'        => 'Отправить',
    'form.submit'      => 'Отправить заявку',

    // Продукт
    'product.sku'            => 'Код товара:',
    'product.in_stock'       => 'В наличии',
    'product.out_of_stock'   => 'Нет в наличии',
    'product.delivery_info'  => 'Информация про доставку и оплату',
    'product.tabs.description'  => 'Описание',
    'product.tabs.composition'  => 'Состав',
    'product.tabs.usage'        => 'Применение',
    'product.tabs.certificates' => 'Сертификаты',
    'product.description_soon'  => 'Описание скоро появится.',
    'product.certificate.alt'   => 'Сертификат',
    'product.certificate.empty' => 'Сертификаты отсутствуют.',
    'product.related'           => 'С этим товаром часто покупают',
    'product.qty.plus'          => 'Плюс',
    'product.qty.minus'         => 'Минус',
    'product.item'              => 'Товар',
    'badge.best_seller'         => 'Хит продаж',

    // Главная / блоки
    'advantages.title'          => 'Преимущества',
    'advantages.items.0.title'  => 'Натуральность',
    'advantages.items.1.title'  => 'Сертификация',
    'advantages.items.2.title'  => 'Эффективность',

    'reviews.title' => 'Отзывы клиентов',
    'reviews.guest' => 'Гость',
    'popular.title' => 'Популярные товары',
    'banners.title' => 'Акции',

    // Контакты
    'contacts.title'        => 'Контакты',
    'contacts.address.title'=> 'Адрес',

    // Футер
    'footer.contacts' => 'Контакты',
    'footer.socials'  => 'Социальные сети',

    // Общие
    'privacy.title' => 'Политика конфиденциальности',
    'privacy.empty' => 'Содержимое политики ещё не добавлено.',
    'common.na'     => '—',

    // Валюта
    'currency' => 'T', // визуально одинаково с ₸, можно сменить на '₸'

    'cart' => [
        'title'      => 'Корзина',
        'add'        => 'Добавить в корзину',
        'add_short'  => 'В корзину',
        'in_cart'    => 'В корзине',
        'empty'      => 'Ваша корзина пуста',
        'code'       => 'Код товара',
        'currency'   => 'T',
        'delete'     => 'Удалить товар',
        'minus_aria' => 'Минус',
        'plus_aria'  => 'Плюс',
    ],

    'toast' => [
        // корзина / заказ
        'added'         => 'Товар добавлен в корзину',
        'cart_empty'    => 'Корзина пуста',
        'parse_error'   => 'Не удалось распознать товары',
        'fill_required' => 'Заполните имя, телефон и адрес',
        'order_error'   => 'Ошибка оформления заказа',
        'network_error' => 'Ошибка сети',
        'order_ok' => '✅ Заказ принят! № :number',

        // лид-форма
        'lead_fill_required' => 'Заполните имя и телефон',
        'lead_error'         => 'Ошибка отправки заявки',
        'lead_success'       => '✅ Заявка отправлена! Менеджер свяжется с вами.',
    ],

    'catalog' => [
        'title'=>'Каталог продукции',
        'products_count' => ':count товаров',
        'filter'         => 'Фильтр',
        'brand'          => 'Бренд',
        'price'          => 'Цена',
        'price_from'     => 'От',
        'price_to'       => 'До',
        'show'           => 'Показать',
        'reset'          => 'Сбросить',
        'not_found'      => 'Товары не найдены',
    ],
];
