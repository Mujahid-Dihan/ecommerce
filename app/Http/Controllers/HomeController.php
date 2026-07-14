<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->take(8)->get();
        $topBrands = \App\Models\Brand::take(10)->get();
        
        $featuredProducts = Product::with(['brand', 'category', 'variants'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
            
        $todaysDeals = Product::with(['brand', 'category', 'variants'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'categories' => $categories,
            'topBrands' => $topBrands,
            'featuredProducts' => $featuredProducts,
            'todaysDeals' => $todaysDeals,
        ]);
    }
}
