<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CartController, OrderController, LeadController, PageController
};
use App\Http\Controllers\Catalog\{
    CatalogController, ProductController
};

/*
|--------------------------------------------------------------------------
| Группа маршрутов без префикса (RU)
|--------------------------------------------------------------------------
*/
Route::middleware('setLocaleFromUrl')->group(function () {
    Route::get('',          [PageController::class, 'show'])->defaults('template','home')->name('home');
    Route::get('about',     [PageController::class, 'show'])->defaults('template','about')->name('about');
    Route::get('policy',    [PageController::class, 'show'])->defaults('template','privacy')->name('privacy');
    Route::view('contacts', 'pages.contacts')->name('contact');

    Route::get('catalog',         [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('catalog/{slug}',  [CatalogController::class, 'category'])->name('catalog.category');

    Route::get('product/{slug}',  [ProductController::class, 'show'])->name('product.show');

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
});

/*
|--------------------------------------------------------------------------
| Группа маршрутов с префиксом (EN, KZ)
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')
    ->where(['locale' => 'kz|en'])
    ->middleware('setLocaleFromUrl')
    ->group(function () {
        Route::get('',          [PageController::class, 'show'])->defaults('template','home')->name('home.localized');
        Route::get('about',     [PageController::class, 'show'])->defaults('template','about')->name('about.localized');
        Route::get('policy',    [PageController::class, 'show'])->defaults('template','privacy')->name('privacy.localized');
        Route::view('contacts', 'pages.contacts')->name('contact.localized');

        Route::get('catalog',         [CatalogController::class, 'index'])->name('catalog.index.localized');
        Route::get('catalog/{slug}',  [CatalogController::class, 'category'])->name('catalog.category.localized');

        Route::get('product/{slug}',  [ProductController::class, 'showLocalized'])->name('product.show.localized');

        Route::get('cart', [CartController::class, 'index'])->name('cart.index.localized');
    });

/*
|--------------------------------------------------------------------------
| Глобальные POST-маршруты
|--------------------------------------------------------------------------
*/
Route::post('cart/order', [OrderController::class, 'store'])->name('cart.store');
Route::post('leads',      [LeadController::class, 'store'])->name('leads.store');
