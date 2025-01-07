<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'description', 'name'];

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
        $uch_object_code = 1390;
        $uch_payment_account = "4643530";
        $payment_account = $this->order->paymentAccount->account_number;

        // if the paymentaccount type is uch, then object code should be $uch_object_code and payment account should be $uch_payment_account

        if ($this->order->paymentAccount->account_type == 'uch') {
            $objectcode = $uch_object_code;
            $payment_account = $uch_payment_account;
        }

        //if the payment account account_category is 'tc' then the object code is actually 2750
        if ($this->order->paymentAccount->account_category == 'tc') {
            $objectcode = 2750;
        }



        $line = $fiscalYear
            . "UC" // Chart Code, always UC for now
            . $payment_account; // Account Number

        // If $line is not yet 18 characters long, pad it with spaces
        $line = str_pad($line, 18, ' ', STR_PAD_RIGHT);
        $line .= "$objectcode   "; // Object Code for Debit, with 3 spaces.
        $line .= "AC    "; // Balance Type, with 4 spaces.
        $line .= "CLTRCCC"; // This is some BS they need here and it wont change.
        $line .= str_pad($sequenceNumber, 13, '0', STR_PAD_LEFT); // Sequence Number for this debit/credit
        $line .= "     "; // 5 spaces
        // Now we have (57-97) lines to create a description
        $description = "COR2E:" . $this->order->facility->abbreviation ." #"
        . $this->order->id
        . " "
        . substr($this->product->name ?? $this->description, 0, 20)
        . " "
        . $this->quantity
        . "@"
        . $this->price / 100
        . "ea";

        // Ensure it doesn't go over 40chars and pad it with spaces if it's short
        $line .= str_pad( substr($description, 0, 40), 40, ' ', STR_PAD_RIGHT);
        $line .= ' ';

        // Next let's put the value and date
        $money = number_format(($this->price * $this->quantity) / 100, 2, '.', '');
        $vals = str_pad( $money, 20, 0, STR_PAD_LEFT);
        $vals .= 'D'; // Debit
        $vals .= $this->order->created_at->format('Y-m-d');

        $line .= $vals;

        // 129 - 138 for the order ID
        $line .= str_pad($this->order->id, 10, ' ', STR_PAD_RIGHT);

        // 139-148 just nothing
        $line .= str_pad('', 10, ' ', STR_PAD_RIGHT);

        // 149 - 157 for the facility abbreviation
        $line .= str_pad($this->order->facility->abbreviation, 9, ' ', STR_PAD_RIGHT);

        return $line;
    }


    public function kfsCreditLine( $sequenceNumber ) {
        $fiscalYear = Carbon::now()->month < 7 ? Carbon::now()->year : Carbon::now()->year + 1;
        $object_code = 4565;

        //if the payment account account_category is 'tc' then the object code is actually 4510
        if ($this->order->paymentAccount->account_category == 'tc' || $this->order->paymentAccount->account_type == 'uch') {
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
        $description = "COR2E:" . $this->order->facility->abbreviation ." #"
            . $this->order->id
            . " "
            . substr($this->product->name ?? $this->description, 0, 20)
            . " "
            . $this->quantity
            . "@"
            . $this->price / 100
            . "ea";

        // Ensure it doesn't go over 40chars and pad it with spaces if it's short
        $line .= str_pad( substr($description, 0, 40), 40, ' ', STR_PAD_RIGHT);
        $line .= ' ';

        // Next let's put the value and date
        $money = number_format(($this->price * $this->quantity) / 100, 2, '.', '');
        $vals = str_pad( $money, 20, 0, STR_PAD_LEFT);
        $vals .= 'C'; // Credit
        $vals .= $this->order->created_at->format('Y-m-d');

        $line .= $vals;

        // 129 - 138 for the order ID
        $line .= str_pad($this->order->id, 10, ' ', STR_PAD_RIGHT);

        // 149-147 just nothing
        $line .= str_pad('', 10, ' ', STR_PAD_RIGHT);

        // 149 - 157 for the facility abbreviation
        $line .= str_pad($this->order->facility->abbreviation, 9, ' ', STR_PAD_RIGHT);

        return $line;
    }
}
