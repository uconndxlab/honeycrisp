<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'description'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Generates a string representing the debit line for this order item.
     * 
     */
    public function kfsDebitLine( $sequenceNumber ) {
        $fiscalYear = Carbon::now()->month < 7 ? Carbon::now()->year : Carbon::now()->year + 1;
        $objectcode = 6610;

        //if the payment account account_category is 'tc' then the object code is actually 2750
        if ($this->order->paymentAccount->account_category == 'tc') {
            $objectcode = 2750;
        }

        $line = $fiscalYear
            . "UC" // Chart Code, always UC for now
            . $this->order->paymentAccount->account_number; // Account Number

        // If $line is not yet 18 characters long, pad it with spaces
        $line = str_pad($line, 18, ' ', STR_PAD_RIGHT);
        $line .= "$objectcode   "; // Object Code for Debit, with 3 spaces.
        $line .= "AC    "; // Balance Type, with 4 spaces.
        $line .= "CLTRCCC"; // This is some BS they need here and it wont change.
        $line .= str_pad($sequenceNumber, 13, '0', STR_PAD_LEFT); // Sequence Number for this debit/credit
        $line .= "     "; // 5 spaces
        // Now we have (57-97) lines to create a description
        $description = "HnyCsp#"
            . $this->order->id
            . " "
            . substr($this->product->name ?? $this->description, 0, 20)
            . " "
            . $this->quantity
            . ($this->product->unit ?? 'unit')
            . " @ "
            . $this->price / 100
            . " ea";

        // Ensure it doesn't go over 40chars and pad it with spaces if it's short
        $line .= str_pad( substr($description, 0, 40), 40, ' ', STR_PAD_RIGHT);
        $line .= ' ';

        // Next let's put the value and date
        $money = number_format(($this->price * $this->quantity) / 100, 2, '.', '');
        $vals = str_pad( $money, 20, 0, STR_PAD_LEFT);
        $vals .= 'D'; // Debit
        $vals .= $this->order->created_at->format('Y-m-d');

        $line .= $vals;

        return $line;
    }


    public function kfsCreditLine( $sequenceNumber ) {
        $fiscalYear = Carbon::now()->month < 7 ? Carbon::now()->year : Carbon::now()->year + 1;

        $object_code = 4565;

        //if the payment account account_category is 'tc' then the object code is actually 4510
        if ($this->order->paymentAccount->account_category == 'tc') {
            $object_code = 4510;
        }
        $line = $fiscalYear
            . "UC" // Chart Code, always UC for now
            . ($this->product->recharge_account ?? $this->order->facility->recharge_account); // Account Number of Product, or parent Facility

        // If $line is not yet 18 characters long, pad it with spaces
        $line = str_pad($line, 18, ' ', STR_PAD_RIGHT);
        $line .= "{$object_code}   "; // Object Code for Debit, with 3 spaces.


        $line .= "AC    "; // Balance Type, with 4 spaces.
        $line .= "CLTRCCC"; // This is some BS they need here and it wont change.
        $line .= str_pad($sequenceNumber, 13, '0', STR_PAD_LEFT); // Sequence Number for this debit/credit
        $line .= "     "; // 5 spaces
        // Now we have (57-97) lines to create a description
        $description = "HnyCsp#"
            . $this->order->id
            . " "
            . substr($this->product->name ?? $this->description, 0, 20)
            . " "
            . $this->quantity
            . ($this->product->unit ?? 'unit')
            . " @ "
            . $this->price / 100
            . " ea";

        // Ensure it doesn't go over 40chars and pad it with spaces if it's short
        $line .= str_pad( substr($description, 0, 40), 40, ' ', STR_PAD_RIGHT);
        $line .= ' ';

        // Next let's put the value and date
        $money = number_format(($this->price * $this->quantity) / 100, 2, '.', '');
        $vals = str_pad( $money, 20, 0, STR_PAD_LEFT);
        $vals .= 'C'; // Credit
        $vals .= $this->order->created_at->format('Y-m-d');

        $line .= $vals;

        return $line;
    }
}
