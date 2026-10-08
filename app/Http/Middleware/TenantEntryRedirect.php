<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TenantEntryRedirect
{
    public function handle($request, Closure $next)
    {
        $slug = $request->route('tenant_slug');

        if (Auth::check()) {
            return redirect()->route('tenant.dashboard', $slug);
        }

        return redirect()->route('tenant.login', $slug);
    }
}
