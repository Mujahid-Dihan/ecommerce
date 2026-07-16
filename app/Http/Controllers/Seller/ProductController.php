<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a shop to manage products.');
        }

        $products = Product::where('shop_id', $shop->id)
            ->with(['category', 'brand', 'variants'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Seller/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get(); // Get subcategories for easier assignment
        if ($categories->isEmpty()) {
            $categories = Category::all();
        }

        $brands = Brand::all();
        $attributes = \App\Models\Attribute::with('values')->get();

        return Inertia::render('Seller/Products/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return redirect()->back()->with('error', 'You do not have a shop associated with your account.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'product_type' => 'required|in:physical,digital',
            'b2b_wholesale' => 'boolean',
            'status' => 'required|in:active,draft,inactive',
            'variants' => 'required|array|min:1',
            'variants.*.variant_string' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'digital_file' => 'nullable|required_if:product_type,digital|file|max:51200',
            'download_limit' => 'nullable|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.*.group' => 'required|string|max:255',
            'specifications.*.key' => 'required|string|max:255',
            'specifications.*.value' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
        ]);

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

        // Generate a unique slug
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create product
        $product = Product::create([
            'shop_id' => $shop->id,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'thumbnail' => $thumbnailPath,
            'product_type' => $validated['product_type'],
            'b2b_wholesale' => $validated['b2b_wholesale'] ?? false,
            'status' => $validated['status'],
            'specifications' => $validated['specifications'] ?? null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
        ]);

        // Create product variants
        foreach ($validated['variants'] as $variantData) {
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_string' => $variantData['variant_string'],
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
            ]);
        }

        // Handle Digital Asset if product type is digital
        if ($validated['product_type'] === 'digital' && $request->hasFile('digital_file')) {
            $digitalFilePath = $request->file('digital_file')->store('digital_products', 'local');
            \App\Models\DigitalAsset::create([
                'product_id' => $product->id,
                'file_path' => $digitalFilePath,
                'download_limit' => $validated['download_limit'] ?? null,
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // View product detail - not typically needed for seller, but good to have.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        $shop = $request->user()->shop;

        if (!$shop || $product->shop_id !== $shop->id) {
            abort(403, 'Unauthorized access.');
        }

        $product->load(['variants', 'digitalAssets']);

        $categories = Category::whereNotNull('parent_id')->get();
        if ($categories->isEmpty()) {
            $categories = Category::all();
        }

        $brands = Brand::all();
        $attributes = \App\Models\Attribute::with('values')->get();

        return Inertia::render('Seller/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $shop = $request->user()->shop;

        if (!$shop || $product->shop_id !== $shop->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'product_type' => 'required|in:physical,digital',
            'b2b_wholesale' => 'boolean',
            'status' => 'required|in:active,draft,inactive',
            'variants' => 'required|array|min:1',
            'variants.*.variant_string' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'digital_file' => 'nullable|file|max:51200',
            'download_limit' => 'nullable|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.*.group' => 'required|string|max:255',
            'specifications.*.key' => 'required|string|max:255',
            'specifications.*.value' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product->fill([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'product_type' => $validated['product_type'],
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

        // Update basic info
        $product->save();

        // Update product variants
        $incomingVariantStrings = array_column($validated['variants'], 'variant_string');

        // Delete variants that are no longer present
        $product->variants()->whereNotIn('variant_string', $incomingVariantStrings)->delete();

        foreach ($validated['variants'] as $variantData) {
            $product->variants()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'variant_string' => $variantData['variant_string']
                ],
                [
                    'sku' => $variantData['sku'] ?? null,
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ]
            );
        }

        // Handle Digital Asset update
        if ($validated['product_type'] === 'digital') {
            $digitalAsset = \App\Models\DigitalAsset::where('product_id', $product->id)->first();

            $digitalData = [
                'download_limit' => $validated['download_limit'] ?? null,
            ];

            if ($request->hasFile('digital_file')) {
                $digitalData['file_path'] = $request->file('digital_file')->store('digital_products', 'local');
            }

            if ($digitalAsset) {
                $digitalAsset->update($digitalData);
            } else if (isset($digitalData['file_path'])) {
                $digitalData['product_id'] = $product->id;
                \App\Models\DigitalAsset::create($digitalData);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $shop = $request->user()->shop;

        if (!$shop || $product->shop_id !== $shop->id) {
            abort(403, 'Unauthorized access.');
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
    }
}
