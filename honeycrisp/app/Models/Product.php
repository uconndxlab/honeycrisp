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

    public function priceGroups()
    {
        return $this->hasMany(PriceGroup::class);
    }

    public function getActivePrice($type)
    {
        return $this->priceGroups()
            ->where('type', $type)
            ->where(function ($query) {
                $query->where('start_date', '<=', now())
                    ->orWhereNull('start_date');
            })
            ->where(function ($query) {
                $query->where('end_date', '>=', now())
                    ->orWhereNull('end_date');
            })
            ->first();
    }
}
