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
            $ruleStart = \Carbon\Carbon::parse($rule->time_of_day_start);
            $ruleEnd = \Carbon\Carbon::parse($rule->time_of_day_end);

            // Ensure the reservation fits within min/max allowed time
            $minTime = $this->minimum_reservation_time ? \Carbon\Carbon::parse($this->minimum_reservation_time) : null;
            $maxTime = $this->maximum_reservation_time ? \Carbon\Carbon::parse($this->maximum_reservation_time) : null;

            if (\Carbon\Carbon::instance($start)->between($ruleStart, $ruleEnd, true) && \Carbon\Carbon::instance($end)->between($ruleStart, $ruleEnd, true)) {
                $reservationDuration = $end->diffInMinutes($start);
                if (($minTime && $reservationDuration < $minTime->diffInMinutes('00:00')) ||
                    ($maxTime && $reservationDuration > $maxTime->diffInMinutes('00:00'))
                ) {
                    return false;
                }
                return true;
            }
        }

        return false;
    }

    public function isBooked($start, $end)
    {
        return $this->reservations()
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('reservation_start', '<', $end)
                        ->where('reservation_end', '>', $start);
                });
            })
            ->exists();
    }
}
