<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'variants'])->where('status', 'active');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = [$category->id];
                if ($category->subcategories) {
                    $categoryIds = array_merge($categoryIds, $category->subcategories->pluck('id')->toArray());
                }
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Frontend/Shop/Index', [
            'products' => $products,
            'categories' => Category::whereNull('parent_id')->with('subcategories')->get(),
            'brands' => Brand::all(),
            'filters' => $request->only(['category', 'brand', 'search']),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['brand', 'category', 'variants', 'digitalAssets'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $attributes = Attribute::with('values')->get();

        $relatedProducts = Product::with(['brand', 'category', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return Inertia::render('Frontend/Products/Show', [
            'product' => $product,
            'attributes' => $attributes,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
