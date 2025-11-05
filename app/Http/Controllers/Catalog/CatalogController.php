<?php
// app/Http/Controllers/Catalog/CatalogController.php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        // вьюха лежит в resources/views/pages/catalog.blade.php
        return view('pages.catalog', compact('categories'));
    }

    public function category(string $slug, Request $request)
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Бренды из GET
        $selectedBrandIds = collect($request->input('brands', []))
            ->map(fn ($v) => (int) $v)
            ->filter()
            ->values();

        // Цена: берём только если поле реально пришло
        $priceFrom = $request->filled('price_from') ? $request->integer('price_from') : null;
        $priceTo   = $request->filled('price_to')   ? $request->integer('price_to')   : null;

        // Бренды, у которых есть товары в этой категории
        $brands = Brand::query()
            ->where('is_active', true)
            ->whereExists(function ($q) use ($category) {
                $q->select(DB::raw(1))
                    ->from('products')
                    ->whereColumn('products.brand_id', 'brands.id')
                    ->where('products.category_id', $category->id)
                    ->where('products.is_available', true);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        // Товары категории + фильтры
        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->when($selectedBrandIds->isNotEmpty(), fn ($q) => $q->whereIn('brand_id', $selectedBrandIds))
            ->when($priceFrom !== null, fn ($q) => $q->whereRaw('COALESCE(price, old_price) >= ?', [$priceFrom]))
            ->when($priceTo   !== null, fn ($q) => $q->whereRaw('COALESCE(price, old_price) <= ?', [$priceTo]))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $totalCount = $products->total();

        // ВАЖНО: правильный путь к вьюхе pages/category.blade.php
        return view('pages.category', [
            'category'   => $category,
            'brands'     => $brands,
            'products'   => $products,
            'totalCount' => $totalCount,
            'price_from' => $priceFrom,
            'price_to'   => $priceTo,
            'selected'   => $selectedBrandIds->all(),
        ]);
    }
}
