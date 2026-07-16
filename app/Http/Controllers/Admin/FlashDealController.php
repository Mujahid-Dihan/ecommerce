<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlashDealController extends Controller
{
    public function index()
    {
        $deals = FlashDeal::with('products.product')->latest()->paginate(15);
        return Inertia::render('Admin/FlashDeals/Index', ['deals' => $deals]);
    }

    public function create()
    {
        $products = Product::where('status', 'active')->select('id', 'name')->get();
        return Inertia::render('Admin/FlashDeals/Create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'banner' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.discount' => 'required|numeric|min:0',
            'products.*.discount_type' => 'required|in:percentage,fixed',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('flash_deals', 'public');
        }

        $deal = FlashDeal::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'banner' => $bannerPath,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $p) {
                FlashDealProduct::create([
                    'flash_deal_id' => $deal->id,
                    'product_id' => $p['product_id'],
                    'discount' => $p['discount'],
                    'discount_type' => $p['discount_type'],
                ]);
            }
        }

        return redirect()->route('admin.flash-deals.index')->with('success', 'Flash deal created.');
    }

    public function destroy(FlashDeal $flashDeal)
    {
        $flashDeal->delete();
        return redirect()->back()->with('success', 'Flash deal deleted.');
    }
}
