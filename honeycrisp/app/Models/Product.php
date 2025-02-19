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
        'recharge_object_code',
        'purchase_price',
        'size',
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
        return $this->hasMany(Reservation::class)->with(['order' => function ($query) {
            $query->with('paymentAccount');
        }]);
    }

    public function scheduleRules()
    {
        return $this->hasMany(ScheduleRule::class);
    }

    public function scheduleRulesForDay($date)
    {
        $dayOfWeek = strtolower((new \DateTime($date))->format('l')); // e.g., 'monday'
        return $this->scheduleRules()->where('day', $dayOfWeek)->get();
    }

    public function isReservable($start, $end)
    {
        
        $dayOfWeek = strtolower($start->format('l')); // e.g., 'monday'
        $rules = $this->scheduleRules()->where('day', $dayOfWeek)->get();

        foreach ($rules as $rule) {
            $ruleStart = new \DateTime($rule->time_of_day_start);
            $ruleEnd = new \DateTime($rule->time_of_day_end);

            if (
                $start->format('H:i') >= $ruleStart->format('H:i') &&
                $end->format('H:i') <= $ruleEnd->format('H:i')
            ) {
                return true;
            }
        }

        return false;
    }

    public function isBooked($start, $end)
    {
        $reservations = $this->reservations()->get();

        foreach ($reservations as $reservation) {
            $reservationStart = new \DateTime($reservation->reservation_start);
            $reservationEnd = new \DateTime($reservation->reservation_end);

            if (
                $start->format('Y-m-d H:i:s') >= $reservationStart->format('Y-m-d H:i:s') &&
                $end->format('Y-m-d H:i:s') <= $reservationEnd->format('Y-m-d H:i:s')
            ) {
                return true;
            }
        }

        return false;
    }

    
}
