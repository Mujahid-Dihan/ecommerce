<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
    //

    public function user() { return $this->belongsTo(User::class); }
    public function links() { return $this->hasMany(AffiliateLink::class); }
    public function earnings() { return $this->hasMany(AffiliateEarnings::class); }

}
