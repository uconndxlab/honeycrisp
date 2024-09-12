<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRule extends Model
{

    protected $fillable = [
        'product_id',
        'start',
        'end',
        'day'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
