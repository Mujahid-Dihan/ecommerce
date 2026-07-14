<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    //

    public function payments() { return $this->hasMany(SellerSubscriptionPayment::class, 'subscription_id'); }

}
