<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRule extends Model
{

    protected $fillable = [
        'product_id',
        'time_of_day_start',
        'time_of_day_end',
        'day'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
