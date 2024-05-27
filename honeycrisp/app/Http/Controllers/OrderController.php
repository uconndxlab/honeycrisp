<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Facility;
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

        if (request('netid')) {
            $user = User::all()->where('netid', request('netid'))->first();
            $selected_user = $user->id;
        }
        
        $facility = Facility::all()->where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first();
        
        return view('orders.create', compact('facility', 'users', 'selected_user'));
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
