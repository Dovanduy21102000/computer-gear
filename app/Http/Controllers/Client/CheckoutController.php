<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();; // Example user ID
        $user = User::find($userId);

        // Get the cart for the user
        $cart = Cart::where('user_id', $userId)->first();

        // Check if cart exists and contains items
        if (!$cart || !CartItem::where('cart_id', $cart->id)->exists()) {
            return redirect()->route('home.index')->with('error', 'Your cart is empty.');
        }

        // Retrieve cart items
        $cartItems = CartItem::with(['product', 'productVariant'])->where('cart_id', $cart->id)->get();


        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $price = $item->productVariant->price ?? $item->product->price ?? 0;
            $totalPrice += $price * $item->quantity;
        }


        $appliedCoupon = session('coupon', null);
        $discount = 0;

        if ($appliedCoupon) {
            if ($totalPrice >= $appliedCoupon['min_order_total']) { // Check min order total
                if ($appliedCoupon['type'] === 'percentage') {
                    $discount = min($totalPrice * ($appliedCoupon['value'] / 100), $appliedCoupon['maximum_amount']);
                } else { // Fixed amount discount
                    $discount = min($totalPrice, $appliedCoupon['value']); // Ensure discount doesn't exceed total
                }
            }
        }


        $provinces = [];
        $districtsByProvince = [];


        $response = Http::get('https://provinces.open-api.vn/api/p');
        if ($response->successful()) {
            $provinces = json_decode($response->body(), true) ?? [];
        }

        // Fetch districts for each province
        foreach ($provinces as $province) {
            $provinceId = $province['code'];
            $districtResponse = Http::get("https://provinces.open-api.vn/api/p/{$provinceId}?depth=2");
            $districtData = json_decode($districtResponse->body(), true);

            if (isset($districtData['districts'])) {
                $districtsByProvince[$provinceId] = $districtData['districts'];
            }
        }


        $template = 'fontend.checkout.index';
        return view('fontend.layout', compact('template', 'provinces', 'districtsByProvince', 'cartItems', 'appliedCoupon', 'discount', 'user', 'totalPrice'));
    }

    public function trackOrderView(Request $request)
    {
        $template = 'fontend.home.check_order';
        return view('fontend.layout', compact('template'));
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string'
        ]);

        $order = Order::where('code', $request->order_code)
            ->with(['orderItems.product']) // Load related products
            ->first();

        if (!$order) {
            return redirect()->route('order.track')->with('error', 'Order not found. Please check your Order ID.');
        }


        $template = 'fontend.home.check_order';
        return view('fontend.layout', compact('template', 'order'));
    }


    public function processCheckout(Request $request)
    {

        // dd($request->all());
        // Get User ID
        $userId = Auth::id(); // Replace with authenticated user

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
        $couponDiscount = session('coupon.discount', 0);
        $finalPrice = max(0, $totalPrice - $couponDiscount); // Prevent negative price

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
            'status' => 'pending',
            'payment_method' => 'cash',
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
            Product::where('id', $item->product_id)->increment('quantity_sold', $item->quantity);
        }
        return redirect('/')->with('success', 'Thanh toán thành công!');
    }
}
