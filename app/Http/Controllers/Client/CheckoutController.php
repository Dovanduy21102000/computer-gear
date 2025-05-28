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
use Illuminate\Support\Facades\Cache;
use App\Events\CheckoutSessionUpdated;
use App\Models\CheckoutSession;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            // Log::info('User data in checkout:', [
            //     'user_id' => $user ? $user->id : null,
            //     'user_name' => $user ? $user->name : null,
            //     'is_authenticated' => Auth::check()
            // ]);

            $addresses = $user ? $user->addresses : [];
            $selectedItems = [];
            $buyNowItem = session('buy_now_item');
            $cartItems = [];

            // Get provinces data from cache
            $provinces = Cache::remember('provinces', now()->addDays(30), function () {
                try {
                    $response = Http::timeout(30)->get('https://provinces.open-api.vn/api/');
                    return $response->json();
                } catch (\Exception $e) {
                    // Log::error('Error fetching provinces:', [
                    //     'error' => $e->getMessage(),
                    //     'trace' => $e->getTraceAsString()
                    // ]);
                    return [];
                }
            });

            // Get districts data from cache
            $districtsByProvince = Cache::remember('districts_by_province', now()->addDays(30), function () use ($provinces) {
                $districts = [];
                foreach ($provinces as $province) {
                    try {
                        $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/p/{$province['code']}?depth=2");
                        $districts[$province['code']] = $response->json()['districts'] ?? [];
                    } catch (\Exception $e) {
                        // Log::error("Error fetching districts for province {$province['code']}:", [
                        //     'error' => $e->getMessage(),
                        //     'trace' => $e->getTraceAsString()
                        // ]);
                        $districts[$province['code']] = [];
                    }
                }
                return $districts;
            });

            // If buy now item exists, use it instead of cart items
            if ($buyNowItem) {
                $cartItems[] = $buyNowItem;
            } else {
                // Get selected items from URL parameters
                if ($request->has('selected_items')) {
                    $selectedItems = explode(',', $request->input('selected_items'));
                }

                // Log::info('Selected items from URL:', ['selected_items' => $selectedItems]);

                if (!empty($selectedItems)) {
                    // Get cart items from database for selected items
                    $cart = Cart::where('user_id', Auth::id())->first();
                    if ($cart) {
                        $cartItems = CartItem::with(['product', 'productVariant'])
                            ->where('cart_id', $cart->id)
                            ->whereIn('id', $selectedItems)
                            ->get()
                            ->map(function ($item) {
                                $cartItem = new \stdClass();
                                $cartItem->id = $item->id;
                                $cartItem->product = $item->product;
                                $cartItem->productVariant = $item->productVariant;
                                $cartItem->quantity = $item->quantity;
                                $cartItem->price = $item->productVariant ?
                                    ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
                                return $cartItem;
                            });
                    }

                    // Log::info('Cart items from database for selected items:', [
                    //     'items' => $cartItems
                    // ]);
                }
            }

            // Calculate total price
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += $item->price * $item->quantity;
            }

            // Apply coupon if exists
            $coupon = session('coupon');
            $couponDiscount = 0;
            if ($coupon && isset($coupon['type']) && $coupon['type'] === 'percent') {
                $percentageDiscount = $totalPrice * ($coupon['price'] / 100);
                $couponDiscount = isset($coupon['maximum_amount']) && $coupon['maximum_amount'] > 0
                    ? min($percentageDiscount, $coupon['maximum_amount'])
                    : $percentageDiscount;
            } elseif ($coupon && isset($coupon['price'])) {
                $couponDiscount = min($coupon['price'], $totalPrice);
            }

            $finalPrice = max(0, $totalPrice - $couponDiscount);

            // Log coupon in session
            Log::info('Coupon in session at checkout:', ['coupon' => session('coupon')]);

            $template = 'fontend.checkout.index';
            return view('fontend.layout', compact(
                'addresses',
                'cartItems',
                'totalPrice',
                'couponDiscount',
                'finalPrice',
                'coupon',
                'provinces',
                'districtsByProvince',
                'template',
                'user'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function getCartItems($user)
    {
        return CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['product', 'productVariant'])
            ->get()
            ->map(function ($item) {
                if (!$item->product) {
                    return null;
                }

                $price = 0;
                if ($item->productVariant) {
                    $price = $item->productVariant->price_sale ?? $item->productVariant->price ?? 0;
                } else {
                    $price = $item->product->price_sale ?? $item->product->price ?? 0;
                }

                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function calculateTotal($user)
    {
        return CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['product', 'productVariant'])
            ->get()
            ->sum(function ($item) {
                if (!$item->product) {
                    return 0;
                }

                $price = 0;
                if ($item->productVariant) {
                    $price = $item->productVariant->price_sale ?? $item->productVariant->price ?? 0;
                } else {
                    $price = $item->product->price_sale ?? $item->product->price ?? 0;
                }

                return $item->quantity * $price;
            });
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
            'payment_method' => 'required|in:momo,cash,vn_pay',
            'shipping_user_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
        ], [
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Invalid payment method selected.',
            'shipping_user_name.required' => 'Vui lòng nhập họ tên người nhận hàng',
            'shipping_email.required' => 'Vui lòng nhập email người nhận hàng',
            'shipping_phone.required' => 'Vui lòng nhập số điện thoại người nhận hàng',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố',
            'district_id.required' => 'Vui lòng chọn quận/huyện',
        ]);

        // Store shipping information in session
        session([
            'momo_shipping_info' => [
                'shipping_user_name' => $request->shipping_user_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'notes' => $request->notes,
            ]
        ]);

        // Handle different payment methods
        if ($request->payment_method === 'momo') {
            return $this->redirectToPost(route('momo.create'), $request->all());
        } elseif ($request->payment_method === 'vn_pay') {
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
        // If selected_items is a string (comma-separated), convert to array
        else if (isset($data['selected_items']) && is_string($data['selected_items'])) {
            $selectedItems = explode(',', $data['selected_items']);
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

        // Ensure selected_items is always an array
        $data['selected_items'] = is_array($selectedItems) ? $selectedItems : [];

        // Preserve the coupon in session
        if (session()->has('coupon')) {
            $data['coupon'] = session('coupon');
        }

        // Log the data being passed
        Log::info('Redirecting to payment with data:', [
            'url' => $url,
            'data' => $data,
            'coupon_in_session' => session('coupon')
        ]);

        return view('fontend.checkout.post', ['url' => $url, 'data' => $data]);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:momo,cash,vn_pay',
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

            // Check for buy now item first
            $buyNowItem = session('buy_now_item');
            if ($buyNowItem) {
                // Calculate total price for buy now item
                $totalPrice = $buyNowItem->price * $buyNowItem->quantity;

                // Apply coupon if exists
                $coupon = session('coupon');
                $couponDiscount = 0;
                $couponId = null;

                if ($coupon) {
                    if ($totalPrice >= $coupon['min_order_total']) {
                        if (isset($coupon['type']) && $coupon['type'] === 'percent') {
                            $percentageDiscount = $totalPrice * ($coupon['price'] / 100);
                            $couponDiscount = isset($coupon['maximum_amount']) && $coupon['maximum_amount'] > 0
                                ? min($percentageDiscount, $coupon['maximum_amount'])
                                : $percentageDiscount;
                        } else {
                            $couponDiscount = min($totalPrice, $coupon['price']);
                        }
                        $couponId = $coupon['id'];
                    }
                }

                $finalPrice = max(0, $totalPrice - $couponDiscount);

                // Create Order
                $order = Order::create([
                    'code' => date('YmdHis') . rand(100, 999),
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

                if ($couponId) {
                    $couponUser = CouponUser::where('user_id', $userId)
                        ->where('coupon_id', $couponId)
                        ->first();
                    if ($couponUser) {
                        $couponUser->used = 1;
                        $couponUser->save();
                    } else {
                        CouponUser::create([
                            'user_id' => $userId,
                            'coupon_id' => $couponId,
                            'used' => 1,
                        ]);
                    }

                    // Increment the coupon's used_count
                    \App\Models\Coupon::where('id', $couponId)->increment('used_count');
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

                // Clear sessions
                session()->forget('coupon');
                session()->forget('buy_now_item');

                DB::commit();

                // Delete cart items AFTER successful transaction
                if (isset($cartItems)) {
                    foreach ($cartItems as $item) {
                        if (isset($item->id) && !is_string($item->id)) {
                            $item->delete();
                        }
                    }
                }

                return redirect()->route('checkout.success', ['order_id' => $order->id])
                    ->with('success', 'Đặt hàng thành công! Vui lòng thanh toán khi nhận hàng.');
            }

            // Handle regular cart items
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                throw new \Exception('Giỏ hàng không tồn tại.');
            }

            // Get selected items from the request
            $selectedItemIds = $request->input('selected_items', []);
            if (is_string($selectedItemIds)) {
                $selectedItemIds = explode(',', $selectedItemIds);
            }
            $selectedItemIds = is_array($selectedItemIds) ? $selectedItemIds : [];

            if (empty($selectedItemIds)) {
                throw new \Exception('Vui lòng chọn sản phẩm để thanh toán.');
            }

            $cartItems = CartItem::with(['product', 'productVariant'])
                ->where('cart_id', $cart->id)
                ->whereIn('id', $selectedItemIds)
                ->get();

            // Check stock availability
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

            // Create Order
            $order = Order::create([
                'code' => date('YmdHis') . rand(100, 999),
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
                $couponUser = CouponUser::where('user_id', $userId)
                    ->where('coupon_id', $couponId)
                    ->first();
                if ($couponUser) {
                    $couponUser->used = 1;
                    $couponUser->save();
                } else {
                    CouponUser::create([
                        'user_id' => $userId,
                        'coupon_id' => $couponId,
                        'used' => 1,
                        'order_id' => $order->id
                    ]);
                }

                // Increment the coupon's used_count
                \App\Models\Coupon::where('id', $couponId)->increment('used_count');
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

            // Clear coupon session
            session()->forget('coupon');

            DB::commit();

            // Handle different payment methods
            switch ($request->payment_method) {
                case 'momo':
                    return redirect()->route('momo.process', ['order_id' => $order->id]);
                case 'vn_pay':
                    return redirect()->route('vnpay.process', ['order_id' => $order->id]);
                case 'cash':
                    return redirect()->route('checkout.success', ['order_id' => $order->id])
                        ->with('success', 'Đặt hàng thành công! Vui lòng thanh toán khi nhận hàng.');
                default:
                    return redirect()->route('checkout.success', ['order_id' => $order->id])
                        ->with('success', 'Đặt hàng thành công!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function applyCoupon(Request $request)
    {

        $isAjax = $request->expectsJson() || $request->ajax();
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('expire_date')->orWhere('expire_date', '>=', now());
            })
            ->first();

        if (!$coupon) {
            $msg = 'Mã khuyến mại không hợp lệ hoặc đã hết hạn.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg]) : back()->with('error', $msg);
        }

        // Ensure user hasn't used the coupon before
        if (Auth::check()) {
            $couponUsed = CouponUser::where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->exists();
            if ($couponUsed) {
                $msg = 'Bạn đã sử dụng mã khuyến mại này rồi!';
                return $isAjax ? response()->json(['success' => false, 'message' => $msg]) : back()->with('error', $msg);
            }
        }

        // Store coupon details in session
        $couponData = [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type, // 'fixed' or 'percent'
            'price' => (float)$coupon->price, // Convert to float to ensure proper calculation
            'maximum_amount' => $coupon->maximum_amount ? (float)$coupon->maximum_amount : null,
            'min_order_total' => $coupon->min_order_total ? (float)$coupon->min_order_total : 0,
            'applied_at' => now()->timestamp
        ];

        // Log the coupon data being stored
        Log::info('Storing coupon in session:', $couponData);

        session(['coupon' => $couponData]);

        $msg = 'Mã khuyến mại đã được áp dụng!';
        return $isAjax ? response()->json(['success' => true, 'message' => $msg]) : back()->with('success', $msg);
    }

    public function success(Request $request)
    {
        $order = Order::with(['items.product', 'items.productVariant'])
            ->findOrFail($request->order_id);

        $template = 'fontend.checkout.success';
        return view('fontend.layout', compact('template', 'order'));
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Mã khuyến mại đã được xóa!');
    }

    public function buyNow(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log the incoming request data
            Log::info('Buy Now Request Data:', [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'variant_id' => $request->variant_id,
                'attributes' => $request->all()
            ]);

            // Validate request
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'variant_id' => 'nullable|exists:product_variants,id',
            ]);

            // Get product
            $product = Product::findOrFail($request->product_id);

            // Log product details
            Log::info('Product Details:', [
                'product_id' => $product->id,
                'name' => $product->name,
                'is_variant' => $product->is_variant
            ]);

            // Check if product is available
            if (!$product->status) {
                throw new \Exception('Sản phẩm không khả dụng.');
            }

            // Handle variant product
            if ($product->is_variant) {
                if (!$request->variant_id && empty($request->except(['product_id', 'quantity', '_token']))) {
                    throw new \Exception('Vui lòng chọn biến thể sản phẩm.');
                }

                // If variant_id is provided, use it directly
                if ($request->variant_id) {
                    $variant = ProductVariant::findOrFail($request->variant_id);
                    Log::info('Using provided variant:', [
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->name
                    ]);
                } else {
                    // Get selected attributes from request
                    $selectedAttributes = $request->input('attributes', []);
                    if (empty($selectedAttributes)) {
                        $selectedAttributes = $request->except(['product_id', 'quantity', '_token']);
                    }

                    Log::info('Selected attributes:', [
                        'attributes' => $selectedAttributes
                    ]);

                    // Find the product variant by matching selected attributes
                    $requestAttributeValues = [];

                    // Handle nested attributes array
                    if (is_array($selectedAttributes)) {
                        foreach ($selectedAttributes as $key => $value) {
                            // Find the attribute value ID for this value
                            $attributeValue = \App\Models\AttributeValue::where('value', $value)->first();
                            if ($attributeValue) {
                                $requestAttributeValues[] = $attributeValue->id;
                            }
                        }
                    }

                    // Log the request attributes
                    // Log::info('Request attributes processed', [
                    //     'product_id' => $request->product_id,
                    //     'raw_attributes' => $selectedAttributes,
                    //     'attribute_value_ids' => $requestAttributeValues
                    // ]);

                    // Find all variants for this product
                    $variants = ProductVariant::where('product_id', $request->product_id)
                        ->with('attributeValues')
                        ->get();

                    // Log all variants found
                    // Log::info('All variants found', [
                    //     'variants' => $variants->map(function ($v) {
                    //         return [
                    //             'id' => $v->id,
                    //             'price' => $v->price,
                    //             'attribute_values' => $v->attributeValues->pluck('id')->toArray()
                    //         ];
                    //     })->toArray()
                    // ]);

                    // Find the matching variant by checking attributes
                    $matchingVariant = null;
                    foreach ($variants as $variant) {
                        // Get attribute value IDs for this variant
                        $variantAttributeIds = $variant->attributeValues->pluck('id')->toArray();

                        // Log variant check
                        // Log::info('Checking variant', [
                        //     'variant_id' => $variant->id,
                        //     'variant_price' => $variant->price,
                        //     'variant_attributes' => $variantAttributeIds,
                        //     'request_attributes' => $requestAttributeValues
                        // ]);

                        // Check if this variant matches all the requested attributes
                        $isMatch = true;
                        foreach ($requestAttributeValues as $requestAttributeId) {
                            if (!in_array($requestAttributeId, $variantAttributeIds)) {
                                $isMatch = false;
                                break;
                            }
                        }

                        if ($isMatch) {
                            $matchingVariant = $variant;
                            Log::info('Found matching variant', [
                                'variant_id' => $variant->id,
                                'price' => $variant->price
                            ]);
                            break;
                        }
                    }

                    $variant = $matchingVariant;

                    // if (!$variant) {
                    //     Log::error('No matching variant found for attributes:', [
                    //         'selected_attributes' => $selectedAttributes,
                    //         'product_id' => $product->id
                    //     ]);
                    //     throw new \Exception('Không tìm thấy biến thể phù hợp.');
                    // }

                    // Log::info('Selected variant', [
                    //     'variant_id' => $variant->id,
                    //     'price' => $variant->price,
                    //     'request_attributes' => $requestAttributeValues
                    // ]);
                }

                // Check variant availability
                if (!$variant->status) {
                    throw new \Exception('Biến thể không khả dụng.');
                }

                if ($variant->quantity < $request->quantity) {
                    throw new \Exception('Số lượng sản phẩm không đủ.');
                }

                $price = $variant->price_sale ?? $variant->price;
                $productVariantId = $variant->id;
            } else {
                // Handle non-variant product
                if ($product->quantity < $request->quantity) {
                    throw new \Exception('Số lượng sản phẩm không đủ.');
                }

                $price = $product->price_sale ?? $product->price;
                $productVariantId = null;
            }

            // Calculate total price
            $totalPrice = $price * $request->quantity;

            // Apply coupon if exists
            $coupon = session('coupon');
            $couponDiscount = 0;
            $couponId = null;

            if ($coupon && $totalPrice >= $coupon['min_order_total']) {
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

            $finalPrice = max(0, $totalPrice - $couponDiscount);

            // Create temporary cart item for checkout
            $cartItem = new \stdClass();
            $cartItem->product = $product; // Assign product first
            $cartItem->id = $cartItem->product->id; // Set the id to the actual product ID
            $cartItem->productVariant = $productVariantId ? ProductVariant::with(['attributeValues.attribute'])->find($productVariantId) : null;
            $cartItem->quantity = $request->quantity;
            $cartItem->price = $price;

            // Store only the selected attributes
            $cartItem->attributes = [];
            if ($product->is_variant && !$request->variant_id) {
                $selectedAttributes = $request->except(['product_id', 'quantity', '_token']);
                foreach ($selectedAttributes as $name => $value) {
                    $cartItem->attributes[] = [
                        'name' => $name,
                        'value' => $value
                    ];
                }
            }

            // Log the final cart item data
            Log::info('Final Cart Item Data:', [
                'product_id' => $cartItem->product->id,
                'variant_id' => $productVariantId,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'attributes' => $cartItem->attributes
            ]);

            // Store cart item in session for checkout
            session(['buy_now_item' => $cartItem]);

            // Log session data
            Log::info('Session Data After Storing:', [
                'buy_now_item' => session('buy_now_item')
            ]);

            // Redirect to checkout page
            return redirect()->route('checkout.index', ['buy_now' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Buy Now Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', $e->getMessage());
        }
    }
}
