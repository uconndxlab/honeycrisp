<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Facility extends Model
{
    use HasFactory;
    // name, description, email, address, abbreviation, recharge_account
    protected $fillable = ['name', 'description', 'email', 'address', 'abbreviation', 'recharge_account'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    
}
