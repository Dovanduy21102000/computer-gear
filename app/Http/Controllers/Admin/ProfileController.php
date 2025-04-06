<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::user();
        
        if (!$admin || $admin->role !== 'admin') {
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }

        // Sử dụng template cho giao diện profile
        $template = 'backend.profile.show';

        return view('backend.dashboard.layout', compact('admin', 'template'));
    }

    public function edit()
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }

        // Sử dụng template cho giao diện chỉnh sửa profile
        $template = 'backend.profile.edit';

        return view('backend.dashboard.layout', compact('admin', 'template'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        try {
            // Xử lý ảnh đại diện (nếu có)
            if ($request->hasFile('avatar')) {
                // Lưu ảnh mới
                $avatarPath = $request->file('avatar')->store('avatars', 'public');

                // Xóa ảnh cũ nếu tồn tại
                if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                    Storage::disk('public')->delete($admin->avatar);
                }

                // Cập nhật đường dẫn avatar
                $validatedData['avatar'] = $avatarPath;
            }

            // Cập nhật thông tin admin
            $admin->update($validatedData);

            // Thông báo thành công
            return redirect()->route('backend.profile.show')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    public function changePassword(Request $request)
    {
        // Xác thực đầu vào
        $validated = $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',  // Kiểm tra mật khẩu mới và xác nhận
            'newPassword_confirmation' => 'required|string|min:8|same:newPassword',  // Kiểm tra mật khẩu xác nhận khớp với mật khẩu mới
        ]);
    
        $admin = auth()->user();
    
        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->currentPassword, $admin->password)) {
            return back()->withErrors(['currentPassword' => 'Mật khẩu hiện tại không đúng.']);
        }
    
        // Kiểm tra mật khẩu mới phải khác mật khẩu cũ
        if ($request->newPassword === $request->currentPassword) {
            return back()->withErrors(['newPassword' => 'Mật khẩu mới phải khác mật khẩu hiện tại.']);
        }
    
        // Cập nhật mật khẩu mới
        $admin->password = Hash::make($request->newPassword);
        $admin->save();
    
        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
    
    


    public function deleteImage()
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }

        try {
            // Kiểm tra nếu ảnh đại diện tồn tại
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                // Xóa ảnh đại diện
                Storage::disk('public')->delete($admin->avatar);

                // Cập nhật trường avatar trong cơ sở dữ liệu
                $admin->avatar = null;
                $admin->save();
            }

            // Thông báo thành công
            return redirect()->route('backend.profile.show')->with('success', 'Ảnh đại diện đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }
}
