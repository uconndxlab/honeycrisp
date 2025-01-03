<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        if ( Auth::check() ) {
            return redirect()->route('home');
        }
        return view('login');
    }


    public function submitLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $potential_user = User::where('email', $credentials['email'])->first();
        if ( $potential_user && ($potential_user->password === null || $potential_user->netid) ) {
            return back()->withErrors([
                'email' => 'You must login with your NetID.',
            ])->onlyInput('email');
        }

        if ( $request->password === null ) {
            return back()->withErrors([
                'password' => 'You must supply a password, or log in with NetID.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        cas()->logout();
        return redirect('/');
    }

    public function register()
    {
        return view('register');
    }

    public function submitRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }


    public function casLogin() {
        cas()->authenticate();

        return redirect()->route('facilities.index');
    }
}
