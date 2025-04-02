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
        // Get User ID
        $userId = Auth::id();

        // Check if Cart Exists
        $cart = Cart::where('user_id', $userId)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        if (!$cart || $cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Calculate Total Price from Cart Items (Fixing Missing Price Issue)
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $price = $item->productVariant->price ?? $item->product->price ?? 0; // Use variant price if available, else fallback to product price
            $totalPrice += $price * $item->quantity;
        }

        // Apply Coupon Discount
        $coupon = session('coupon', null);
        $couponDiscount = 0;

        if ($coupon) {
            if ($totalPrice >= $coupon['min_order_total']) { // Check min order total condition
                if ($coupon['type'] === 'percentage') {
                    $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                } else { // Fixed amount discount
                    $couponDiscount = min($totalPrice, $coupon['value']); // Ensure it doesn't exceed total price
                }
            }
        }

        $finalPrice = max(0, $totalPrice - $couponDiscount); // Prevent negative prices

        // Create Order Before Payment
        $order = Order::create([
            'code' => 'ORD' . time(),
            'user_id' => $userId,
            'shipping_user_name' => $request->shipping_user_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'coupon_code' => session('coupon.code', null),
            'coupon_discount' => $couponDiscount,
            'total_price' => $totalPrice,
            'final_price' => $finalPrice,
            'payment_status' => 0,
            'status' => 'processing',
            'payment_method' => 'momo',
            'notes' => $request->notes,
        ]);

        // Save Order Items (Fixed: Include Products with & without Variants)
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id ?? null, // Allow null for non-variant products
                'price' => $item->productVariant->price ?? $item->product->price ?? 0, // Use variant price or fallback to product price
                'price_sale' => $item->productVariant->price_sale ?? null, // Use variant sale price if available
                'quantity' => $item->quantity,
                'product_info' => json_encode($item->product->toArray()),
            ]);
            $product = Product::find($item->product_id);

            if ($item->product_variant_id) {
                $productVariant = ProductVariant::find($item->product_variant_id);
                if ($productVariant) {
                    $productVariant->decrement('quantity', $item->quantity);
                }
            } else {
                $product->decrement('quantity', $item->quantity);
            }

            Product::where('id', $item->product_id)->increment('quantity_sold', $item->quantity);
        }

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
    }


    public function handleReturn(Request $request)
    {
        $order = Order::where('code', $request->input('orderId'))->first();
        // dd(12323);

        if (!$order) {
            return redirect('/')->with('error', 'Order not found.');
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

            return redirect('/')->with('success', 'Thanh toán thành công!');
        } else {
            $order->update(['payment_status' => 0]);

            return redirect('/')->with('error', 'Thanh toán thất bại!');
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
