<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Session as FacadesSession;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

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

        Auth::login($user);
        Session::put('user', $user);

        return redirect()->route('home.index')->with('success', 'Đăng nhập thành công. Xin chào ' . $user->name);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
