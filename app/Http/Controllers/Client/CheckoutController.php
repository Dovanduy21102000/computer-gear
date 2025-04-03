<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // Get authenticated user ID
        $user = User::find($userId);

        // Get the cart for the user
        $cart = Cart::where('user_id', $userId)->first();

        // Check if cart exists and contains items
        if (!$cart || !CartItem::where('cart_id', $cart->id)->exists()) {
            return redirect()->route('home.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Get selected items from the cart
        $selectedItemIds = $request->input('selected_items', []); // Get the selected item IDs
        if (empty($selectedItemIds)) {
            return redirect()->route('cart.index')->with('error', 'Chưa có sản phẩm được chọn');
        }

        // Retrieve only selected items
        $cartItems = CartItem::with(['product', 'productVariant'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedItemIds) // Filter by selected items
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm hợp lệ.');
        }

        // Calculate the total price of selected items
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            // Check if the product has a sale price (price_sale)
            $price = $item->productVariant->price_sale ?? $item->productVariant->price ?? $item->product->price_sale ?? $item->product->price ?? 0;

            // Add the price multiplied by the quantity to the total price
            $totalPrice += $price * $item->quantity;
        }

        // Apply Coupon Discount
        $appliedCoupon = session('coupon', null);
        $discount = 0;

        if ($appliedCoupon) {
            if ($totalPrice >= $appliedCoupon['min_order_total']) { // Check if minimum order total is met
                if ($appliedCoupon['type'] === 'percentage') {
                    $discount = min($totalPrice * ($appliedCoupon['value'] / 100), $appliedCoupon['maximum_amount']);
                } else { // Fixed amount discount
                    $discount = min($totalPrice, $appliedCoupon['value']); // Ensure discount doesn't exceed total
                }
            }
        }

        // Final price after discount
        $finalPrice = max(0, $totalPrice - $discount);

        // Fetch provinces and districts for shipping information
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

        // Prepare the view with necessary data
        $template = 'fontend.checkout.index';
        return view('fontend.layout', compact('template', 'provinces', 'districtsByProvince', 'cartItems', 'appliedCoupon', 'discount', 'user', 'totalPrice', 'finalPrice'));
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
            return redirect()->route('order.track')->with('error', 'Không tìm thấy đơn hàng, vui lòng kiểm tra lại mã đơn hàng');
        }


        $template = 'fontend.home.check_order';
        return view('fontend.layout', compact('template', 'order'));
    }

    public function checkoutMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:momo,vn_pay,cash',
        ], [
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Invalid payment method selected.',
        ]);

        // Handle different payment methods
        if ($request->payment_method === 'momo') {
            return $this->redirectToPost(route('momo.create'), $request->all());
        } elseif ($request->payment_method === 'vn_pay') {
            return $this->redirectToPost(route('vnpay.create'), $request->all());
        } else {
            return redirect()->route('checkout.process')->with('success', 'Order placed successfully.');
        }
    }

    private function redirectToPost($url, $data)
    {
        return response()->view('fontend.checkout.post', ['url' => $url, 'data' => $data]);
    }

    public function processCheckout(Request $request)
    {

        $request->validate([
            'payment_method' => 'required|in:momo,cash,vnpay',
            'shipping_user_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
        ]);

        // dd($request->all());
        // Get User ID
        $userId = Auth::id(); // Replace with authenticated user

        // Check if Cart Exists
        $cart = Cart::where('user_id', $userId)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        if (!$cart || $cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Calculate Total Price from Cart Items (Fixing Missing Price Issue)
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $price = $item->productVariant->price ?? $item->product->price ?? 0; // Use variant price if available, else fallback to product price
            $totalPrice += $price * $item->quantity;
        }

        // Apply Coupon Discount
        $coupon = session('coupon', null);
        $couponDiscount = session('coupon.discount', 0);
        if ($coupon) {
            if ($totalPrice >= $coupon['min_order_total']) { // Check min order total condition
                if ($coupon['type'] === 'percentage') {
                    $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                } else { // Fixed amount discount
                    $couponDiscount = min($totalPrice, $coupon['value']); // Ensure it doesn't exceed total price
                }
                $couponId = $coupon['id'];
            }
        }
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

        if (!empty($couponId)) {
            DB::table('coupon_user')->insert([
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'created_at' => now(),
            ]);
        }        

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
        return redirect('/')->with('success', 'Thanh toán thành công!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->where('expire_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Mã khuyến mại không hợp lệ hoặc đã hết hạn.');
        }

        // Ensure user hasn't used the coupon before
        if (Auth::check()) {
            $couponUsed = CouponUser::where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->exists();
            if ($couponUsed) {
                return back()->with('error', 'Bạn dã sử dụng mã khuyến mại này rồi!');
            }
        }

        // Store coupon details in session
        session([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type, // 'fixed' or 'percentage'
                'value' => $coupon->price, // Discount value (amount or percentage)
                'maximum_amount' => $coupon->maximum_amount, // Limit discount for percentage type
                'min_order_total' => $coupon->min_order_total // Minimum order value required
            ]
        ]);

        return back()->with('success', 'Mã khuyến mại đã được áp dụng!');
    }
}
