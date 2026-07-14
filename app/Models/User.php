<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses() { return $this->hasMany(UserAddress::class); }
    public function shop() { return $this->hasOne(Shop::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function wishlists() { return $this->hasMany(Wishlist::class); }
    public function supportTickets() { return $this->hasMany(SupportTicket::class); }
    public function socialLogins() { return $this->hasMany(SocialLogin::class); }
    public function affiliateUser() { return $this->hasOne(AffiliateUser::class); }
    public function classifiedProducts() { return $this->hasMany(ClassifiedProduct::class); }

}
