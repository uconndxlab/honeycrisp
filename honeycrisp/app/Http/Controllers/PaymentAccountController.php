<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('payment-accounts.index', [
            'paymentAccounts' => PaymentAccount::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users =  User::all();
        return view('payment-accounts.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required',
            'account_number' => 'required',
            'account_type' => 'required',
            'account_owner' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'account_number.required' => 'The account number field is required.',
            'type.required' => 'The type field is required.',
            'owner.required' => 'The owner field is required.',
        ]);

        $paymentAccount = PaymentAccount::create($request->all());
        // add the user to the account as the owner
        $paymentAccount->users()->attach($request->account_owner, ['role' => 'owner']);

        return redirect()->route('payment-accounts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentAccount $paymentAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentAccount $paymentAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentAccount $paymentAccount)
    {
        //
    }
}
