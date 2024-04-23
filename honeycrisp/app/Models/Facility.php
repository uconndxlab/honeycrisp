<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    // name, description, email, address, abbreviation, recharge_account
    protected $fillable = ['name', 'description', 'email', 'address', 'abbreviation', 'recharge_account'];
}
