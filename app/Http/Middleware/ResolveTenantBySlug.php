<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Tenants;
use Illuminate\Support\Facades\Auth;

class ResolveTenantBySlug
{
    public function handle(Request $request, Closure $next)
    {
        // dd([
        //     'middleware' => 'ResolveTenant',
        //     'auth' => Auth::check(),
        //     'user' => Auth::id(),
        //     'session_id' => session()->getId(),
        //     'session_keys' => array_keys(session()->all()),
        // ]);
        $tenantSlug = $request->route('tenant_slug') ?? session('tenant_slug');

        if (!$tenantSlug) {
            return $next($request);
        }

        $tenant = Tenants::where('tenant_slug', $tenantSlug)
            ->where('is_active', 1)
            ->firstOrFail();

        app()->instance('tenant', $tenant);

        session()->put([
            'tenant_id'   => $tenant->id,
            'tenant_slug' => $tenant->tenant_slug,
            'tenant_logo' => $tenant->tenant_logo,
        ]);

        URL::defaults([
            'tenant_slug' => $tenant->tenant_slug,
        ]);

        return $next($request);
    }
}