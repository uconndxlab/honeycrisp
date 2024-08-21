<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // isActive, based on start_date and end_date
    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->start_date <= $now && $now <= $this->end_date;
    }
}
