<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartItem;
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
        $request->validate([
            'shipping_user_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // Get User ID
            $userId = Auth::id();
            if (!$userId) {
                throw new \Exception('Vui lòng đăng nhập để tiếp tục.');
            }

            // Check if Cart Exists
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                throw new \Exception('Giỏ hàng không tồn tại.');
            }

            // Get selected items from the cart
            $selectedItemIds = [];

            // Check if selected_items is provided directly
            if ($request->has('selected_items')) {
                $selectedItemIds = $request->input('selected_items', []);
            }
            // Check if cart data is provided in the format cart[item_id][id]
            else if ($request->has('cart')) {
                foreach ($request->input('cart') as $itemId => $itemData) {
                    if (isset($itemData['id'])) {
                        $selectedItemIds[] = $itemData['id'];
                    }
                }
            }

            // If no items are selected, show error
            if (empty($selectedItemIds)) {
                // Log the request data for debugging
                Log::info('MOMO Payment Request Data:', $request->all());
                throw new \Exception('Vui lòng chọn sản phẩm để thanh toán');
            }

            // Retrieve only selected items with their relationships
            $cartItems = CartItem::with(['product', 'productVariant'])
                ->where('cart_id', $cart->id)
                ->whereIn('id', $selectedItemIds)
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
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

            // Create Order
            $order = Order::create([
                'code' => 'ORD' . time() . rand(1000, 9999),
                'user_id' => $userId,
                'shipping_user_name' => $request->shipping_user_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'coupon_code' => $coupon['code'] ?? null,
                'coupon_discount' => $couponDiscount,
                'total_price' => $totalPrice,
                'final_price' => $finalPrice,
                'payment_status' => 0,
                'status' => 'pending',
                'payment_method' => 'momo',
                'notes' => $request->notes,
            ]);

            // Record coupon usage
            if ($couponId) {
                DB::table('coupon_user')->insert([
                    'user_id' => $userId,
                    'coupon_id' => $couponId,
                    'order_id' => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
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
            }

            // Remove only the selected items from the cart
            CartItem::whereIn('id', $selectedItemIds)->delete();

            // Check if cart is empty after removing selected items
            $remainingItems = CartItem::where('cart_id', $cart->id)->count();
            if ($remainingItems == 0) {
                $cart->delete();
            }

            // Clear coupon session
            session()->forget('coupon');

            DB::commit();

            // MoMo API Request
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
            $accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
            $secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
            $orderInfo = "Thanh toán đơn hàng " . $order->code;
            $amount = $order->final_price;
            $orderId = $order->code;
            $redirectUrl = route('momo.return');
            $ipnUrl = route('momo.ipn');
            $extraData = "";

            $requestId = time() . "";
            $requestType = "payWithCC";

            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            $response = Http::post($endpoint, $data);
            $result = $response->json();

            if (isset($result['payUrl'])) {
                return redirect($result['payUrl']); // Redirect to MoMo payment
            }

            return back()->with('error', 'Lỗi khi tạo thanh toán MoMo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }


    public function handleReturn(Request $request)
    {
        $order = Order::where('code', $request->input('orderId'))->first();
        // dd(12323);

        if (!$order) {
            return redirect()->route('checkout.success', ['order_id' => null])
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($request->input('resultCode') == 0) {
            // dd(1123);
            $order->update([
                'payment_status' => 1,
                'status' => 'pending'
            ]);

            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('success', 'Thanh toán thành công!');
        } else {
            $order->update(['payment_status' => 0]);

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('error', 'Thanh toán thất bại!');
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
