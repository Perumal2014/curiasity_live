<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
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