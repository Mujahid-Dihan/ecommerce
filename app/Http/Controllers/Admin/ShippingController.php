<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingController extends Controller
{
    public function index()
    {
        $methods = ShippingMethod::latest()->paginate(20);
        return Inertia::render('Admin/Shipping/Index', ['methods' => $methods]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        ShippingMethod::create($validated);
        return redirect()->back()->with('success', 'Shipping method added.');
    }

    public function update(Request $request, ShippingMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $method->update($validated);
        return redirect()->back()->with('success', 'Shipping method updated.');
    }

    public function destroy(ShippingMethod $method)
    {
        $method->delete();
        return redirect()->back()->with('success', 'Shipping method deleted.');
    }
}
