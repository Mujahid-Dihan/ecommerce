<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants', 'shop']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category_id', 'brand_id', 'status', 'product_type']),
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'product_type' => 'required|in:physical,digital',
            'status' => 'required|in:active,draft,inactive',
            'is_featured' => 'boolean',
            'todays_deal' => 'boolean',
            'b2b_wholesale' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.variant_string' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'specifications' => 'nullable|array',
            'digital_file' => 'nullable|file|max:51200',
            'download_limit' => 'nullable|integer|min:0',
        ]);

        // For admin products, use shop_id = 1 (admin shop) or create one
        $shop = \App\Models\Shop::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['name' => 'In-house', 'address' => 'Admin Store']
        );

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $product = Product::create([
            'shop_id' => $shop->id,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'thumbnail' => $thumbnailPath,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'product_type' => $validated['product_type'],
            'is_featured' => $validated['is_featured'] ?? false,
            'todays_deal' => $validated['todays_deal'] ?? false,
            'b2b_wholesale' => $validated['b2b_wholesale'] ?? false,
            'status' => $validated['status'],
            'specifications' => $validated['specifications'] ?? null,
        ]);

        // Create variants
        foreach ($validated['variants'] as $variantData) {
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_string' => $variantData['variant_string'],
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
            ]);
        }

        // Digital asset
        if ($validated['product_type'] === 'digital' && $request->hasFile('digital_file')) {
            \App\Models\DigitalAsset::create([
                'product_id' => $product->id,
                'file_path' => $request->file('digital_file')->store('digital_products', 'local'),
                'download_limit' => $validated['download_limit'] ?? null,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'digitalAssets']);
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'product_type' => 'required|in:physical,digital',
            'status' => 'required|in:active,draft,inactive',
            'is_featured' => 'boolean',
            'todays_deal' => 'boolean',
            'b2b_wholesale' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.variant_string' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'specifications' => 'nullable|array',
            'digital_file' => 'nullable|file|max:51200',
            'download_limit' => 'nullable|integer|min:0',
        ]);

        $product->fill([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'product_type' => $validated['product_type'],
            'is_featured' => $validated['is_featured'] ?? false,
            'todays_deal' => $validated['todays_deal'] ?? false,
            'b2b_wholesale' => $validated['b2b_wholesale'] ?? false,
            'status' => $validated['status'],
            'specifications' => $validated['specifications'] ?? null,
        ]);

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('products', 'public');
        }

        if ($request->hasFile('images')) {
            $imagePaths = $product->images ?? [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $product->images = $imagePaths;
        }

        $product->save();

        // Update variants
        $incomingVariantStrings = array_column($validated['variants'], 'variant_string');
        $product->variants()->whereNotIn('variant_string', $incomingVariantStrings)->delete();

        foreach ($validated['variants'] as $variantData) {
            $product->variants()->updateOrCreate(
                ['product_id' => $product->id, 'variant_string' => $variantData['variant_string']],
                ['sku' => $variantData['sku'] ?? null, 'price' => $variantData['price'], 'stock' => $variantData['stock']]
            );
        }

        // Digital asset
        if ($validated['product_type'] === 'digital' && $request->hasFile('digital_file')) {
            $digitalAsset = \App\Models\DigitalAsset::where('product_id', $product->id)->first();
            $data = [
                'file_path' => $request->file('digital_file')->store('digital_products', 'local'),
                'download_limit' => $validated['download_limit'] ?? null,
            ];
            if ($digitalAsset) {
                $digitalAsset->update($data);
            } else {
                $data['product_id'] = $product->id;
                \App\Models\DigitalAsset::create($data);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
