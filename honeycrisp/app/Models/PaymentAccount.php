<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_number',
        'account_type',
        'account_owner',
        'expiration_date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function owner()
    {
        $user = User::find($this->account_owner);
        return $user;
    }

  
    public static function types()
    {
        // get them via the enum options
        return [
            'kfs',
            'uch',
            'other'
        ];
    }
}
