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
        // owner is pivot on user
        return $this->users()->wherePivot('role', 'owner')->first();
    }

    public function account_manager()
    {
        // account manager is pivot on user
        return $this->users()->wherePivot('role', 'account_manager')->first();
    }

    public function fiscal_officer()
    {
        // fiscal officer is pivot on user
        return $this->users()->wherePivot('role', 'fiscal_officer')->first();
    }

    public function authorized_users()
    {
        // authorized users are pivot on user in the payment_account_user table
        return $this->belongsToMany(User::class)
            ->wherePivot('role', 'authorized_user')
            ->withTimestamps();
    }

    public function formatted()
    {
        return strtoupper($this->account_type) . '-' . $this->account_number;
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
