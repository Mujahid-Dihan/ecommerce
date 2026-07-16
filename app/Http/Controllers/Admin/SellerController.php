<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'seller')->with('shop')->withCount(['shop as products_count' => function ($q) {
            // not directly possible, we'll handle in the view
        }]);

        // Simplified: just load sellers with shops
        $query = User::where('user_type', 'seller')->with('shop');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('shop', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->whereHas('shop', fn ($q) => $q->where('is_approved', true));
            } else {
                $query->whereHas('shop', fn ($q) => $q->where('is_approved', false));
            }
        }

        $sellers = $query->latest()->paginate(20)->withQueryString();

        return Inertia::render('Admin/Sellers/Index', [
            'sellers' => $sellers,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(User $seller)
    {
        $seller->load('shop');
        $shop = $seller->shop;

        $stats = [];
        if ($shop) {
            $stats = [
                'total_products' => Product::where('shop_id', $shop->id)->count(),
                'total_orders' => $shop->subOrders()->count(),
                'total_revenue' => $shop->subOrders()->sum('sub_total'),
            ];
        }

        $recentProducts = $shop ? Product::where('shop_id', $shop->id)->latest()->take(10)->get() : [];

        return Inertia::render('Admin/Sellers/Show', [
            'seller' => $seller,
            'stats' => $stats,
            'recentProducts' => $recentProducts,
        ]);
    }

    public function approve(User $seller)
    {
        if ($seller->shop) {
            $seller->shop->update(['is_approved' => true]);
        }
        return redirect()->back()->with('success', 'Seller approved successfully.');
    }

    public function reject(User $seller)
    {
        if ($seller->shop) {
            $seller->shop->update(['is_approved' => false]);
        }
        return redirect()->back()->with('success', 'Seller approval revoked.');
    }

    public function updateCommission(Request $request, User $seller)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($seller->shop) {
            $seller->shop->update(['commission_rate' => $validated['commission_rate']]);
        }

        return redirect()->back()->with('success', 'Commission rate updated.');
    }
}
