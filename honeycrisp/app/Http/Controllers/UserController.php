<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->has('search')) {
            $users = \App\Models\User::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('netid', 'like', '%' . $request->search . '%')
                ->paginate(30);
        } else {
            $users = \App\Models\User::orderBy('name')->paginate(30);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least :min characters.',
        ]);


        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'netid' => $request->netid, // added 'netid' => $request->netid, to the create method
            'role' => $request->role,
            'status' => $request->status,
            'external_rates' => $request->external_rates,
            'external_organization' => $request->external_organization ?? null,
            'external_customer_id' => $request->external_customer_id ?? null,
            'price_group' => $request->price_group ?? null,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = \App\Models\User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Retrieve the user with their payment accounts
        $user = \App\Models\User::with('paymentAccounts')->find($id);
    
        // Ensure the user exists before returning the view
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        $ldapOutput = \App\Ldap\User::where('uid', 'bak11004')->get();
    
        return view('users.create', compact('user', 'ldapOutput'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::find($id);

        if ($request->has('name') && $request->name !== $user->name) {
            $user->name = $request->name;
        }

        if ($request->has('netid') && $request->netid !== $user->netid) {
            $user->netid = $request->netid;
        }

        if ($request->has('email') && $request->email !== $user->email) {
            $user->email = $request->email;
        }

        if ($request->has('role') && $request->role !== $user->role) {
            $user->role = $request->role;
        }

        if ($request->has('status') && $request->status !== $user->status) {
            $user->status = $request->status;
        }

        if ($request->has('external_rates') && $request->external_rates !== $user->external_rates && $request->external_rates !== null) {
            $user->external_rates = $request->external_rates;
        }


     

        // external_organization and kuali_customer_id

        if ($request->has('external_organization') && $request->external_organization !== $user->external_organization) {
            $user->external_organization = $request->external_organization;
        }

        if ($request->has('external_customer_id') && $request->external_customer_id !== $user->external_customer_id) {
            $user->external_customer_id = $request->external_customer_id;
        }

        if ($request->has('password') && $request->password !== $user->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::find($id);
        $user->paymentAccounts()->delete();
        // detatch all the roles and accounts before deleting the user
        
  
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
