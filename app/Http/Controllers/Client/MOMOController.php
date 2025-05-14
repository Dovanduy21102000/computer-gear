<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MOMOController extends Controller
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;

    public function __construct()
    {
        $this->endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $this->partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
        $this->accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
        $this->secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
    }

    public function createPayment(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

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
            session(['momo_selected_items' => $selectedItemIds]);

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

            // Generate a unique order code using structured timestamp without separators
            $orderCode = date('YmdHis') . rand(100, 999);

            // Create payment request
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = "Thanh toán qua MoMo";
            $amount = $finalPrice;
            $orderId = $orderCode;
            $redirectUrl = route('momo.return');
            $ipnUrl = route('momo.ipn');
            $extraData = json_encode(['selected_items' => $selectedItemIds]); // Store selected items in extraData

            $requestId = time() . "";
            $requestType = "payWithCC";
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $response = Http::post('https://test-payment.momo.vn/v2/gateway/api/create', [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => "vi",
                'requestType' => "payWithCC",
                'autoCapture' => true,
                'extraData' => $extraData,
                'signature' => $signature
            ]);

            $jsonResult = $response->json();

            // Log the full response for debugging
            Log::info('MOMO Payment Response:', [
                'status' => $response->status(),
                'response' => $jsonResult,
                'request_data' => [
                    'partnerCode' => $partnerCode,
                    'amount' => $amount,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'redirectUrl' => $redirectUrl,
                    'ipnUrl' => $ipnUrl,
                    'extraData' => $extraData,
                    'signature' => $signature
                ]
            ]);

            if (isset($jsonResult['payUrl'])) {
                return redirect($jsonResult['payUrl']);
            } else {
                $errorMessage = $jsonResult['message'] ?? 'Không thể tạo thanh toán. Vui lòng thử lại.';
                Log::error('MOMO Payment Error:', [
                    'error' => $errorMessage,
                    'response' => $jsonResult
                ]);
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('MOMO Payment Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function handleReturn(Request $request)
    {
        try {
            // Log the return data from MOMO
            Log::info('MOMO Return Data:', $request->all());

            // Check payment status
            if ($request->resultCode != 0) {
                Log::error('MOMO Payment Failed:', [
                    'resultCode' => $request->resultCode,
                    'message' => $request->message ?? 'Payment failed'
                ]);

                // Clear any existing sessions that might cause loops
                session()->forget('momo_selected_items');
                session()->forget('coupon');

                return redirect('/')
                    ->with('error', 'Thanh toán thất bại: ' . ($request->message ?? 'Vui lòng thử lại sau.'));
            }

            // Check if order already exists to prevent duplicate processing
            $existingOrder = Order::where('code', $request->orderId)->first();
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

            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng không tồn tại.');
            }

            // Get selected items from extraData or session
            $selectedItemIds = [];
            if ($request->extraData) {
                $extraData = json_decode($request->extraData, true);
                $selectedItemIds = $extraData['selected_items'] ?? [];
            }

            if (empty($selectedItemIds)) {
                $selectedItemIds = session('momo_selected_items', []);
            }

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
            if ($request->amount != $finalPrice) {
                Log::error('MOMO Amount Mismatch:', [
                    'expected' => $finalPrice,
                    'received' => $request->amount
                ]);
                throw new \Exception('Số tiền thanh toán không khớp. Vui lòng liên hệ hỗ trợ.');
            }

            // Create Order
            $order = Order::create([
                'code' => $request->orderId,
                'user_id' => $userId,
                'shipping_user_name' => session('momo_shipping_info.shipping_user_name'),
                'shipping_email' => session('momo_shipping_info.shipping_email'),
                'shipping_phone' => session('momo_shipping_info.shipping_phone'),
                'shipping_address' => session('momo_shipping_info.shipping_address'),
                'province_id' => session('momo_shipping_info.province_id'),
                'district_id' => session('momo_shipping_info.district_id'),
                'coupon_code' => $coupon['code'] ?? null,
                'coupon_discount' => $couponDiscount,
                'total_price' => $totalPrice,
                'final_price' => $finalPrice,
                'payment_status' => 1,
                'status' => 'pending',
                'payment_method' => 'momo',
                'notes' => session('momo_shipping_info.notes'),
            ]);

            // Record coupon usage
            if ($couponId) {
                CouponUser::create([
                    'user_id' => $userId,
                    'coupon_id' => $couponId,
                    'order_id' => $order->id
                ]);
            }

            // Save Order Items and Update Stock
            foreach ($cartItems as $item) {
                // Create order item
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

                // Update stock
                if ($item->productVariant) {
                    $item->productVariant->decrement('quantity', $item->quantity);
                } else {
                    $item->product->decrement('quantity', $item->quantity);
                }

                // Delete only this specific cart item
                $item->delete();
            }

            // Clear sessions
            session()->forget('coupon');
            session()->forget('momo_selected_items');
            session()->forget('momo_shipping_info');
            session()->forget('buy_now_item');

            DB::commit();

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MOMO Return Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function handleIPN(Request $request)
    {
        Log::info('MoMo IPN Data: ', $request->all());

        $order = Order::where('code', $request->input('orderId'))->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($request->input('resultCode') == 0) {
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
}
