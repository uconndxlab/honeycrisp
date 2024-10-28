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
        'recharge_account',
        'recharge_object_code'
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
        return $this->hasMany(PriceGroup::class)->orderBy('name');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scheduleRules()
    {
        return $this->hasMany(ScheduleRule::class);
    }

    
}
