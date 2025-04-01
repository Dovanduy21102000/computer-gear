<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // Kiểm tra nếu trạng thái là 'pending' thì không thể xóa
        if ($contact->status === 'pending') {
            return redirect()->route('contacts.index')->with('error', 'Liên hệ đang chờ xử lý, không thể xóa!');
        }
    
        // Tiến hành xóa nếu không phải trạng thái 'pending'
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Liên hệ đã được xóa!');
    }
    

    
}
