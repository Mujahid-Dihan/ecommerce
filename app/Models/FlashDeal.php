<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{
    //

    public function products() { return $this->hasMany(FlashDealProduct::class); }

}
