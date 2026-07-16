<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return Inertia::render('Seller/Dashboard', [
                'stats' => [
                    'totalSales' => '0.00',
                    'totalOrders' => 0,
                    'activeProducts' => 0,
                    'draftProducts' => 0,
                    'pendingOrders' => 0,
                    'confirmedOrders' => 0,
                    'shippedOrders' => 0,
                    'deliveredOrders' => 0,
                    'cancelledOrders' => 0,
                    'walletBalance' => '0.00',
                ],
                'recentOrders' => [],
                'hasShop' => false,
            ]);
        }

        $totalSales = SubOrder::where('shop_id', $shop->id)->sum('sub_total');
        $totalOrders = SubOrder::where('shop_id', $shop->id)->count();
        $activeProducts = Product::where('shop_id', $shop->id)->where('status', 'active')->count();
        $draftProducts = Product::where('shop_id', $shop->id)->where('status', 'draft')->count();

        $pendingOrders = SubOrder::where('shop_id', $shop->id)->where('order_status', 'pending')->count();
        $confirmedOrders = SubOrder::where('shop_id', $shop->id)->where('order_status', 'confirmed')->count();
        $shippedOrders = SubOrder::where('shop_id', $shop->id)->where('order_status', 'shipped')->count();
        $deliveredOrders = SubOrder::where('shop_id', $shop->id)->where('order_status', 'delivered')->count();
        $cancelledOrders = SubOrder::where('shop_id', $shop->id)->where('order_status', 'cancelled')->count();

        $recentOrders = SubOrder::where('shop_id', $shop->id)
            ->with(['order.user'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($subOrder) {
                return [
                    'id' => $subOrder->order_id,
                    'sub_order_id' => $subOrder->id,
                    'customer' => $subOrder->order->user->name ?? 'Guest',
                    'sub_total' => $subOrder->sub_total,
                    'shipping_cost' => $subOrder->shipping_cost,
                    'status' => $subOrder->order_status,
                    'created_at' => $subOrder->created_at->format('d M Y, h:i A'),
                ];
            });

        return Inertia::render('Seller/Dashboard', [
            'stats' => [
                'totalSales' => number_format($totalSales, 2),
                'totalOrders' => $totalOrders,
                'activeProducts' => $activeProducts,
                'draftProducts' => $draftProducts,
                'pendingOrders' => $pendingOrders,
                'confirmedOrders' => $confirmedOrders,
                'shippedOrders' => $shippedOrders,
                'deliveredOrders' => $deliveredOrders,
                'cancelledOrders' => $cancelledOrders,
                'walletBalance' => '0.00',
            ],
            'recentOrders' => $recentOrders,
            'hasShop' => true,
        ]);
    }
}
