<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::sum('grand_total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('user_type', 'customer')->count();
        $totalSellers = User::where('user_type', 'seller')->count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();

        $pendingOrders = SubOrder::where('order_status', 'pending')->count();
        $confirmedOrders = SubOrder::where('order_status', 'confirmed')->count();
        $shippedOrders = SubOrder::where('order_status', 'shipped')->count();
        $deliveredOrders = SubOrder::where('order_status', 'delivered')->count();
        $cancelledOrders = SubOrder::where('order_status', 'cancelled')->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'subOrders'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->name ?? 'Guest',
                    'email' => $order->user->email ?? '',
                    'grand_total' => $order->grand_total,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'source' => $order->source,
                    'status' => $order->subOrders->first()?->order_status ?? 'pending',
                    'items_count' => $order->subOrders->count(),
                    'created_at' => $order->created_at->format('d M Y, h:i A'),
                ];
            });

        // Low stock products
        $lowStockProducts = Product::with(['variants', 'category'])
            ->whereHas('variants', function ($q) {
                $q->where('stock', '<=', 5);
            })
            ->take(5)
            ->get()
            ->map(function ($product) {
                $lowVariant = $product->variants->sortBy('stock')->first();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? '-',
                    'stock' => $lowVariant?->stock ?? 0,
                    'sku' => $lowVariant?->sku ?? '-',
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalSales' => number_format($totalSales, 2),
                'totalOrders' => $totalOrders,
                'totalProducts' => $totalProducts,
                'totalCustomers' => $totalCustomers,
                'totalSellers' => $totalSellers,
                'totalCategories' => $totalCategories,
                'totalBrands' => $totalBrands,
                'pendingOrders' => $pendingOrders,
                'confirmedOrders' => $confirmedOrders,
                'shippedOrders' => $shippedOrders,
                'deliveredOrders' => $deliveredOrders,
                'cancelledOrders' => $cancelledOrders,
            ],
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
