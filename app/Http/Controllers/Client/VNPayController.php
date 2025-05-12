<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\vnpay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Check for buy now item first
            $buyNowItem = session('buy_now_item');
            if ($buyNowItem) {
                // Store buy now item in session for later use
                session(['vnpay_buy_now_item' => $buyNowItem]);

                // Calculate total price for buy now item
                $totalPrice = $buyNowItem->price * $buyNowItem->quantity;

                // Apply coupon if exists
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon) {
                    if ($totalPrice >= $coupon['min_order_total']) {
                        if ($coupon['type'] === 'percentage') {
                            $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                        } else {
                            $couponDiscount = min($totalPrice, $coupon['value']);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);
            } else {
                // Handle regular cart items
                $cart = Cart::where('user_id', $userId)->first();
                if (!$cart) {
                    return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
                }

                // Get selected items from the request
                $selectedItemIds = $request->input('selected_items', []);
                if (is_string($selectedItemIds)) {
                    $selectedItemIds = explode(',', $selectedItemIds);
                }
                $selectedItemIds = is_array($selectedItemIds) ? $selectedItemIds : [];

                if (empty($selectedItemIds)) {
                    return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán.');
                }

                $cartItems = CartItem::with(['product', 'productVariant'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $selectedItemIds)
                    ->get();

                if ($cartItems->isEmpty()) {
                    return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
                }

                // Store selected items in session for later use
                session(['vnpay_selected_items' => $selectedItemIds]);

                // Validate stock availability
                foreach ($cartItems as $item) {
                    if ($item->productVariant) {
                        if ($item->productVariant->quantity < $item->quantity) {
                            return back()->with('error', "Sản phẩm {$item->product->name} - {$item->productVariant->name} không đủ số lượng trong kho.");
                        }
                    } else {
                        if ($item->product->quantity < $item->quantity) {
                            return back()->with('error', "Sản phẩm {$item->product->name} không đủ số lượng trong kho.");
                        }
                    }
                }

                // Calculate Total Price
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $price = $item->productVariant ?
                        ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
                    $totalPrice += $price * $item->quantity;
                }

                // Apply Coupon Discount
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon) {
                    if ($totalPrice >= $coupon['min_order_total']) {
                        if ($coupon['type'] === 'percentage') {
                            $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                        } else {
                            $couponDiscount = min($totalPrice, $coupon['value']);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);
            }

            // Store shipping info in session
            session(['vnpay_shipping_info' => [
                'shipping_user_name' => $request->shipping_user_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'notes' => $request->notes,
            ]]);

            // Generate a unique order code using structured timestamp without separators
            $orderCode = date('YmdHis') . rand(100, 999);

            // VNPay payment request
            $amount = (int)($finalPrice * 100); // VNPay expects amount in VND * 100
            $order_info = "Thanh toán đơn hàng #{$orderCode}";
            $returnUrl = route('vnpay.return');
            Log::info('VNPay Return URL being sent:', ['returnUrl' => $returnUrl]);
            $date = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $date->modify('+15 minutes');
            $vnp_ExpireDate = $date->format('YmdHis');

            $inputData = array(
                "vnp_Amount" => $amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_ExpireDate" => $vnp_ExpireDate,
                "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => $order_info,
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => $returnUrl,
                "vnp_TmnCode" => env('VNP_TMN_CODE', 'G76FE03R'),
                "vnp_TxnRef" => $orderCode,
                "vnp_Version" => "2.1.0"
            );

            ksort($inputData);
            $hashData = http_build_query($inputData);
            $vnp_SecureHash = hash_hmac('sha512', $hashData, env('VNP_HASH_SECRET', 'LGNLFJHKB82W3JOHDTD7LMTNP8QBGG46'));
            $inputData['vnp_SecureHash'] = $vnp_SecureHash;
            $vnp_Url = env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html') . "?" . http_build_query($inputData);

            return redirect($vnp_Url);
        } catch (\Exception $e) {
            Log::error('VNPay Payment Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function handleReturn(Request $request)
    {
        try {
            Log::info('VNPay Return Data:', $request->all());

            if ($request->vnp_ResponseCode != '00') {
                Log::error('VNPay Payment Failed:', [
                    'response_code' => $request->vnp_ResponseCode,
                    'message' => $request->vnp_Message ?? 'Payment failed'
                ]);
                session()->forget('vnpay_selected_items');
                session()->forget('vnpay_buy_now_item');
                session()->forget('coupon');
                return redirect('/')
                    ->with('error', 'Thanh toán thất bại: ' . ($request->vnp_Message ?? 'Vui lòng thử lại sau.'));
            }

            $existingOrder = Order::where('code', $request->vnp_TxnRef)->first();
            if ($existingOrder) {
                Log::info('Order already processed:', ['order_id' => $existingOrder->id]);
                return redirect()->route('checkout.success', ['order_id' => $existingOrder->id])
                    ->with('success', 'Đặt hàng thành công!');
            }

            DB::beginTransaction();

            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Check for buy now item first
            $buyNowItem = session('vnpay_buy_now_item');
            if ($buyNowItem) {
                // Calculate total price for buy now item
                $totalPrice = $buyNowItem->price * $buyNowItem->quantity;

                // Apply coupon if exists
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon) {
                    if ($totalPrice >= $coupon['min_order_total']) {
                        if ($coupon['type'] === 'percentage') {
                            $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                        } else {
                            $couponDiscount = min($totalPrice, $coupon['value']);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);

                // Verify the amount matches
                $receivedAmount = $request->vnp_Amount / 100;
                if ($receivedAmount != $finalPrice) {
                    Log::error('VNPay Amount Mismatch:', [
                        'expected' => $finalPrice,
                        'received' => $receivedAmount
                    ]);
                    throw new \Exception('Số tiền thanh toán không khớp. Vui lòng liên hệ hỗ trợ.');
                }

                $shipping = session('vnpay_shipping_info', []);
                $order = Order::create([
                    'code' => $request->vnp_TxnRef,
                    'user_id' => $userId,
                    'shipping_user_name' => $shipping['shipping_user_name'] ?? null,
                    'shipping_email' => $shipping['shipping_email'] ?? null,
                    'shipping_phone' => $shipping['shipping_phone'] ?? null,
                    'shipping_address' => $shipping['shipping_address'] ?? null,
                    'province_id' => $shipping['province_id'] ?? null,
                    'district_id' => $shipping['district_id'] ?? null,
                    'coupon_code' => $coupon['code'] ?? null,
                    'coupon_discount' => $couponDiscount,
                    'total_price' => $totalPrice,
                    'final_price' => $finalPrice,
                    'payment_status' => 1,
                    'status' => 'pending',
                    'payment_method' => 'vn_pay',
                    'notes' => $shipping['notes'] ?? null,
                ]);

                if ($couponId) {
                    CouponUser::create([
                        'user_id' => $userId,
                        'coupon_id' => $couponId,
                        'order_id' => $order->id
                    ]);
                }

                // Create order item for buy now item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $buyNowItem->product->id,
                    'product_variant_id' => $buyNowItem->productVariant ? $buyNowItem->productVariant->id : null,
                    'price' => $buyNowItem->price,
                    'quantity' => $buyNowItem->quantity,
                    'product_info' => json_encode([
                        'product' => $buyNowItem->product->toArray(),
                        'variant' => $buyNowItem->productVariant ? $buyNowItem->productVariant->toArray() : null
                    ]),
                ]);

                // Update stock
                if ($buyNowItem->productVariant) {
                    $buyNowItem->productVariant->decrement('quantity', $buyNowItem->quantity);
                } else {
                    $buyNowItem->product->decrement('quantity', $buyNowItem->quantity);
                }
            } else {
                // Handle regular cart items
                $cart = Cart::where('user_id', $userId)->first();
                if (!$cart) {
                    return redirect()->route('cart.index')->with('error', 'Giỏ hàng không tồn tại.');
                }

                $selectedItemIds = session('vnpay_selected_items', []);
                if (empty($selectedItemIds)) {
                    return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán.');
                }

                $cartItems = CartItem::with(['product', 'productVariant'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $selectedItemIds)
                    ->get();

                if ($cartItems->isEmpty()) {
                    return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
                }

                // Validate stock availability
                foreach ($cartItems as $item) {
                    if ($item->productVariant) {
                        if ($item->productVariant->quantity < $item->quantity) {
                            throw new \Exception("Sản phẩm {$item->product->name} - {$item->productVariant->name} không đủ số lượng trong kho.");
                        }
                    } else {
                        if ($item->product->quantity < $item->quantity) {
                            throw new \Exception("Sản phẩm {$item->product->name} không đủ số lượng trong kho.");
                        }
                    }
                }

                // Calculate Total Price
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $price = $item->productVariant ?
                        ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
                    $totalPrice += $price * $item->quantity;
                }

                // Apply Coupon Discount
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon) {
                    if ($totalPrice >= $coupon['min_order_total']) {
                        if ($coupon['type'] === 'percentage') {
                            $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                        } else {
                            $couponDiscount = min($totalPrice, $coupon['value']);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);

                // Verify the amount matches
                $receivedAmount = $request->vnp_Amount / 100;
                if ($receivedAmount != $finalPrice) {
                    Log::error('VNPay Amount Mismatch:', [
                        'expected' => $finalPrice,
                        'received' => $receivedAmount
                    ]);
                    throw new \Exception('Số tiền thanh toán không khớp. Vui lòng liên hệ hỗ trợ.');
                }

                $shipping = session('vnpay_shipping_info', []);
                $order = Order::create([
                    'code' => $request->vnp_TxnRef,
                    'user_id' => $userId,
                    'shipping_user_name' => $shipping['shipping_user_name'] ?? null,
                    'shipping_email' => $shipping['shipping_email'] ?? null,
                    'shipping_phone' => $shipping['shipping_phone'] ?? null,
                    'shipping_address' => $shipping['shipping_address'] ?? null,
                    'province_id' => $shipping['province_id'] ?? null,
                    'district_id' => $shipping['district_id'] ?? null,
                    'coupon_code' => $coupon['code'] ?? null,
                    'coupon_discount' => $couponDiscount,
                    'total_price' => $totalPrice,
                    'final_price' => $finalPrice,
                    'payment_status' => 1,
                    'status' => 'pending',
                    'payment_method' => 'vn_pay',
                    'notes' => $shipping['notes'] ?? null,
                ]);

                if ($couponId) {
                    CouponUser::create([
                        'user_id' => $userId,
                        'coupon_id' => $couponId,
                        'order_id' => $order->id
                    ]);
                }

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'price' => $item->productVariant ?
                            ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price),
                        'quantity' => $item->quantity,
                        'product_info' => json_encode([
                            'product' => $item->product->toArray(),
                            'variant' => $item->productVariant ? $item->productVariant->toArray() : null
                        ]),
                    ]);

                    if ($item->productVariant) {
                        $item->productVariant->decrement('quantity', $item->quantity);
                    } else {
                        $item->product->decrement('quantity', $item->quantity);
                    }

                    $item->delete();
                }
            }

            // Clear sessions
            session()->forget('coupon');
            session()->forget('vnpay_selected_items');
            session()->forget('vnpay_buy_now_item');
            session()->forget('vnpay_shipping_info');
            session()->forget('buy_now_item');

            DB::commit();

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Return Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function ipn(Request $request)
    {
        Log::info('VNPay IPN Data:', $request->all());

        $order = Order::where('code', $request->vnp_TxnRef)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($request->vnp_ResponseCode == '00' && $request->vnp_TransactionStatus == '00') {
            $order->update([
                'payment_status' => 1,
                'status' => 'pending'
            ]);

            return response()->json(['message' => 'Order confirmed'], 200);
        } else {
            $order->update(['payment_status' => 0]);
            return response()->json(['message' => 'Payment failed'], 400);
        }
    }

    public function testPayment()
    {
        try {
            $amount = 1000000; // 10,000 VND in cents
            $orderCode = date('YmdHis') . rand(100, 999);
            $date = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $date->modify('+15 minutes');
            $vnp_ExpireDate = $date->format('YmdHis');

            // Create input data array with exact parameter order
            $inputData = array(
                "vnp_Amount" => $amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_ExpireDate" => $vnp_ExpireDate,
                "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => "Test Payment " . $orderCode,
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => "http://computer-gear.com:81/vnpay/return",
                "vnp_TmnCode" => "G76FE03R",
                "vnp_TxnRef" => $orderCode,
                "vnp_Version" => "2.1.0"
            );

            // Sort the data
            ksort($inputData);

            // Create hash data string using http_build_query
            $hashData = http_build_query($inputData);

            // Log the exact string being hashed
            Log::info('Test Payment - Hash Generation Details:', [
                'raw_input_data' => $inputData,
                'hash_data' => $hashData,
                'hash_secret' => 'LGNLFJHKB82W3JOHDTD7LMTNP8QBGG46',
                'hash_data_length' => strlen($hashData)
            ]);

            // Generate the hash
            $vnp_SecureHash = hash_hmac('sha512', $hashData, "LGNLFJHKB82W3JOHDTD7LMTNP8QBGG46");

            // Add secure hash to input data
            $inputData['vnp_SecureHash'] = $vnp_SecureHash;

            // Create final URL using the same http_build_query
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . http_build_query($inputData);

            // Log final details
            Log::info('Test Payment - Final Details:', [
                'hash_data' => $hashData,
                'secure_hash' => $vnp_SecureHash,
                'final_url' => $vnp_Url,
                'amount' => $amount,
                'order_code' => $orderCode
            ]);

            return redirect($vnp_Url);
        } catch (\Exception $e) {
            Log::error('Test Payment Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function debugPayment()
    {
        $amount = 10000; // 10,000 VND
        $orderCode = date('YmdHis') . rand(100, 999);
        $date = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
        $date->modify('+15 minutes');
        $vnp_ExpireDate = $date->format('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => "G76FE03R",
            "vnp_Amount" => 1000000,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Test Payment " . $orderCode,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => "http://computer-gear.com:81/vnpay/return",
            "vnp_SecureHashType" => "SHA256",
            "vnp_TxnRef" => $orderCode
        );

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $vnp_SecureHash = hash_hmac('sha512', $hashData, "LGNLFJHKB82W3JOHDTD7LMTNP8QBGG46");
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;

        return response()->json([
            'input_data' => $inputData,
            'hash_data' => $hashData,
            'secure_hash' => $vnp_SecureHash,
            'final_url' => "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . http_build_query($inputData)
        ]);
    }
}
