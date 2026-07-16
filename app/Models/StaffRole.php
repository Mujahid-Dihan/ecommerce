<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRole extends Model
{
    protected $guarded = [];
    protected $casts = ['permissions' => 'array'];

    public function users() { return $this->belongsToMany(User::class, 'staff_role_user'); }
}
