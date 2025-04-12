<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelRequestStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $approved;

    public function __construct(Order $order, $approved)
    {
        $this->order = $order;
        $this->approved = $approved;
    }

    public function build()
    {
        $subject = $this->approved
            ? 'Đơn hàng #' . $this->order->code . ' đã được huỷ'
            : 'Yêu cầu huỷ đơn hàng #' . $this->order->code . ' đã bị từ chối';

        return $this->subject($subject)
            ->view('fontend.emails.cancel_request_status');
    }
}
