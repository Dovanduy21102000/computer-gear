<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->subject('Cảm ơn bạn đã liên hệ với chúng tôi!')
                    ->view('fontend.emails.contact_reply')
                    ->from('hiencoi250404@gmail.com', 'Computer Gear Shop') 
                    ->replyTo('hiencoi250404@gmail.com', 'Computer Gear Shop') 
                    ->to($this->contact->email) 
                    ->with([
                        'name' => $this->contact->name,
                    ]);
    }
    
    
    
}
