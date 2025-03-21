<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Hiển thị danh sách liên hệ.
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);
        $template = 'backend.contacts.index';

        return view('backend.dashboard.layout', compact('contacts', 'template'));
    }

    /**
     * Hiển thị chi tiết liên hệ.
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        $template = 'backend.contacts.show';

        return view('backend.dashboard.layout', compact('template', 'contact'));
    }

    /**
     * Lưu thông tin liên hệ từ form.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'message.required' => 'Nội dung liên hệ không được để trống.',
        ];

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ], $messages);

        // Lưu thông tin user nếu có đăng nhập
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $data['ip_address'] = $request->ip(); // Lưu IP người gửi
        $data['status'] = 'pending'; // Mặc định là chưa xử lý

        Contact::create($data);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
    }

    /**
     * Cập nhật trạng thái liên hệ.
     */

     public function edit($id)
     {
         $contact = Contact::findOrFail($id);
         $template = 'backend.contacts.edit';
 
         return view('backend.dashboard.layout', compact('template', 'contact'));
     }
     public function update(Request $request, Contact $contact)
     {
         $request->validate([
             'status' => 'required|in:pending,resolved,spam',
         ]);
     
         // Kiểm tra nếu trạng thái hiện tại đã là 'resolved' thì không cho cập nhật lại
         if ($contact->status === 'resolved') {
             return redirect()->back()->with('error', 'Liên hệ đã được xử lý, không thể thay đổi trạng thái.');
         }
     
         $contact->update(['status' => $request->status]);
     
         return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
     }
     

    /**
     * Xóa liên hệ (xóa mềm).
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Liên hệ đã được xóa!');
    }

    
}
