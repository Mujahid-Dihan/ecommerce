<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = RefundRequest::with(['user', 'orderItem.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $refunds = $query->latest()->paginate(20)->withQueryString();

        return Inertia::render('Admin/Refunds/Index', [
            'refunds' => $refunds,
            'filters' => $request->only(['status']),
        ]);
    }

    public function approve(RefundRequest $refund)
    {
        $refund->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Refund approved.');
    }

    public function reject(RefundRequest $refund)
    {
        $refund->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Refund rejected.');
    }
}
