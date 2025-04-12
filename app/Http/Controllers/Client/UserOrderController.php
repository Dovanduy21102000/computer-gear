<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

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
    $order = Order::where('code', $code)
        ->where('user_id', auth()->id()) // đảm bảo chỉ huỷ đơn của mình
        ->firstOrFail();

    if (!in_array($order->status, ['pending', 'processing'])) {
        return back()->with('error', 'Không thể huỷ đơn hàng này.');
    }

    $request->validate([
        'cancel_reason' => 'required|string|max:255',
    ]);

    if ($order->status === 'pending') {
        // Nếu đang chờ xác nhận → huỷ ngay
        $order->status = 'canceled';
        $order->cancel_reason = $request->cancel_reason;
        $order->save();
    
        // Trả lại số lượng sản phẩm
        foreach ($order->orderItems as $item) {
            if ($item->product_variant_id) {
                // Sản phẩm biến thể
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->quantity += $item->quantity;
                    $variant->save();
                }
    
                // Đồng thời cộng lại số lượng sản phẩm cha
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            } else {
                // Sản phẩm thường
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }
    
        return back()->with('success', 'Đơn hàng đã được huỷ.');
    } elseif ($order->status === 'processing') {
        // Nếu đang xử lý → yêu cầu huỷ
        $order->cancel_requested = true;
        $order->cancel_reason = $request->cancel_reason;
        $order->save();
    
        return back()->with('info', 'Đã gửi yêu cầu huỷ. Người bán sẽ xem xét phê duyệt.');
    }
    
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
