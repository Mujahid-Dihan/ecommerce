<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    //

    public function subOrder() { return $this->belongsTo(SubOrder::class); }

}
