<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class); }
    public function shop() { return $this->belongsTo(Shop::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function tracking() { return $this->hasMany(OrderTracking::class); }

}
