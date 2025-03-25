<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactClientController extends Controller
{
    /**
     * Hiển thị form liên hệ.
     */
    public function index()
    {
        $template = 'fontend.contacts.index'; // Đảm bảo bạn có view cho form liên hệ
        return view('fontend.layout', compact('template'));
    }

    /**
     * Xử lý gửi thông tin liên hệ.
     */
    public function store(Request $request)
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!Auth::check()) {
            // Nếu người dùng chưa đăng nhập, hiển thị thông báo lỗi và không cho gửi liên hệ
            return redirect()->route('client.contacts.index')->with('error', 'Bạn cần đăng nhập để gửi liên hệ.');
        }

        // Validate dữ liệu từ form
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Lưu thông tin người dùng nếu đã đăng nhập
        $data['user_id'] = Auth::id();

        // Tạo mới liên hệ
        Contact::create($data);

        // Chuyển hướng về trang liên hệ với thông báo thành công
        return redirect()->route('client.contacts.index')->with('success', 'Gửi liên hệ thành công!');
    }
}

