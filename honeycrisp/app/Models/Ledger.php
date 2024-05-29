<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id', 'title', 'description', 'status', 'date'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
