<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{

    protected $fillable = [
        'product_id',
        'order_id',
        'reservation_start',
        'reservation_end',
        'reservation_interval',
        'minimum_reservation_time',
        'maximum_reservation_time',
        'actual_start',
        'actual_end',
        'status',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
