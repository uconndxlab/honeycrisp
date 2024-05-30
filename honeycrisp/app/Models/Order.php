<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'date', 'user_id', 'facility_id', 'payment_account', 'status', 'tags', 'total'];

    public function items()
    {
        return $this->hasMany(OrderItem::class); 
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class);
    }
    
    public function updateTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }
        $this->total = $total;
        $this->save();
    }
}
