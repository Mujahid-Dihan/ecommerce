<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalAsset extends Model
{
    protected $fillable = [
        'product_id',
        'file_path',
        'download_limit',
    ];

    public function product() { return $this->belongsTo(Product::class); }

}
