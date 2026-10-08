<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use App\Models\Tenants;



class ResolveTenantBySlug
{

    public function handle(Request $request, Closure $next)
    {

        if (!$request->route()?->hasParameter('tenant_slug')) {
             return $next($request);
        }



        $tenant = Tenants::where('tenant_slug', $request->route('tenant_slug'))

            ->where('is_active', 1)

            ->firstOrFail();



        if ($tenant) {

            app()->instance('tenant', $tenant);

        }



        session()->put([

            'tenant_id'   => $tenant->id,

            'tenant_slug' => $tenant->tenant_slug,

            'tenant_logo' => $tenant->tenant_logo,

        ]);



        URL::defaults([

            'tenant_slug' => $tenant->tenant_slug

        ]);



        return $next($request);

    }

}