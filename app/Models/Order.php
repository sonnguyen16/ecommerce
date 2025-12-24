<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'total',
        'code',
        'name',
        'phone',
        'address',
        'province',
        'district',
        'ward',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district', 'code');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward', 'code');
    }
}
