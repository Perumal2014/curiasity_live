<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {

    //         $tenant = request()->segment(1);

    //         return url($tenant . '/login');
    //     }
    // }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            if ($request->route() && $request->route()->hasParameter('tenant_slug')) {
                return route('tenant.login', [
                    'tenant_slug' => $request->route('tenant_slug')
                ]);
            }

            return route('login');
        }
    }

}