<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Seller/Dashboard', [
            'stats' => [
                'totalSales' => 0,
                'totalOrders' => 0,
                'activeProducts' => 0,
            ]
        ]);
    }
}
