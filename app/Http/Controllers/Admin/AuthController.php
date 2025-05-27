<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị trang login
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->role === 'admin') {
                return redirect()->route('dashboard.index'); 
            }
            return redirect()->route('home.index'); 
        }
    
        return view('backend.auth.login');
    }
    
    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role === 'admin') {
                Auth::guard('admin')->login($user);
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }

            Auth::guard('admin')->logout();
            return redirect()->route('auth.admin.login')->with('error', 'Bạn không có quyền truy cập!');
        }

        return redirect()->route('auth.admin.login')->with('error', 'Email hoặc mật khẩu không chính xác!');
    }

    // Đăng xuất
  public function logout(Request $request)
{
    Auth::guard('admin')->logout();

    // Không invalidate toàn bộ session, tránh đăng xuất luôn guard 'web'
    // Chỉ regenerate token để tránh CSRF
    $request->session()->regenerateToken();

    return redirect()->route('auth.admin');
}

}
