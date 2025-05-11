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
        try {
            $user = Auth::user();
            $addresses = $user->addresses;
            $selectedItems = session('selected_items', []);
            $buyNowItem = session('buy_now_item');
            $cartItems = [];

            // Get provinces data
            $provincesResponse = Http::get('https://provinces.open-api.vn/api/');
            $provinces = $provincesResponse->json();

            // Get districts data for each province
            $districtsByProvince = [];
            foreach ($provinces as $province) {
                $districtsResponse = Http::get("https://provinces.open-api.vn/api/p/{$province['code']}?depth=2");
                $districtsByProvince[$province['code']] = $districtsResponse->json()['districts'] ?? [];
            }

            // If buy now item exists, use it instead of cart items
            if ($buyNowItem) {
                $cartItems[] = $buyNowItem;
            } else {
                // Get selected items from URL parameters
                $selectedItems = [];
                if ($request->has('selected_items')) {
                    $selectedItems = explode(',', $request->input('selected_items'));
                }

                Log::info('Selected items from URL:', ['selected_items' => $selectedItems]);

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
                                $cartItem->product = $item->product;
                                $cartItem->productVariant = $item->productVariant;
                                $cartItem->quantity = $item->quantity;
                                $cartItem->price = $item->productVariant ?
                                    ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);
                                return $cartItem;
                            })
                            ->toArray();
                    }

                    Log::info('Cart items from database for selected items:', [
                        'items' => $cartItems
                    ]);
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
            if ($coupon && $totalPrice >= $coupon['min_order_total']) {
                if ($coupon['type'] === 'percentage') {
                    $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                } else {
                    $couponDiscount = min($totalPrice, $coupon['value']);
                }
            }

            $finalPrice = max(0, $totalPrice - $couponDiscount);

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
                'template'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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

            // Get selected items from the request
            $selectedItemIds = $request->input('selected_items', []);
            if (is_string($selectedItemIds)) {
                $selectedItemIds = explode(',', $selectedItemIds);
            }
            $selectedItemIds = is_array($selectedItemIds) ? $selectedItemIds : [];

            Log::info('Selected items for checkout:', [
                'selected_items' => $selectedItemIds
            ]);

            if (empty($selectedItemIds)) {
                throw new \Exception('Vui lòng chọn sản phẩm để thanh toán.');
            }

            $cartItems = CartItem::with(['product', 'productVariant'])
                ->where('cart_id', $cart->id)
                ->whereIn('id', $selectedItemIds)
                ->get();

            Log::info('Cart items found:', [
                'items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity
                    ];
                })->toArray()
            ]);

            if ($cartItems->isEmpty()) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
            }

            // Process cart items to handle multiple variants
            $processedCartItems = collect();
            foreach ($cartItems as $item) {
                // If the item has multiple variants (stored as "id1 | id2")
                if ($item->product_variant_id && strpos($item->product_variant_id, '|') !== false) {
                    $variantIds = array_map('trim', explode('|', $item->product_variant_id));
                    // Only process variants that are in the selected items
                    foreach ($variantIds as $variantId) {
                        // Create a new cart item for each variant
                        $variantItem = clone $item;
                        $variantItem->setAttribute('product_variant_id', $variantId);
                        $variantItem->setRelation('productVariant', ProductVariant::with('attributeValues.attribute')
                            ->find($variantId));
                        $processedCartItems->push($variantItem);
                    }
                } else {
                    // For non-variant items or single variant items, just add them as is
                    $processedCartItems->push($item);
                }
            }

            Log::info('Processed cart items:', [
                'items' => $processedCartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity
                    ];
                })->toArray()
            ]);

            // Validate stock availability for processed items
            foreach ($processedCartItems as $item) {
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
            foreach ($processedCartItems as $item) {
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
                if ($coupon['type'] === 'percentage') {
                    $couponDiscount = min($totalPrice * ($coupon['value'] / 100), $coupon['maximum_amount']);
                } else {
                    $couponDiscount = min($totalPrice, $coupon['value']);
                }
                $couponId = $coupon['id'];
            }

            $finalPrice = max(0, $totalPrice - $couponDiscount);

            // Create temporary cart item for checkout
            $cartItem = new \stdClass();
            $cartItem->product = $product;
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
