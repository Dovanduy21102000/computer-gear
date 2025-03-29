<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $email = $request->input('email');


        $password = $request->input('password');

        $user = DB::table('users')->where('email', $email)->first();


        if ($user && $user->password === $password) {
            Auth::loginUsingId($user->id);
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }

            Auth::logout();
            return redirect()->route('auth.admin')->with('error', 'Bạn không có quyền truy cập!');
        }

        return redirect()->route('auth.admin')->with('error', 'Email hoặc mật khẩu không chính xác!');
    }


    //Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
