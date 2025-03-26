<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'province',
        'district',
        'ward',
        'gender',
        'birthday',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function shop(){
        return $this->hasOne(Shop::class);
    }

    /**
     * Kiểm tra xem user có phải là khách hàng không
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 2;
    }

    public function ward(){
        return $this->belongsTo(Ward::class, 'ward', 'code');
    }

    public function district(){
        return $this->belongsTo(District::class, 'district', 'code');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'province', 'code');
    }
}
