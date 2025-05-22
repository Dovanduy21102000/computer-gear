<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionCookie
{
    /**
     * Handle an incoming request.
     * Thay đổi tên cookie session thành cookie admin (cookie_admin config)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Lấy cookie admin từ config/session.php
        $adminCookieName = config('session.cookie_admin', 'laravel_session_admin');

        // Đổi tên cookie session hiện tại
        config(['session.cookie' => $adminCookieName]);

        // Bắt đầu lại session với cookie mới
        // Note: Laravel session middleware xử lý session dựa trên config('session.cookie')

        return $next($request);
    }
}
