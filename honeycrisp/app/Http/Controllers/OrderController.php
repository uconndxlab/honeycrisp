<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $orders = Order::all();



        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($facilityAbbreviation)
    {

        // if request[netid] is not null, get the user with that netid so they can be selected by default

        $users = User::all()->where('status', 'active');
        $selected_user = null;

        if (request('user_id')) {
            $user = User::all()->where('netid', request('user_id'))->first();
            $selected_user = $user->id;
            $accounts = [];

            $accounts =  $user->paymentAccounts()->get();
        } else {
            $accounts = null;
        }


        $facility = Facility::all()->where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first();

        return view('orders.create', compact('facility', 'users', 'selected_user', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = User::all()->where('netid', $request->user_id)->first()->id;
        $facility_id = Facility::all()->where('abbreviation', $request->facility_abbreviation)->first()->id;
        $payment_account = PaymentAccount::find($request->payment_account)->id;

        $order = new Order();
        $order->user_id = $user_id;
        $order->facility_id = $facility_id;
        $order->payment_account = $payment_account;
        $order->status = 'draft';
        

        


    

        $order->save();
    
        foreach ($request->items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->name = $item['name'];
            $orderItem->price = $item['price'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->save();
        }
    
        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
