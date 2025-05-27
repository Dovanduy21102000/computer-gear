<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Advertise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Hiển thị trang tài khoản người dùng
    public function show()
    {
        $user = auth()->user();
        $template = 'fontend.home.show_user';
        return view('fontend.layout', compact('user', 'template'));
    }


    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'alert' => [
                    'type' => 'warning',
                    'title' => 'Cảnh Báo',
                    'content' => 'Bạn phải đăng nhập để sử dụng chức năng này!'
                ]
            ]);
        }

        $template = 'fontend.home.edit_user';
        $user = Auth::user();
        return view('fontend.layout', compact('user', 'template'));
    }
    public function save(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'alert' => [
                    'type' => 'warning',
                    'title' => 'Cảnh Báo',
                    'content' => 'Bạn phải đăng nhập để sử dụng chức năng này!'
                ]
            ]);
        }

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Xác thực dữ liệu đầu vào
        $rules = [
            'name' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ];

        if ($request->phone != $user->phone) {
            $rules['phone'] = 'required|string|size:10|regex:/^0[^6421][0-9]{8}$/|unique:users';
        }

        $messages = [
            'name.required' => 'Tên không được để trống!',
            'name.string' => 'Tên phải là một chuỗi ký tự!',
            'name.max' => 'Tên không được vượt quá :max kí tự!',
            'phone.required' => 'Số điện thoại không được để trống!',
            'phone.string' => 'Số điện thoại phải là một chuỗi ký tự!',
            'phone.size' => 'Số điện thoại phải có độ dài :size chữ số!',
            'phone.regex' => 'Số điện thoại không hợp lệ!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',
            'address.string' => 'Địa chỉ phải là một chuỗi ký tự!',
            'address.max' => 'Địa chỉ không được vượt quá :max kí tự!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        if ($request->phone != $user->phone) {
            $user->phone = $request->phone;
        }
        $user->address = $request->address;

        if ($request->hasFile('avatars')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatars')->store('users', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('user.show')->with([
            'alert' => [
                'content' => 'Cập nhật thông tin tài khoản thành công.'
            ]
        ]);
    }

    public function changePassword(Request $request)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'alert' => [
                    'type' => 'warning',
                    'title' => 'Cảnh Báo',
                    'content' => 'Bạn phải đăng nhập để sử dụng chức năng này!'
                ]
            ]);
        }

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->back()->with([
                'alert' => [
                    'content' => 'Mật khẩu hiện tại không chính xác.'
                ]
            ])->withInput();
        }

        // Xác thực đầu vào
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ], [
            'currentPassword.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'newPassword.required' => 'Vui lòng nhập mật khẩu mới.',
            'newPassword.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'newPassword.confirmed' => 'Mật khẩu mới và xác nhận không khớp.',
        ]);


        $user->password = Hash::make($request->newPassword);
        $user->save();


        return redirect()->route('user.show')->with([
            'success' => 'Cập nhật mật khẩu thành công!',
        ]);
    }
}
