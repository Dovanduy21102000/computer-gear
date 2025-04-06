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
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Clear any existing coupon from session when entering checkout page
        session()->forget('coupon');

        $user = User::find($userId);
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Get selected items from the cart
        $selectedItemIds = $request->input('selected_items', []);

        // If no items are selected, show error
        if (empty($selectedItemIds)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }

        // Retrieve only selected items with their relationships
        $cartItems = CartItem::with(['product', 'productVariant.attributeValues.attribute'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedItemIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm hợp lệ.');
        }

        // Calculate the total price of selected items
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $price = $item->productVariant ?
                ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
            $totalPrice += $price * $item->quantity;
        }

        // Apply Coupon Discount
        $appliedCoupon = session('coupon');
        $discount = 0;

        if ($appliedCoupon && $totalPrice >= $appliedCoupon['min_order_total']) {
            if ($appliedCoupon['type'] === 'percentage') {
                $discount = min($totalPrice * ($appliedCoupon['value'] / 100), $appliedCoupon['maximum_amount']);
            } else {
                $discount = min($totalPrice, $appliedCoupon['value']);
            }
        }

        $finalPrice = max(0, $totalPrice - $discount);

        // Fetch provinces and districts
        $provinces = [];
        $districtsByProvince = [];

        try {
            $response = Http::get('https://provinces.open-api.vn/api/p');
            if ($response->successful()) {
                $provinces = $response->json() ?? [];

                // Fetch districts for each province
                foreach ($provinces as $province) {
                    $provinceId = $province['code'];
                    $districtResponse = Http::get("https://provinces.open-api.vn/api/p/{$provinceId}?depth=2");
                    if ($districtResponse->successful()) {
                        $districtData = $districtResponse->json();
                        if (isset($districtData['districts'])) {
                            $districtsByProvince[$provinceId] = $districtData['districts'];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching provinces/districts: ' . $e->getMessage());
        }

        $template = 'fontend.checkout.index';
        return view('fontend.layout', compact(
            'template',
            'provinces',
            'districtsByProvince',
            'cartItems',
            'appliedCoupon',
            'discount',
            'user',
            'totalPrice',
            'finalPrice'
        ));
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
            'payment_method' => 'required|in:momo,cash,vnpay',
        ], [
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Invalid payment method selected.',
        ]);

        // Handle different payment methods
        if ($request->payment_method === 'momo') {
            return $this->redirectToPost(route('momo.create'), $request->all());
        } elseif ($request->payment_method === 'vnpay') {
            return $this->redirectToPost(route('vnpay.create'), $request->all());
        } else {
            // For cash payment, submit the form directly to process checkout
            return $this->processCheckout($request);
        }
    }

    private function redirectToPost($url, $data)
    {
        // Extract selected items from the cart data
        $selectedItems = [];

        // Check if selected_items is already in the data
        if (isset($data['selected_items']) && is_array($data['selected_items'])) {
            $selectedItems = $data['selected_items'];
        }
        // Otherwise, extract from cart data
        else if (isset($data['cart']) && is_array($data['cart'])) {
            foreach ($data['cart'] as $itemId => $itemData) {
                if (isset($itemData['id'])) {
                    $selectedItems[] = $itemData['id'];
                }
            }
        }
        // Check for cart[item_id][id] format
        else {
            foreach ($data as $key => $value) {
                if (strpos($key, 'cart[') === 0 && strpos($key, '][id]') !== false) {
                    $selectedItems[] = $value;
                }
            }
        }

        // Add selected_items to the data
        $data['selected_items'] = $selectedItems;

        return view('fontend.checkout.post', ['url' => $url, 'data' => $data]);
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

            $cartItems = CartItem::with(['product', 'productVariant'])
                ->where('cart_id', $cart->id)
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
                'payment_status' => $request->payment_method === 'cash' ? 0 : 1,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
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
            }

            // Clear cart
            $cart->cartItems()->delete();
            $cart->delete();

            // Clear coupon session
            session()->forget('coupon');

            DB::commit();

            // Handle different payment methods
            switch ($request->payment_method) {
                case 'momo':
                    return redirect()->route('momo.process', ['order_id' => $order->id]);
                case 'vnpay':
                    return redirect()->route('vnpay.process', ['order_id' => $order->id]);
                default:
                    return redirect()->route('checkout.success', ['order_id' => $order->id])
                        ->with('success', 'Đặt hàng thành công!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
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

        // Store coupon details in session with a timestamp to track when it was applied
        session([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type, // 'fixed' or 'percentage'
                'value' => $coupon->price, // Discount value (amount or percentage)
                'maximum_amount' => $coupon->maximum_amount, // Limit discount for percentage type
                'min_order_total' => $coupon->min_order_total, // Minimum order value required
                'applied_at' => now()->timestamp // Add timestamp to track when coupon was applied
            ]
        ]);

        return back()->with('success', 'Mã khuyến mại đã được áp dụng!');
    }

    public function success(Request $request)
    {
        $order = Order::with(['orderItems.product', 'orderItems.productVariant'])
            ->findOrFail($request->order_id);

        $template = 'fontend.checkout.success';
        return view('fontend.layout', compact('template', 'order'));
    }
}
