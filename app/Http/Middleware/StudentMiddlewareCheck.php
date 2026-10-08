<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use App\Models\Tenants;

class StudentMiddlewareCheck
{
    public function handle($request, Closure $next)
    {
        $tenantSlug = $request->route('tenant_slug');

        // ✅ Public site — no tenant
        if (!$tenantSlug) {
            return $next($request);
        }

        // ✅ Tenant exists → validate
        $tenant = Tenants::where('tenant_slug', $tenantSlug)->firstOrFail();

        // ✅ Bind tenant globally
        app()->instance('tenant', $tenant);

        // ✅ Store for logout / redirects
        session()->put('tenant_slug', $tenant->tenant_slug);

        // ✅ Apply URL defaults ONLY for tenant requests
        URL::defaults([
            'tenant_slug' => $tenant->tenant_slug
        ]);

        return $next($request);
    }

}
