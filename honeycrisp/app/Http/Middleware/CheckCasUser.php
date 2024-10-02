<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckCasUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( !Auth::check() && cas()->isAuthenticated() ) {
            $user = \App\Models\User::firstOrCreate(
                ['netid' => cas()->user()],
                [
                    'name' => cas()->user(),
                    'email' => cas()->user() . '@uconn.edu',
                    'price_group' => 'internal'
                ]
            );

            $ldapUser = \App\Ldap\User::where('uid', cas()->user())->first();

            if ( $ldapUser ) {
                $user->name = $ldapUser->cn[0];
                $user->email = $ldapUser->mail[0];
                $user->save();
            }

            Auth::login($user);
        }
        return $next($request);
    }
}
