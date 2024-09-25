<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentLocation extends Model
{
    use HasFactory;

    protected $table = 'shipment_locations';

    protected $fillable = [
        'order_detail_id',
        'note',
        'address',
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
