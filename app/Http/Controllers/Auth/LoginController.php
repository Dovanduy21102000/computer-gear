<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
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
    
        // Nếu đang đăng nhập admin
        if ($request->is('admin/*')) {
            if ($user->role !== 'admin') {
                return back()->withErrors(['login_error' => 'Bạn không có quyền truy cập trang này!']);
            }
    
            // Đăng xuất user trước đó nếu có
            Auth::guard('web')->logout();
            session()->forget('user_logged_in');
    
            // Đăng nhập admin
            Auth::guard('admin')->login($user);
            session(['admin_logged_in' => true]);
    
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công!');
        }
    
        // Nếu đang đăng nhập member
        if ($user->role === 'member') {
            // Đăng xuất admin trước đó nếu có
            Auth::guard('admin')->logout();
            session()->forget('admin_logged_in');
    
            // Đăng nhập member
            Auth::guard('web')->login($user);
            session(['user_logged_in' => true]);
    
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công. Xin chào ' . $user->name);
        }
    
        return back()->withErrors(['login_error' => 'Bạn không có quyền truy cập.']);
    }
    

    // Đăng xuất
    public function logout(Request $request)
    {
        // Kiểm tra và logout dựa trên guard
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            session()->forget('admin_logged_in');
            return redirect()->route('auth.admin');
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            session()->forget('user_logged_in');
            return redirect('/');
        }

        // Nếu không có guard nào được đăng nhập, reset session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


