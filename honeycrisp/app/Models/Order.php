<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'date', 'user_id', 'facility_id', 'payment_account_id', 'status', 'tags', 'total'];

    // set status color based on status

    // Accessor to get the status color based on the status
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return 'secondary';
            case 'pending':
                return 'warning';
            case 'approved':
                return 'success';
            case 'in progress':
                return 'info';
            case 'canceled':
                return 'danger';
            case 'complete':
                return 'success';
            case 'invoiced':
                return 'primary';
            case 'archived':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

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

    public static function statusOptions()
    {
        return [
            'quote' => 'Quote',
            'pending' => 'Pending Approval',
            'accepted' => 'Accepted',
            'in_progress' => 'In Progress',
            'invoice' => 'Invoice',
            'sent_to_kfs' => 'Sent to KFS',
            'reconciled' => 'Reconciled',
            'canceled' => 'Canceled',
            'archived' => 'Archived'
        ];
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }
}
