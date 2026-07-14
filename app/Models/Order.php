<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    public function user() { return $this->belongsTo(User::class); }
    public function address() { return $this->belongsTo(UserAddress::class, 'shipping_address_id'); }
    public function subOrders() { return $this->hasMany(SubOrder::class); }

}
