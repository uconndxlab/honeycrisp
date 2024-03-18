<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    /**
     * Get the products associated with the facility.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the services associated with the facility.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
