<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $template = 'backend.profile.show';
        $admin = Auth::user(); // Lấy thông tin người dùng đã đăng nhập
        if (!$admin || $admin->role !== 'admin') {
            // Nếu không phải admin, redirect về trang đăng nhập hoặc trang lỗi
            return redirect()->route('auth.admin')->withErrors(['access_denied' => 'Bạn không có quyền truy cập trang này!']);
        }
    
        return view('backend.dashboard.layout', compact('admin', 'template'));
    }
    
    
}
