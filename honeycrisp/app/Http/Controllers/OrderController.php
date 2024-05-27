<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\PaymentAccount;
use App\Models\User;

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

            $account_ids =  $user->paymentAccounts();
           // loop through the accounts and echo the account number
            foreach ($account_ids as $account_id) {
                $account = PaymentAccount::all()->where('id', $account_id)->first();
                $accounts[] = $account;
            }


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
        //
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
