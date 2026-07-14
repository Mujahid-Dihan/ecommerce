<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'thumbnail',
        'images',
        'product_type',
        'b2b_wholesale',
        'status',
        'specifications',
    ];

    protected $casts = [
        'specifications' => 'array',
        'images' => 'array',
    ];
    public function shop() { return $this->belongsTo(Shop::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function wholesalePrices() { return $this->hasMany(WholesalePrice::class); }
    public function digitalAssets() { return $this->hasMany(DigitalAsset::class); }
    public function auctions() { return $this->hasMany(Auction::class); }

}
