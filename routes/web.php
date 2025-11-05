<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/',        [PageController::class, 'show'])->defaults('template','home')->name('home');
Route::get('/about',   [PageController::class, 'show'])->defaults('template','about')->name('about');
Route::get('/policy', [PageController::class, 'show'])->defaults('template','privacy')->name('privacy');
Route::view('/contacts', 'pages.contacts')->name('contact');

Route::get('/catalog',        [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/order', [OrderController::class, 'store'])->name('cart.store');

Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
