<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class TenantContext
{
    public function handle(Request $request, Closure $next)
    {
        $tenantSlug = $request->route('slug');
        //print_r($tenantSlug); exit;
        if ($tenantSlug) {
            $tenant = User::where('slug', trim($tenantSlug))->first();

            if (! $tenant) {
                abort(404);
            }

            session([
                'tenant_id'   => $tenant->id,
                'slug' => $tenant->slug,
            ]);

            app()->instance('tenant', $tenant);
        }

        return $next($request);
    }
}


