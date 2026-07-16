<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'customer')->withCount('orders');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(User $customer)
    {
        $customer->loadCount('orders');
        $customer->load(['orders' => fn ($q) => $q->latest()->take(10)]);

        $stats = [
            'total_orders' => $customer->orders_count,
            'total_spent' => Order::where('user_id', $customer->id)->sum('grand_total'),
        ];

        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customer,
            'stats' => $stats,
        ]);
    }

    public function toggleBan(User $customer)
    {
        $customer->update(['banned' => !$customer->banned]);
        $status = $customer->banned ? 'banned' : 'unbanned';
        return redirect()->back()->with('success', "Customer has been {$status}.");
    }
}
