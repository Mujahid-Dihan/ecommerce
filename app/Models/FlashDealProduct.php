<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashDealProduct extends Model
{
    protected $guarded = [];

    public function flashDeal() { return $this->belongsTo(FlashDeal::class); }
    public function product() { return $this->belongsTo(Product::class); }

}
