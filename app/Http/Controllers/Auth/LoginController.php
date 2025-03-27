<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    use AuthenticatesUsers;

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');  // Trả về view của bạn (ví dụ: footer.blade.php hoặc một view khác)
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu nhập vào
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // dd($credentials);
        // Cố gắng đăng nhập người dùng
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công, chuyển hướng người dùng
            return redirect()->route('home');
        }

        // Đăng nhập thất bại
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
