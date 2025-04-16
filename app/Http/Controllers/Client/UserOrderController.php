<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\RateLimiter;

class UserOrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $template = 'fontend.oders.index';
        return view('fontend.layout', compact('orders', 'template'));
    }


    public function show($code)
    {
        $order = Order::with(['items.product', 'items.productVariant.attributeValues.attribute'])
            ->where('code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();


        $template = 'fontend.oders.show';
        return view('fontend.layout', compact('order', 'template'));
    }

    public function cancel(Request $request, $code)
    {
        $user = auth()->user();

        // Kiểm tra số lần huỷ đơn của người dùng trong 1 giờ
        $key = 'cancel-attempts:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->with('error', 'Bạn đã huỷ đơn quá nhiều lần. Vui lòng thử lại sau 1 giờ.');
        }
        RateLimiter::hit($key, 3600);


        $order = Order::where('code', $code)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Kiểm tra trạng thái đơn hàng có cho phép huỷ không
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Không thể huỷ đơn hàng này.');
        }

        
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

       
        $order->cancel_requested = true;  // Đánh dấu yêu cầu huỷ
        $order->cancel_reason = $request->cancel_reason;
        $order->status = 'pending_cancel';
        $order->save();
        return back()->with('info', 'Đã gửi yêu cầu huỷ. Người bán sẽ xem xét phê duyệt.');
    }



    public function confirmReceived($code)
    {
        $order = Order::where('code', $code)->firstOrFail();

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn hàng đang giao.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Cảm ơn bạn đã xác nhận đã nhận hàng!');
    }
}
