<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(['email' => 'admin@ryans.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'is_verified' => true,
        ]);

        // Create Seller User
        $seller = User::firstOrCreate(['email' => 'seller@ryans.com'], [
            'name' => 'Seller User',
            'password' => bcrypt('password'),
            'user_type' => 'seller',
            'is_verified' => true,
        ]);

        // Create Customer User
        $customer = User::firstOrCreate(['email' => 'customer@example.com'], [
            'name' => 'Customer User',
            'password' => bcrypt('password'),
            'user_type' => 'customer',
            'is_verified' => true,
        ]);

        // Create a Shop for the Seller
        $shop = \App\Models\Shop::firstOrCreate(['user_id' => $seller->id], [
            'name' => 'Ryans Tech Store',
            'rating' => 4.8,
        ]);

        // Categories
        $laptops = \App\Models\Category::firstOrCreate(['slug' => 'laptops'], ['name' => 'Laptops']);
        $accessories = \App\Models\Category::firstOrCreate(['slug' => 'accessories'], ['name' => 'Accessories']);

        // Brands
        $apple = \App\Models\Brand::firstOrCreate(['slug' => 'apple'], ['name' => 'Apple']);
        $logitech = \App\Models\Brand::firstOrCreate(['slug' => 'logitech'], ['name' => 'Logitech']);

        // Products
        \App\Models\Product::firstOrCreate(['slug' => 'macbook-pro-m2'], [
            'shop_id' => $shop->id,
            'category_id' => $laptops->id,
            'brand_id' => $apple->id,
            'name' => 'MacBook Pro M2 14-inch',
            'description' => 'Powerful laptop with M2 Pro chip.',
            'product_type' => 'physical',
            'is_featured' => true,
            'todays_deal' => true,
        ]);

        \App\Models\Product::firstOrCreate(['slug' => 'mx-master-3s'], [
            'shop_id' => $shop->id,
            'category_id' => $accessories->id,
            'brand_id' => $logitech->id,
            'name' => 'Logitech MX Master 3S',
            'description' => 'Ergonomic wireless mouse.',
            'product_type' => 'physical',
            'is_featured' => false,
            'todays_deal' => true,
        ]);
    }
}
