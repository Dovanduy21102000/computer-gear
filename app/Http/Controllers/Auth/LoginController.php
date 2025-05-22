<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập cho member
    public function showLoginForm()
    {
        $template = 'fontend.auth.login';
        return view('fontend.layout', compact('template'));
    }

    // Hiển thị form đăng nhập cho admin
    public function showAdminLoginForm()
    {
        $template = 'fontend.auth.admin_login';
        return view('fontend.layout', compact('template'));
    }

    // Xử lý đăng nhập member
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login_error' => 'Email hoặc mật khẩu không chính xác!'])->withInput();
        }

        if ($user->role !== 'member') {
            return back()->withErrors(['login_error' => 'Bạn không có quyền đăng nhập trang này!'])->withInput();
        }

        // KHÔNG logout admin nữa để giữ trạng thái đăng nhập admin

        Auth::guard('web')->login($user);
        session(['user_logged_in' => true]);

        return redirect()->route('home.index')->with('success', 'Đăng nhập thành công. Xin chào ' . $user->name);
    }

    // Xử lý đăng nhập admin
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login_error' => 'Email hoặc mật khẩu không chính xác!'])->withInput();
        }

        if ($user->role !== 'admin') {
            return back()->withErrors(['login_error' => 'Bạn không có quyền truy cập trang này!'])->withInput();
        }

        // KHÔNG logout member nữa để giữ trạng thái đăng nhập member

        Auth::guard('admin')->login($user);
        session(['admin_logged_in' => true]);

        return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công!');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            session()->forget('user_logged_in');

            // Chỉ regenerate CSRF token, không invalidate session toàn bộ
            $request->session()->regenerateToken();

            return redirect()->route('login.form');
        }

        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            session()->forget('admin_logged_in');

            // Chỉ regenerate CSRF token, không invalidate session toàn bộ
            $request->session()->regenerateToken();

            return redirect()->route('auth.admin.login');
        }

        // Nếu không ai đăng nhập thì redirect về trang chủ, vẫn regenerate token
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
