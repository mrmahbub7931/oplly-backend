<?php

namespace Canopy\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class RedirectIfNotAPICustomer
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'api-customer')
    {
        if (!Auth::guard($guard)->check()) {
            return response()->json([ "error" => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
