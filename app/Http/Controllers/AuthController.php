<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) { // Use Auth::check() instead of checking Auth::id() > 0
            return redirect()->route('dashboard.index');
        }
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password'); // Retrieve email & password

        if (Auth::attempt($credentials)) { // Laravel's built-in authentication
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }

            Auth::logout();
            return redirect()->route('auth.admin')->with('error', 'Bạn không có quyền truy cập!');
        }

        return redirect()->route('auth.admin')->with('error', 'Email hoặc mật khẩu không chính xác!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
