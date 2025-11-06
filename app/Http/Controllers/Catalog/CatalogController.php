<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Brand, Category, Product};
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index(Request $request, string ...$params)
    {

        $locale = $params[0] ?? $request->route('locale') ?? app()->getLocale() ?? 'ru';

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        return view('pages.catalog', compact('categories'));
    }

    // /catalog/{slug}  и  /{locale}/catalog/{slug}
    public function category(Request $request, string ...$params)
    {
        // RU: $params = ['pishhevye-dobavki']
        // KZ/EN: $params = ['kz','pishhevye-dobavki'] / ['en','...']
        [$first, $second] = $params + [null, null];

        if ($second === null) {
            // RU путь (без префикса)
            $locale = 'ru';
            $slug   = $first;
        } else {
            // Локализованный путь
            $locale = $first;
            $slug   = $second;
        }

        $slug = trim(Str::lower($slug));

        $probe = Category::query()->whereRaw('LOWER(TRIM(slug)) = ?', [$slug])->first();

        $category = Category::query()
            ->whereRaw('LOWER(TRIM(slug)) = ?', [$slug])
            ->where('is_active', true)
            ->firstOrFail();

        $selectedBrandIds = collect($request->input('brands', []))
            ->map(fn ($v) => (int) $v)->filter()->values();

        $priceFrom = $request->filled('price_from') ? $request->integer('price_from') : null;
        $priceTo   = $request->filled('price_to')   ? $request->integer('price_to')   : null;

        $brands = Brand::query()
            ->where('is_active', true)
            ->whereExists(function ($q) use ($category) {
                $q->select(DB::raw(1))
                    ->from('products')
                    ->whereColumn('products.brand_id', 'brands.id')
                    ->where('products.category_id', $category->id)
                    ->where('products.is_available', true);
            })
            ->orderBy('created_at')
            ->get(['id', 'name']);

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->when($selectedBrandIds->isNotEmpty(), fn ($q) => $q->whereIn('brand_id', $selectedBrandIds))
            ->when($priceFrom !== null, fn ($q) => $q->whereRaw('COALESCE(price, old_price) >= ?', [$priceFrom]))
            ->when($priceTo   !== null, fn ($q) => $q->whereRaw('COALESCE(price, old_price) <= ?', [$priceTo]))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.category', [
            'category'   => $category,
            'brands'     => $brands,
            'products'   => $products,
            'totalCount' => $products->total(),
            'price_from' => $priceFrom,
            'price_to'   => $priceTo,
            'selected'   => $selectedBrandIds->all(),
        ]);
    }
}
