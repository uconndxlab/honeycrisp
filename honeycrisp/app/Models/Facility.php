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
    
}
