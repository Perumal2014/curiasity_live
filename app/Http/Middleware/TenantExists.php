<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TenantExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant_slug = $request->route('tenant_slug');

        if (!$tenant_slug || !User::where('slug', $tenant_slug)->exists()) {
            abort(404, "Tenant not found");
        }

        return $next($request);
    }

}
