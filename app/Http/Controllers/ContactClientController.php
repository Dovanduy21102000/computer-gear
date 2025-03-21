<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactClientController extends Controller
{
    /**
     * Hiển thị trang danh sách liên hệ (nếu cần)
     */
    public function index()
    {
        $contacts = Contact::where('status', 'pending')->get(); // Lấy các liên hệ chưa xử lý
        $template = 'fontend.contacts.index';
        return view('fontend.layout', compact('template', 'contacts'));
    }

    /**
     * Hiển thị form liên hệ
     */
  

    /**
     * Xử lý gửi thông tin liên hệ
     */
    public function store(Request $request)
{
    $messages = [
        'name.required' => 'Vui lòng nhập tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
        'message.required' => 'Vui lòng nhập nội dung liên hệ.',
    ];

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ], $messages);

    if (Auth::check()) {
        $data['user_id'] = Auth::id();
    }

    $data['ip_address'] = $request->ip();
    $data['status'] = 'pending';

    Contact::create($data);

    // Chuyển hướng đến trang form rỗng thay vì quay lại trang cũ
    return redirect()->route('contact.create')->with('success', 'Gửi liên hệ thành công!');
}

}
