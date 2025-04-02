<?php


namespace App\Mail;

use App\Models\Contact; // Thêm dòng này để import model Contact
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact; // Đổi từ $contactData thành $contact

    /**
     * Tạo một instance mới
     */
    public function __construct(Contact $contact) // Sử dụng đối tượng Contact thay vì mảng
    {
        $this->contact = $contact;
    }

    /**
     * Xây dựng email
     */
    public function build()
    {
        return $this->subject('Liên hệ mới từ khách hàng')
                    ->view('fontend.emails.contact') // View email
                    ->with('contact', $this->contact); // Truyền đối tượng Contact vào view
    }
}

