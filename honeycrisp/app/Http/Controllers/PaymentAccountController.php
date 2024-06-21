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
            'expiration_date' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'account_number.required' => 'The account number field is required.',
            'type.required' => 'The type field is required.',
            'owner.required' => 'The owner field is required.',
            'expiration_date.required' => 'The expiration date field is required.',
        ]);

        $data ['account_name'] = $request->account_name;
        $data ['account_number'] = $request->account_number;
        $data ['account_type'] = $request->account_type;
        $data ['expiration_date'] = $request->expiration_date;


        $paymentAccount = PaymentAccount::create($data);

        // update the user to the account as the owner
        $paymentAccount->users()->attach($request->account_owner, ['role' => 'owner']);
      

        return redirect()->route('payment-accounts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentAccount $paymentAccount)
    {
        return view('payment-accounts.show', [
            'paymentAccount' => $paymentAccount,
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentAccount $paymentAccount)
    {
        return view('payment-accounts.edit', [
            'paymentAccount' => $paymentAccount,
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentAccount $paymentAccount)
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

        $paymentAccount->update($request->all());
        // update the user to the account as the owner
    

        return redirect()->route('payment-accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentAccount $paymentAccount)
    {
        $paymentAccount->delete();
        return redirect()->route('payment-accounts.index');
    }
}
