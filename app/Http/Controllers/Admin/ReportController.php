<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\Wishlist;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Order::query();
        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to')) $query->whereDate('created_at', '<=', $request->to);

        $stats = [
            'total_orders' => (clone $query)->count(),
            'total_revenue' => (clone $query)->sum('grand_total'),
            'avg_order_value' => (clone $query)->avg('grand_total'),
        ];

        $recentOrders = (clone $query)->with('user')->latest()->take(20)->get();

        return Inertia::render('Admin/Reports/Sales', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'filters' => $request->only(['from', 'to']),
        ]);
    }

    public function stock()
    {
        $lowStock = Product::with('variants')
            ->whereHas('variants', fn ($q) => $q->where('stock', '<=', 5)->where('stock', '>', 0))
            ->take(50)->get();

        $outOfStock = Product::with('variants')
            ->whereHas('variants', fn ($q) => $q->where('stock', '<=', 0))
            ->take(50)->get();

        return Inertia::render('Admin/Reports/Stock', [
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
        ]);
    }

    public function wishlist()
    {
        $wishlisted = Wishlist::select('product_id')
            ->selectRaw('COUNT(*) as wishlist_count')
            ->groupBy('product_id')
            ->orderByDesc('wishlist_count')
            ->take(50)
            ->get()
            ->map(function ($w) {
                $w->product = Product::find($w->product_id);
                return $w;
            });

        return Inertia::render('Admin/Reports/Wishlist', ['wishlisted' => $wishlisted]);
    }

    public function commission()
    {
        $sellerData = SubOrder::with('shop.user')
            ->selectRaw('shop_id, SUM(sub_total) as total_sales')
            ->groupBy('shop_id')
            ->orderByDesc('total_sales')
            ->take(50)->get();

        return Inertia::render('Admin/Reports/Commission', ['sellerData' => $sellerData]);
    }
}
