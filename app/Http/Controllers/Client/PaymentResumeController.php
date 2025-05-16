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
        $pendingPayments = PaymentAttempt::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('fontend.payment.resume', compact('pendingPayments'));
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
        session([
            'shipping_info' => $paymentAttempt->shipping_info,
            'coupon' => $paymentAttempt->coupon_info
        ]);

        // Redirect to appropriate payment method
        if ($paymentAttempt->payment_method === 'momo') {
            session(['momo_selected_items' => $paymentAttempt->selected_items]);
            return redirect()->route('momo.create');
        } else if ($paymentAttempt->payment_method === 'vn_pay') {
            session(['vnpay_selected_items' => $paymentAttempt->selected_items]);
            return redirect()->route('vnpay.create');
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
        return redirect()->route('payment.resume')->with('success', 'Đã hủy thanh toán.');
    }
}
