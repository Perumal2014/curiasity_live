<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UserLogin;
use Illuminate\Support\Facades\Auth;

class RestoreTenantLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() && session()->has('login_token')) {

            $login = UserLogin::where('token', session('login_token'))
                ->where('status', 1)
                ->first();

            if ($login) {
                Auth::loginUsingId($login->user_id);

                return redirect()->intended($request->fullUrl());
            }
        }

        return $next($request);
    }
}
