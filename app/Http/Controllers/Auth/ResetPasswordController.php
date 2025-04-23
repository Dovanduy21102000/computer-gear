<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
        
        public function showResetForm($token)
        {
            $template = 'fontend.auth.reset_password';
            return view('fontend.layout', compact('template'))->with(['token' => $token]);
            
        }
    

        public function reset(Request $request)
        {
            $request->validate([
                'email'    => 'required|email',
                'password' => [
                    'required',
                    'string',
                    'min:8',          
                    'confirmed',      
                ],
                'token'    => 'required',
            ], [
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password.required' => 'Bạn chưa nhập mật khẩu.',
                'password.confirmed' => 'Hai mật khẩu không khớp.',
            ]);
    
            // Reset mật khẩu
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = bcrypt($password);
                    $user->save();
                }
            );
    
            return $status === Password::PASSWORD_RESET
                ? redirect()->route('home.index')->with('status', __('Your password has been reset successfully!'))
                : back()->withErrors(['email' => [__($status)]]);
        }
}