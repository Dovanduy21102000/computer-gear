<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PaymentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentResumeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $allPayments = PaymentAttempt::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                $payment->is_expired = $payment->expires_at && $payment->expires_at->lt(now());
                return $payment;
            });

        $template = 'fontend.payment.resume';
        return view('fontend.layout', ['allPayments' => $allPayments, 'template' => $template]);
    }

    public function resume($id)
    {
        $userId = Auth::id();
        $paymentAttempt = PaymentAttempt::where('user_id', $userId)
            ->where('id', $id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Restore session data
        $couponInfo = $paymentAttempt->coupon_info;
        if (is_string($couponInfo)) {
            $couponInfo = json_decode($couponInfo, true);
        }
        session([
            'shipping_info' => $paymentAttempt->shipping_info,
            'coupon' => $couponInfo
        ]);

        if ($paymentAttempt->payment_method === 'momo') {
            // Append a suffix to the order code for MoMo payment
            $paymentAttempt->order_code = $paymentAttempt->order_code . '_' . time();

            // Update expiration time
            $paymentAttempt->update([
                'expires_at' => now()->addMinutes(15)
            ]);

            session(['momo_selected_items' => $paymentAttempt->selected_items]);
            return view('fontend.payment.resume_momo_post', ['paymentAttempt' => $paymentAttempt]);
        } else if ($paymentAttempt->payment_method === 'vn_pay') {
            session(['vnpay_selected_items' => $paymentAttempt->selected_items]);
            return view('fontend.payment.resume_vnpay_post', ['paymentAttempt' => $paymentAttempt]);
        }

        return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

    public function cancel($id)
    {
        $userId = Auth::id();
        $paymentAttempt = PaymentAttempt::where('user_id', $userId)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $paymentAttempt->update(['status' => 'cancelled']);
        return redirect()->route('payment.resume.index')->with('success', 'Đã hủy thanh toán.');
    }
}
