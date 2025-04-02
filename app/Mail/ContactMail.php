<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;

    /**
     * Tạo một instance mới
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Xây dựng email
     */
    public function build()
    {
        return $this->subject('Liên hệ mới từ khách hàng')
                    ->view('fontend.emails.contact')
                    ->with('contactData', $this->contactData);
    }
}
