<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeparateSession
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (Auth::check()) {
            $role = Auth::user()->role;
            
            // Lưu thông tin vai trò vào session
            if ($role === 'admin') {
                // Đảm bảo admin sử dụng session và cookie riêng biệt
                session(['admin_logged_in' => true]);
                session(['role_session' => 'admin']);
                // Đảm bảo rằng session cookie là dành riêng cho admin
                cookie()->queue(cookie('admin_session', session()->getId(), 120));
            } elseif ($role === 'member') {
                // Đảm bảo member sử dụng session và cookie riêng biệt
                session(['user_logged_in' => true]);
                session(['role_session' => 'member']);
                // Đảm bảo rằng session cookie là dành riêng cho member
                cookie()->queue(cookie('user_session', session()->getId(), 120));
            }
        }

        return $next($request);
    }
}


