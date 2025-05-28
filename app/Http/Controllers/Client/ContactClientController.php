<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Mail\ContactReplyMail;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            return redirect()->route('client.contacts.index')->with('error', 'Bạn cần đăng nhập để gửi liên hệ.');
        }
    
        // Validate dữ liệu
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
    
        // Gán ID của người dùng nếu đã đăng nhập
        $data['user_id'] = Auth::id();
    
        // Lưu vào database
        $contact = Contact::create($data);
    

        // Gửi email thông báo
        Mail::to('doduy21102000@gmail.com')->send(new ContactMail($contact));

    
        // Gửi email phản hồi lại cho người gửi
        Mail::to($contact->email)->send(new ContactReplyMail($contact));
    
        // Trả lại thông tin form cho người dùng và chuyển hướng
        return redirect()->route('client.contacts.index')
                         ->with('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi lại sớm.')
                         ->withInput(); // Giữ lại các giá trị đã nhập vào form
    }
    

}

