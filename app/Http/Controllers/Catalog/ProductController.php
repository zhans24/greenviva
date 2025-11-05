<?php
// app/Http/Controllers/Catalog/ProductController.php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name'])
            ->where('slug', $slug)
            ->firstOrFail();

        // price (скидка) или old_price
        $effectivePrice = $product->price ?? $product->old_price;

        $gallery = $product->getMedia('gallery');
        $certs   = $product->getMedia('certificates');

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->where('is_available', true)
            ->where('id', '!=', $product->id)
            ->latest('updated_at')
            ->take(12)
            ->get();

        // ВАЖНО: правильный путь к вьюхе pages/product.blade.php
        return view('pages.product', [
            'product'        => $product,
            'effectivePrice' => $effectivePrice,
            'gallery'        => $gallery,
            'certs'          => $certs,
            'related'        => $related,
        ]);
    }
}
