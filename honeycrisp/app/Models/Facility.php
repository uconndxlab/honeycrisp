<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Facility extends Model
{
    use HasFactory;
    // name, description, email, address, abbreviation, recharge_account
    protected $fillable = ['name', 'description', 'email', 'address', 'abbreviation', 'recharge_account'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function director()
    {
        return $this->staff()->where('role', 'director')->first();
    }

    public function staff()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getRecentlyUsedProductsAttribute()
    {
        // Order Item is the table to get this from. Order_item has a field called "product_id".
        // Just get the most recent 5 order items from orders that are in this facility and list all the products that were ordered.
    
        $order_items = OrderItem::whereHas('order', function ($query) {
            $query->where('facility_id', $this->id);
        })->orderBy('created_at', 'desc')->limit(5)->get();
    
        $products = collect();
    
        foreach ($order_items as $order_item) {
            $product_id = $order_item->product_id;
            if ($product_id != null) {
                $product = Product::find($product_id);
                if ($product) {
                    $products->push($product);
                }
            }
        }
    
        return $products->unique('id')->values();
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
        $deptAddress = '91 N. Eagleville Rd';
        $campusCode = '01';
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
        //convert $total to be like 92.00

        $total = $total*2;
        $total = $total/100;
        
        $total = number_format($total, 2, '.', '');

        $footer = str_pad('', 25, ' ', STR_PAD_RIGHT)
            . 'TL'
            . str_pad('', 19, ' ', STR_PAD_RIGHT)
            . str_pad($glCount, 5, '0', STR_PAD_LEFT)
            . str_pad('', 41, ' ', STR_PAD_RIGHT)
            . str_pad($total, 20, '0', STR_PAD_LEFT);

        return $footer. "\n";

    }

    
    
}
