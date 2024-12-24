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
        // Initial query without eager loading
        $query = PaymentAccount::where('expiration_date', '>', now());
    
        // Apply search filter if provided
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($accountType = request('account_type')) {
            $query->where('account_type', $accountType);
        }
        
        // Paginate results and then eager load the users only after filtering
        $paymentAccounts = $query->paginate(50);
        $paymentAccounts->load('users');
    
        return view('payment-accounts.index', compact('paymentAccounts'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    

        // $request->netid is the netid of the user that was selected
        $selectedUser = User::where('netid', $request->netid)->first();

        return view('payment-accounts.create', compact('selectedUser'));
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

        $data['account_name'] = $request->account_name;
        $data['account_number'] = $request->account_number;
        $data['account_type'] = $request->account_type;
        $data['expiration_date'] = $request->expiration_date;


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
            'paymentAccount' => $paymentAccount
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentAccount $paymentAccount)
    {
        return view('payment-accounts.edit', [
            'paymentAccount' => $paymentAccount
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

        if ($request->expiration_date) {
            $paymentAccount->expiration_date = $request->expiration_date;
        }

        if ($request->fiscal_officer) {
            if ($paymentAccount->fiscal_officer) {
                $paymentAccount->users()->detach($paymentAccount->fiscal_officer);
            }

            $paymentAccount->users()->attach($request->fiscal_officer, ['role' => 'fiscal_officer']);
        }

        if ($request->account_manager) {
            if ($paymentAccount->account_manager) {
                $paymentAccount->users()->detach($paymentAccount->account_manager);
            }

            $paymentAccount->users()->attach($request->account_manager, ['role' => 'account_manager']);
        }


        $paymentAccount->update($request->all());
        // update the user to the account as the owner


        return redirect()->route('payment-accounts.index');
    }


    public function addUser(Request $request, PaymentAccount $paymentAccount)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required',
        ], [
            'user_id.required' => 'The user field is required.',
            'role.required' => 'The role field is required.',
        ]);

        $paymentAccount->users()->attach($request->user_id, ['role' => $request->role]);

        return redirect()->route('payment-accounts.show', $paymentAccount);
    }

    public function authorizedUsers(PaymentAccount $paymentAccount)
    {
        return view('payment-accounts.authorized-users', [
            'paymentAccount' => $paymentAccount
        ]);
    }

    public function addAuthorizedUser(Request $request, PaymentAccount $paymentAccount)
    {
        $request->validate([
            'additional_users' => 'required|array',
            'additional_users.*' => 'exists:users,id',
        ], [
            'additional_users.required' => 'The user field is required.',
            'additional_users.*.exists' => 'One or more selected users do not exist. Tell them to log in to create an account.',
        ]);

        $paymentAccount->authorized_users()->syncWithoutDetaching($request->additional_users);

        

        return redirect()->route('payment-accounts.authorizedUsers', $paymentAccount)->with('success', 'User added successfully.');
    }

    public function removeAuthorizedUser(PaymentAccount $paymentAccount, User $user)
    {
        $paymentAccount->authorized_users()->detach($user->id);

        return redirect()->route('payment-accounts.authorizedUsers', $paymentAccount)->with('success', 'User removed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentAccount $paymentAccount)
    {
        // remove all users from the account
        $paymentAccount->users()->detach();
        // remove all authorized users from the account
        $paymentAccount->authorized_users()->detach();


        $paymentAccount->delete();
        return redirect()->route('payment-accounts.index');
    }
}
