<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerSubscriptionPayment extends Model
{
    //

    public function user() { return $this->belongsTo(User::class); }
    public function subscription() { return $this->belongsTo(SellerSubscription::class, 'subscription_id'); }

}
