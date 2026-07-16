<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->where('status', 'active')->latest()->take(50)->get();
        $customers = User::where('user_type', 'customer')->select('id', 'name', 'email')->limit(100)->get();

        return Inertia::render('Admin/Pos/Index', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $search = $request->search;
        $products = Product::with('variants')
            ->where('status', 'active')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('variants', fn ($vq) => $vq->where('sku', 'like', "%{$search}%"));
            })
            ->take(20)->get();

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_banking',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $subTotal = 0;
        foreach ($validated['items'] as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        $discount = $validated['discount'] ?? 0;
        $grandTotal = max($subTotal - $discount, 0);

        $order = Order::create([
            'user_id' => $validated['customer_id'] ?? null,
            'sub_total' => $subTotal,
            'shipping_cost' => 0,
            'coupon_discount' => $discount,
            'grand_total' => $grandTotal,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'order_source' => 'pos',
        ]);

        // Create a single sub_order for POS (in-house, shop_id=1)
        $subOrder = SubOrder::create([
            'order_id' => $order->id,
            'shop_id' => 1,
            'sub_total' => $subTotal,
            'order_status' => 'delivered',
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'sub_order_id' => $subOrder->id,
                'product_id' => $item['product_id'],
                'variant' => $item['variant_id'] ? ProductVariant::find($item['variant_id'])?->variant_string : null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Deduct stock
            if ($item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $variant->decrement('stock', $item['quantity']);
                }
            }
        }

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
