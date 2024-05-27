<?php

namespace App\Models;

use App\Models\PaymentAccount;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'role',
        'external_rates',
        'netid',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** belongs to many paymentaccounts pivoting  on role */
    public function paymentAccounts()
    {
        return $this->belongsToMany(PaymentAccount::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /** get owned accounts */
    public function ownedAccounts()
    {
        return PaymentAccount::all()->where('account_owner', $this->id);
    }
}
