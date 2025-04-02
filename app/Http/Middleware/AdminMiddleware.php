<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            Auth::logout(); // Đăng xuất nếu không phải admin
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }

        return $next($request);
    }
}
