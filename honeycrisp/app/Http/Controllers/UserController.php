<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::all();
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
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = \App\Models\User::find($id)->first();
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // load the create view but with the user data
        $user = \App\Models\User::find($id)->first();
        return view('users.create', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::find($id)->first();

        if ($request->has('name') && $request->name !== $user->name) {
            $user->name = $request->name;
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

        if ($request->has('external_rates') && $request->external_rates !== $user->external_rates) {
            $user->external_rates = $request->external_rates;
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
        //
    }
}