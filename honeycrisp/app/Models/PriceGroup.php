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
}
