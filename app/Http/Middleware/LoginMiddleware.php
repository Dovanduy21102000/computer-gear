<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu đã đăng nhập và người dùng có vai trò admin, chuyển hướng đến trang admin
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard.index'); // Hoặc route admin bạn muốn
            }
        }

        return $next($request);
    }
}

