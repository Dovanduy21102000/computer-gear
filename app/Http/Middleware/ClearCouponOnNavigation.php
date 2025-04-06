<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClearCouponOnNavigation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the current path
        $currentPath = $request->path();

        // If we're not on the checkout page and there's a coupon in the session
        if (!str_contains($currentPath, 'checkout') && Session::has('coupon')) {
            // Clear the coupon from the session
            Session::forget('coupon');
        }

        return $next($request);
    }
}
