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

    public function customer()
    {

        return $this->belongsTo(User::class, 'user_id');

    }

    // Additional users associated with the order
    public function users()
    {
        return $this->belongsToMany(User::class, 'order_user', 'order_id', 'user_id');
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
        return $this->hasMany(OrderLog::class)->orderBy('changed_at', 'desc');
    }

    public static function statusColor($status)
    {
        switch ($status) {
            case 'quote':
                return 'secondary';
            case 'pending':
                return 'warning';
            case 'accepted':
                return 'success';
            case 'in_progress':
                return 'info';
            case 'invoice':
                return 'primary';
            case 'sent_to_kfs':
                return 'primary';
            case 'reconciled':
                return 'success';
            case 'canceled':
                return 'danger';
            case 'archived':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

    public function generateFinancialLines(){

        $financial_line_items = collect();
        $counter = 0;

        foreach ( $this->items as $item ) {
            $financial_line_items->push($item->kfsDebitLine($counter));
            $financial_line_items->push($item->kfsCreditLine($counter));
            $counter++;
        }

        return $financial_line_items;
    }

    public function generateFinancialHeader() {
        // Header – 172 Characters
        // 1-4: Fiscal Year
        // 5-6: Chart Code
        // 7-10 Organization Code
        // 11-15 blank spaces
        // 16-25 Transmission Date in YYYY-MM-DD format
        // 26-27 just the letters HD
        // 28 batch sequence number (0-9)
        // 29-68 email address
        // 69-98 Department Contact Person
        // 99-128 Department Name
        // 129-158 Department Address
        // 159-160 Campus Code
        // 161-170 Department Phone Number
        // 171-172 blank spaces 
        

        // get the fiscal year from the date of the order
        
        $orderDate = date_create($this->date);
        $month = date_format($orderDate, 'm');
        $year = date_format($orderDate, 'Y');

        $fiscalYear = $month < 7 ? $year : $year + 1;
        $chartCode = 'UC';
        $orgCode = 1348;
        $transDate = $this->date;
        $batchSeq = 0;
        $email = 'honeycrisp@uconn.edu';
        $contactPerson = 'Cynthia Doherty';
        $deptName = 'COR2E';
        $deptAddress = '159 Discovery Dr, Storrs, CT';
        $campusCode = 'ST';
        $deptPhone = '018605551234';

        $header = $fiscalYear
            . $chartCode
            . str_pad($orgCode, 4, '0', STR_PAD_LEFT)
            . str_pad('', 5, ' ', STR_PAD_RIGHT)
            . $transDate
            . 'HD'
            . $batchSeq
            . str_pad($email, 40, ' ', STR_PAD_RIGHT)
            . str_pad($contactPerson, 30, ' ', STR_PAD_RIGHT)
            . str_pad($deptName, 30, ' ', STR_PAD_RIGHT)
            . str_pad($deptAddress, 30, ' ', STR_PAD_RIGHT)
            . $campusCode
            . str_pad($deptPhone, 10, ' ', STR_PAD_RIGHT)
            . str_pad('', 2, ' ', STR_PAD_RIGHT);

        return $header. "\n";

    }

    public function generateFinancialFooter($glCount, $total) {
        // Trailer Record – 112 Characters
        // 1-25 blank spaces
        // 26-27 just the letters TL
        // 28-46 blank spaces
        // 47-51 $glCount
        // 52-91 blank spaces
        // 93-112 $total


        $footer = str_pad('', 25, ' ', STR_PAD_RIGHT)
            . 'TL'
            . str_pad('', 19, ' ', STR_PAD_RIGHT)
            . str_pad($glCount, 5, '0', STR_PAD_LEFT)
            . str_pad('', 40, ' ', STR_PAD_RIGHT)
            . str_pad($total, 20, '0', STR_PAD_LEFT);

        return $footer. "\n";

    }

    public function financialFile(){
        // the header, the line items, and the footer as a fixed width string
        $financial_lines = $this->generateFinancialLines();
        $glCount = $financial_lines->count();
        $total = $this->total;

        $ledgerString = $this->generateFinancialHeader();

        foreach ($financial_lines as $line) {
            $ledgerString .= $line . "\n";
        }

        $ledgerString .= $this->generateFinancialFooter($glCount, $total);

        return $ledgerString;
    }
}
