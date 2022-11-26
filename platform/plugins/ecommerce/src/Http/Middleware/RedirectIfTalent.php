<?php

namespace Canopy\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfTalent
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'talent')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect(route('customer.overview'));
        }

        return $next($request);
    }
}
