<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{

    protected $fillable = ['type', 'path'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
