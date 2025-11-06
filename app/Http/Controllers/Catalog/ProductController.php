<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // /product/{slug}
    public function show(Request $request, string $slug)
    {
        Log::debug('ProductController@show (no-locale)', [
            'app_locale' => app()->getLocale(),
            'slug_raw'   => $slug,
            'url'        => $request->fullUrl(),
        ]);

        return $this->renderProduct($request, $slug);
    }

    // /{locale}/product/{slug}
    public function showLocalized(Request $request, string $locale, string $slug)
    {
        Log::debug('ProductController@showLocalized', [
            'route_locale' => $locale,
            'app_locale'   => app()->getLocale(),
            'slug_raw'     => $slug,
            'url'          => $request->fullUrl(),
        ]);

        return $this->renderProduct($request, $slug);
    }

    private function renderProduct(Request $request, string $slug)
    {
        $slug = trim(Str::lower($slug));

        $product = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name'])
            ->whereRaw('LOWER(TRIM(slug)) = ?', [$slug])
            ->firstOrFail();

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

        return view('pages.product', compact('product','effectivePrice','gallery','certs','related'));
    }
}
