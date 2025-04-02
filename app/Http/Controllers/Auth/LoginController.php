<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;


    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        $template = 'fontend.auth.login';
        return view('fontend.layout', compact('template'));
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['login_error' => 'Email không tồn tại!']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login_error' => 'Mật khẩu không chính xác!']);
        }

        // Kiểm tra nếu đang đăng nhập ở trang admin
        if ($request->is('admin/*')) {
            if ($user->role !== 'admin') {
                return back()->withErrors(['login_error' => 'Bạn không có quyền truy cập trang này!']);
            }
            Auth::guard('admin')->login($user);
            session(['admin_logged_in' => true]); // Đánh dấu session cho admin
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công!');
        }

        // Đăng nhập cho member
        if ($user->role === 'member') {
            Auth::guard('web')->login($user);
            session(['user_logged_in' => true]); // Đánh dấu session cho member
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công. Xin chào ' . $user->name);
        }

        return back()->withErrors(['login_error' => 'Không thể đăng nhập vào hệ thống.']);
    }


    public function logout(Request $request)
    {
        if (session('admin_logged_in')) {
            Auth::guard('admin')->logout();
            session()->forget('admin_logged_in');
        }

        if (session('user_logged_in')) {
            Auth::guard('web')->logout();
            session()->forget('user_logged_in');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
