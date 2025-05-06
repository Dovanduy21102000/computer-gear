<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        $template = 'fontend.auth.forgot-password';
        return view('fontend.layout', compact('template'));
    }

    // Xử lý gửi email reset mật khẩu
    public function sendResetLinkEmail(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Gửi email reset mật khẩu
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'hãy kiểm tra email của bạn để đặt lại mật khẩu.');
        }

        return back()->withErrors(['email' => 'Không thể gửi link reset mật khẩu.']);
    }
}