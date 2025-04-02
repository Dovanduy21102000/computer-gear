<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Sử dụng Eloquent Model

class AuthController extends Controller
{
    // Hiển thị trang login
    public function index()
    {
        if (Auth::check()) {
            // Kiểm tra người dùng đã đăng nhập và có vai trò admin chưa
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard.index'); // Nếu admin, chuyển hướng đến dashboard
            }
            return redirect()->route('home.index'); // Nếu không phải admin, chuyển hướng đến trang chủ
        }
    
        return view('backend.auth.login'); // Trả về trang đăng nhập nếu chưa đăng nhập
    }
    
    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Lấy thông tin người dùng từ bảng users
        $user = User::where('email', $request->email)->first(); // Sử dụng Eloquent để truy vấn

        // Kiểm tra người dùng tồn tại và mật khẩu chính xác
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user); // Đăng nhập người dùng

            // Kiểm tra vai trò của người dùng
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }

            // Nếu người dùng không phải là admin, đăng xuất và chuyển hướng lại trang login
            Auth::logout();
            return redirect()->route('auth.admin')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Nếu email hoặc mật khẩu sai, trả về thông báo lỗi
        return redirect()->route('auth.admin')->with('error', 'Email hoặc mật khẩu không chính xác!');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout(); // Đăng xuất người dùng
        $request->session()->invalidate(); // Hủy session
        $request->session()->regenerateToken(); // Tạo lại token CSRF
        return redirect()->route('auth.admin'); // Chuyển hướng về trang login
    }
}
