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
use App\Models\PaymentAttempt;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Restore coupon from request if present
            if ($request->has('coupon')) {
                $couponData = is_string($request->coupon) ? json_decode($request->coupon, true) : $request->coupon;
                session(['coupon' => $couponData]);
            }

            // Log the coupon state
            Log::info('VNPay - Coupon state at payment creation:', [
                'coupon_in_request' => $request->coupon,
                'coupon_in_session' => session('coupon')
            ]);

            // Check for buy now item first
            $buyNowItem = session('buy_now_item');
            if ($buyNowItem) {
                // Ensure buyNowItem has a product_id property
                if (!isset($buyNowItem->product_id)) {
                    $buyNowItem->product_id = $buyNowItem->id; // Fallback to id if product_id is not set
                }
                // Calculate total price for buy now item
                $totalPrice = $buyNowItem->price * $buyNowItem->quantity;

                // Apply coupon if exists
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon && is_array($coupon)) {
                    if ($totalPrice >= ($coupon['min_order_total'] ?? 0)) {
                        if (isset($coupon['type']) && $coupon['type'] === 'percent') {
                            $percentageDiscount = $totalPrice * ($coupon['price'] / 100);
                            $couponDiscount = isset($coupon['maximum_amount']) && $coupon['maximum_amount'] > 0
                                ? min($percentageDiscount, $coupon['maximum_amount'])
                                : $percentageDiscount;
                        } else {
                            $couponDiscount = min($coupon['price'], $totalPrice);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);

                // Log price calculations
                Log::info('VNPay - Price calculations (Buy Now):', [
                    'totalPrice' => $totalPrice,
                    'coupon' => $coupon,
                    'couponDiscount' => $couponDiscount,
                    'finalPrice' => $finalPrice
                ]);

                // Store buy now item in session for later use
                session(['vnpay_buy_now_item' => $buyNowItem]);

                if ($finalPrice < 10000) {
                    return redirect('/')->with('error', 'Số tiền giao dịch không hợp lệ. Số tiền hợp lệ phải từ 10.000 VND trở lên.');
                }
            } else {
                // Regular cart items logic
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

                if ($coupon && is_array($coupon)) {
                    if ($totalPrice >= ($coupon['min_order_total'] ?? 0)) {
                        if (isset($coupon['type']) && $coupon['type'] === 'percent') {
                            $percentageDiscount = $totalPrice * ($coupon['price'] / 100);
                            $couponDiscount = isset($coupon['maximum_amount']) && $coupon['maximum_amount'] > 0
                                ? min($percentageDiscount, $coupon['maximum_amount'])
                                : $percentageDiscount;
                        } else {
                            $couponDiscount = min($coupon['price'], $totalPrice);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);

                // Log price calculations
                Log::info('VNPay - Price calculations:', [
                    'totalPrice' => $totalPrice,
                    'coupon' => $coupon,
                    'couponDiscount' => $couponDiscount,
                    'finalPrice' => $finalPrice
                ]);

                if ($finalPrice < 10000) {
                    return redirect('/')->with('error', 'Số tiền giao dịch không hợp lệ. Số tiền hợp lệ phải từ 10.000 VND trở lên.');
                }
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

            // Use existing order code if provided, otherwise generate new one
            $orderCode = $request->input('order_code') ?? (date('YmdHis') . rand(100, 999));

            // Check if payment attempt already exists
            $paymentAttempt = PaymentAttempt::where('order_code', $orderCode)->first();

            if ($paymentAttempt) {
                // Update existing payment attempt
                $paymentAttempt->update([
                    'amount' => $finalPrice,
                    'status' => 'pending',
                    'selected_items' => $selectedItemIds ?? null,
                    'shipping_info' => session('vnpay_shipping_info'),
                    'coupon_info' => $coupon,
                    'expires_at' => now()->addMinutes(15)
                ]);
            } else {
                // Create new payment attempt
                PaymentAttempt::create([
                    'user_id' => $userId,
                    'payment_method' => 'vn_pay',
                    'order_code' => $orderCode,
                    'amount' => $finalPrice,
                    'status' => 'pending',
                    'selected_items' => $selectedItemIds ?? null,
                    'shipping_info' => session('vnpay_shipping_info'),
                    'coupon_info' => $coupon,
                    'expires_at' => now()->addMinutes(15)
                ]);
            }

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
                // Mark payment attempt as completed
                \App\Models\PaymentAttempt::where('order_code', $existingOrder->code)
                    ->update(['status' => 'completed']);
                Log::info('Order already processed:', ['order_id' => $existingOrder->id]);
                return redirect()->route('checkout.success', ['order_id' => $existingOrder->id])
                    ->with('success', 'Đặt hàng thành công!');
            }

            DB::beginTransaction();

            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Use PaymentAttempt data
            $paymentAttempt = \App\Models\PaymentAttempt::where('order_code', $request->vnp_TxnRef)->first();
            if (!$paymentAttempt) {
                throw new \Exception('Không tìm thấy thông tin thanh toán. Vui lòng liên hệ hỗ trợ.');
            }
            $expectedAmount = $paymentAttempt->amount;
            $receivedAmount = $request->vnp_Amount / 100;
            if ($expectedAmount != $receivedAmount) {
                Log::error('VNPay Amount Mismatch:', [
                    'expected' => $expectedAmount,
                    'received' => $receivedAmount
                ]);
                throw new \Exception('Số tiền thanh toán không khớp. Vui lòng liên hệ hỗ trợ.');
            }
            $coupon = $paymentAttempt->coupon_info;
            $selectedItemIds = $paymentAttempt->selected_items ?? [];
            $shipping = $paymentAttempt->shipping_info ?? [];

            // Handle buy now or cart
            $cartItems = [];
            if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
                $cart = Cart::where('user_id', $userId)->first();
                $cartItems = CartItem::with(['product', 'productVariant'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $selectedItemIds)
                    ->get();
            } else {
                $buyNowItem = session('vnpay_buy_now_item');
                if ($buyNowItem) {
                    // Always reload the product and variant from the database
                    $product = \App\Models\Product::find($buyNowItem->product_id ?? $buyNowItem->product->id ?? $buyNowItem->id);
                    $variant = null;
                    if ((isset($buyNowItem->product_variant_id) && $buyNowItem->product_variant_id) || (isset($buyNowItem->productVariant) && isset($buyNowItem->productVariant->id))) {
                        $variantId = $buyNowItem->product_variant_id ?? $buyNowItem->productVariant->id;
                        $variant = \App\Models\ProductVariant::find($variantId);
                    }
                    $finalPriceForOrderItem = $variant
                        ? ($variant->price_sale ?? $variant->price)
                        : ($product->price_sale ?? $product->price);
                    if (is_null($finalPriceForOrderItem)) {
                        throw new \Exception('Không thể xác định giá sản phẩm. Vui lòng kiểm tra lại biến thể hoặc sản phẩm.');
                    }
                    // Create a cart item instance for buy now
                    $cartItem = new \App\Models\CartItem();
                    $cartItem->product_id = $product->id;
                    $cartItem->product_variant_id = $variant ? $variant->id : null;
                    $cartItem->quantity = $buyNowItem->quantity;
                    $cartItem->price = $finalPriceForOrderItem;
                    $cartItems[] = $cartItem;
                }
            }

            // Calculate total price (for record)
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $price = $item->productVariant ?
                    ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
                $totalPrice += $price * $item->quantity;
            }

            $couponDiscount = 0;
            $couponId = null;
            if ($coupon && isset($coupon['type']) && $coupon['type'] === 'percent') {
                $percentageDiscount = $totalPrice * ($coupon['price'] / 100);
                $couponDiscount = isset($coupon['maximum_amount']) && $coupon['maximum_amount'] > 0
                    ? min($percentageDiscount, $coupon['maximum_amount'])
                    : $percentageDiscount;
                $couponId = $coupon['id'] ?? null;
            } elseif ($coupon && isset($coupon['price'])) {
                $couponDiscount = min($coupon['price'], $totalPrice);
                $couponId = $coupon['id'] ?? null;
            }
            $finalPrice = max(0, $totalPrice - $couponDiscount);

            // Create Order
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
                $couponUser = \App\Models\CouponUser::where('user_id', $userId)
                    ->where('coupon_id', $couponId)
                    ->first();
                if ($couponUser) {
                    $couponUser->used = 1;
                    $couponUser->save();
                } else {
                    \App\Models\CouponUser::create([
                        'user_id' => $userId,
                        'coupon_id' => $couponId,
                        'used' => 1
                    ]);
                }
            }

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_variant_id' => $item->productVariant ? $item->productVariant->id : null,
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
            }

            // Clear sessions
            session()->forget('coupon');
            session()->forget('vnpay_selected_items');
            session()->forget('vnpay_buy_now_item');
            session()->forget('vnpay_shipping_info');
            session()->forget('buy_now_item');

            DB::commit();

            // Delete cart items AFTER successful transaction
            if (isset($cartItems)) {
                foreach ($cartItems as $item) {
                    if (isset($item->id)) {
                        $item->delete();
                    }
                }
            }

            // Mark payment attempt as completed
            \App\Models\PaymentAttempt::where('order_code', $order->code)
                ->update(['status' => 'completed']);

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
