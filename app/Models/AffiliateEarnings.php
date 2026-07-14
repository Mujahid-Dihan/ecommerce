<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateEarnings extends Model
{
    //

    public function affiliateUser() { return $this->belongsTo(AffiliateUser::class); }
    public function order() { return $this->belongsTo(Order::class); }

}
