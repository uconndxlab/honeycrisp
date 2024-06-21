<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'unit_price_internal',
        'unit_price_external_nonprofit',
        'unit_price_external_forprofit',
        'requires_approval',
        'is_active',
        'is_deleted',
        'image_url',
        'tags',
        'facility_id',
        'category_id',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
