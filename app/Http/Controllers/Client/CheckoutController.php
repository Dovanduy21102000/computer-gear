<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = 10; // Example user ID
        $user = User::find($userId);
        // Get the cart for the user
        $cart = Cart::where('user_id', $userId)->first();
        $cartItems = CartItem::with(['product', 'productVariant'])->where('cart_id', $cart->id)->get();

        // Retrieve applied coupon from session (if exists)
        $appliedCoupon = session('coupon', null);
        // dd($appliedCoupon);
        // Fetch provinces
        $response = Http::get('https://provinces.open-api.vn/api/p');
        $provinces = json_decode($response->body(), true) ?? [];

        // Fetch districts for each province
        $districtsByProvince = [];
        foreach ($provinces as $province) {
            $provinceId = $province['code'];
            $districtResponse = Http::get("https://provinces.open-api.vn/api/p/{$provinceId}?depth=2");
            $districtData = json_decode($districtResponse->body(), true);

            if (isset($districtData['districts'])) {
                $districtsByProvince[$provinceId] = $districtData['districts'];
            }
        }

        $template = 'fontend.checkout.index';
        return view('fontend.layout', compact('template', 'provinces', 'districtsByProvince', 'cartItems', 'appliedCoupon', 'user'));
    }

    public function checkout(Request $request)
    {
        $total = $request->total_price;
        $paymentMethod = $request->payment_method;
        $userId = 10; // Example user ID
        $user = User::find($userId);
        $cart = Cart::where('user_id', $userId)->first();

        $coupon = session('coupon', []);
        $coupon_code = $coupon['code'] ?? null;
        $coupon_discount = isset($coupon['discount']) ? (float) $coupon['discount'] : 0;

        session([
            'order_data' => [
                'user_id' => $user->id,
                'shipping_user_name' => $request->shipping_user_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'specific_address' => $request->specific_address,
                'coupon_code' => $coupon_code,
                'coupon_discount' => $coupon_discount,
                'total_price' => $total,
                'final_price' => max(0, $total - $coupon_discount),
                'payment_method' => $paymentMethod,
                'notes' => $request->notes,
            ]
        ]);

        if ($paymentMethod === 'vn_pay') {
            return redirect()->route('vnpay.create');
        }

        return $this->processCashPayment($cart);
    }

    public function processCheckout(Request $request)
    {
        $userId = 10;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $discount = session('coupon')['discount'] ?? 0;
        $total = max(0, $subtotal - $discount);

        $order = Order::create([
            'user_id' => $userId,
            'shipping_user_name' => $request->shipping_user_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'specific_address' => $request->specific_address,
            'coupon_code' => session('coupon')['code'] ?? null,
            'coupon_discount' => $discount,
            'total_price' => $subtotal,
            'final_price' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 0,
            'status' => 'pending',
        ]);

        if ($request->payment_method === 'vn_pay') {
            return redirect()->route('vnpay.create', ['order_id' => $order->id, 'amount' => $total]);
        } else {
            return $this->processCashPayment($cart);
        }
    }

    private function processCashPayment($cart)
    {
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }
}
