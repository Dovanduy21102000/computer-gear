<?php

namespace App\Http\Controllers\Client;

use App\Events\CartUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\CouponApplied;
use App\Events\CheckoutSessionUpdated;
use App\Models\CheckoutSession;

class CartController extends Controller
{
    // Show Cart Page
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }

        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $userId]);
        }

        // Get cart items with their products and variants
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with(['product', 'productVariant'])
            ->get();

        // Log initial cart items
        // Log::info('Initial cart items', [
        //     'items' => $cartItems->map(function ($item) {
        //         return [
        //             'id' => $item->id,
        //             'product_id' => $item->product_id,
        //             'variant_id' => $item->product_variant_id
        //         ];
        //     })->toArray()
        // ]);

        // Process each cart item to handle multiple variant IDs
        $processedItems = collect();
        $invalidItemIds = collect();

        $cartItems->each(function ($item) use ($processedItems, $invalidItemIds) {
            // Check if product exists and is active
            if (!$item->product || !$item->product->status) {
                $invalidItemIds->push($item->id);
                return;
            }

            // If product has variants, check variant status
            if ($item->product->is_variant) {
                if (!$item->productVariant || !$item->productVariant->status) {
                    $invalidItemIds->push($item->id);
                    return;
                }

                // Get the variant with its attribute values
                $variant = ProductVariant::with(['attributeValues' => function ($query) {
                    $query->with('attribute')
                        ->orderBy('attribute_id');
                }])->find($item->product_variant_id);

                if ($variant) {
                    // Create a new cart item instance
                    $newItem = new CartItem();
                    $newItem->id = $item->id;
                    $newItem->cart_id = $item->cart_id;
                    $newItem->product_id = $item->product_id;
                    $newItem->product_variant_id = $variant->id;
                    $newItem->quantity = $item->quantity;
                    $newItem->product = $item->product;
                    $newItem->productVariant = $variant;

                    // Add the variant's attributes to the item
                    $newItem->variant_attributes = $variant->attributeValues->unique('attribute_id')->map(function ($value) {
                        return [
                            'name' => $value->attribute->name,
                            'value' => $value->value
                        ];
                    })->toArray();

                    $processedItems->push($newItem);
                } else {
                    $invalidItemIds->push($item->id);
                }
            } else {
                $processedItems->push($item);
            }
        });

        // If any items were invalid, delete them from the database
        if ($invalidItemIds->isNotEmpty()) {
            CartItem::whereIn('id', $invalidItemIds)->delete();
            session()->flash('warning', 'Một số sản phẩm không còn khả dụng đã được xóa khỏi giỏ hàng.');
        }

        $cartItems = $processedItems;

        $template = 'fontend.cart.index';
        return view('fontend.layout', compact('template', 'cart', 'cartItems', 'userId'));
    }

    // Add Product to Cart
    public function add(Request $request)
    {
        // Validate input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng.');
        }

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Check if product has variants
        if ($product->is_variant) {
            // If product has variants but no attributes selected, redirect to product detail
            if (!$request->has('attributes') || empty($request->attributes)) {
                return redirect()->route('client.products.detail', $product->slug)
                    ->with('error', 'Vui lòng chọn biến thể sản phẩm.');
            }

            // Find the product variant by matching selected attributes
            $requestAttributeValues = [];

            // Log the raw request
            Log::info('Raw request data', [
                'all' => $request->all(),
                'attributes' => $request->input('attributes')
            ]);

            // Handle nested attributes array
            $attributes = $request->input('attributes', []);
            if (is_array($attributes)) {
                foreach ($attributes as $key => $value) {
                    // Find the attribute value ID for this value
                    $attributeValue = \App\Models\AttributeValue::where('value', $value)->first();
                    if ($attributeValue) {
                        $requestAttributeValues[] = $attributeValue->id;
                    }
                }
            }

            // Log the request attributes
            Log::info('Request attributes processed', [
                'product_id' => $request->product_id,
                'raw_attributes' => $attributes,
                'attribute_value_ids' => $requestAttributeValues
            ]);

            // Find all variants for this product
            $variants = ProductVariant::where('product_id', $request->product_id)
                ->with('attributeValues')
                ->get();

            // Log all variants found
            Log::info('All variants found', [
                'variants' => $variants->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'price' => $v->price,
                        'attribute_values' => $v->attributeValues->pluck('id')->toArray()
                    ];
                })->toArray()
            ]);

            // Find the matching variant by checking attributes
            $matchingVariant = null;
            foreach ($variants as $variant) {
                // Get attribute value IDs for this variant
                $variantAttributeIds = $variant->attributeValues->pluck('id')->toArray();

                // Log variant check
                Log::info('Checking variant', [
                    'variant_id' => $variant->id,
                    'variant_price' => $variant->price,
                    'variant_attributes' => $variantAttributeIds,
                    'request_attributes' => $requestAttributeValues
                ]);

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

            if (!$variant) {
                return redirect()->back()->with('error', 'Biến thể sản phẩm không tồn tại.');
            }

            // Log the final selected variant
            Log::info('Selected variant', [
                'variant_id' => $variant->id,
                'price' => $variant->price,
                'request_attributes' => $requestAttributeValues
            ]);

            // Store the actual variant ID as a string
            $variantId = (string)$variant->id;

            // Get or create user's cart
            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            // Check if the item with this exact variant already exists in the cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($existingItem) {
                // Update quantity if variant exists
                $newQuantity = $existingItem->quantity + $request->quantity;
                if ($newQuantity > $variant->quantity) {

                    $payload = [
                        'error' => true,
                        'message' => 'Số lượng sản phẩm vượt quá tồn kho.',
                    ];
                    if ($request->ajax()) {
                        return response()->json($payload, 200);
                    }
                    return redirect()->back()->with('error', $payload['message']);
                }
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new cart item if it doesn't exist
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'product_variant_id' => $variantId,
                    'quantity' => $request->quantity
                ]);
            }
        } else {
            // For non-variant products, check product stock directly
            if ($request->quantity > $product->quantity) {
                return redirect()->back()->with('error', 'Sản phẩm không còn tồn hàng.');
            }

            // Get or create user's cart
            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            // Check if the item already exists in the cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->whereNull('product_variant_id')
                ->first();

            if ($existingItem) {
                // Update quantity if item exists
                $newQuantity = $existingItem->quantity + $request->quantity;
                if ($newQuantity > $product->quantity) {
                    $payload = [
                        'error' => true,
                        'message' => 'Số lượng sản phẩm vượt quá tồn kho.',
                    ];
                    if ($request->ajax()) {
                        return response()->json($payload, 200);
                    }
                    return redirect()->back()->with('error', $payload['message']);
                }
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'product_variant_id' => null,
                    'quantity' => $request->quantity,
                ]);
            }
        }
        event(new CartUpdated($userId, $cart->items()->count()));
        $payload = [
            'success'   => true,
            'message'   => 'Sản phẩm đã được thêm vào giỏ hàng.',
            'cartCount' => $cart->items()->count() // Số lượng giỏ hàng mới
        ];

        if ($request->ajax()) {
            return response()->json($payload, 200);
        }

        return redirect()->back()->with('success', $payload['message']);

        //     return response()->json([
        //     'success' => true,
        //     'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
        //     'cartCount' => $cart->items()->count() // Số lượng giỏ hàng mới
        // ]);
    }

    // Update Cart Item Quantity
    public function update(Request $request)
    {
        if (!$request->has('cart') || !is_array($request->cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có mục giỏ hàng nào để cập nhật.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->first();
            $updatedItems = [];

            foreach ($request->cart as $item) {
                $cartItemModel = CartItem::with(['product', 'productVariant'])
                    ->where('id', $item['id'])
                    ->first();

                if (!$cartItemModel) {
                    continue;
                }

                $newQuantity = (int)$item['quantity'];
                $maxQuantity = $cartItemModel->productVariant ?
                    $cartItemModel->productVariant->quantity :
                    $cartItemModel->product->quantity;

                if ($newQuantity > $maxQuantity) {
                    throw new \Exception('Số lượng sản phẩm vượt quá tồn kho.');
                }

                $cartItemModel->quantity = $newQuantity;
                $cartItemModel->save();

                // Add to updated items for broadcasting
                $updatedItems[] = [
                    'id' => $cartItemModel->id,
                    'quantity' => $newQuantity,
                    'price' => $cartItemModel->productVariant ?
                        ($cartItemModel->productVariant->price_sale ?? $cartItemModel->productVariant->price) : ($cartItemModel->product->price_sale ?? $cartItemModel->product->price)
                ];
            }

            // Calculate new totals
            $subtotal = 0;
            foreach ($updatedItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Apply coupon if exists
            $coupon = session('coupon');
            $discount = 0;
            if ($coupon) {
                if ($coupon['type'] === 'percent') {
                    $discount = min(
                        $subtotal * ($coupon['price'] / 100),
                        $coupon['maximum_amount'] ?? $subtotal
                    );
                } else {
                    $discount = min($coupon['price'], $subtotal);
                }
            }

            $total = max(0, $subtotal - $discount);

            // Broadcast cart update event only for cart page
            event(new CartUpdated($userId, $cart->items()->count()));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công!',
                'data' => [
                    'items' => $updatedItems,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'coupon' => $coupon ? [
                        'code' => $coupon['code'],
                        'type' => $coupon['type'],
                        'price' => $coupon['price'],
                        'maximum_amount' => $coupon['maximum_amount'] ?? null
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    private function getCartItems($user)
    {
        return Cart::where('user_id', $user->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->productVariant
                        ? ($item->productVariant->price_sale ?? $item->productVariant->price)
                        : ($item->product->price_sale ?? $item->product->price),
                ];
            })->toArray();
    }

    private function calculateTotal($user)
    {
        return Cart::where('user_id', $user->id)
            ->get()
            ->sum(function ($item) {
                $price = $item->productVariant
                    ? ($item->productVariant->price_sale ?? $item->productVariant->price)
                    : ($item->product->price_sale ?? $item->product->price);
                return $item->quantity * $price;
            });
    }

    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng');
        }

        return redirect()->back()->with('error', 'Không tìm thấy mục.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->selected_items) {
            CartItem::whereIn('id', $request->selected_items)->delete();
            return redirect()->back()->with('success', 'Đã xóa các mặt hàng đã chọn khỏi giỏ hàng.');
        }

        return redirect()->back()->with('error', 'Không có mục nào được chọn.');
    }

    // Clear Cart
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->back()->with('success', 'Đã xóa giỏ hàng!');
    }

    public function getAvailableCoupons()
    {
        $userId = Auth::id();
        $total = request()->input('total', 0);

        // Public coupons not used by this user
        $publicCoupons = DB::table('coupons')
            ->where('is_public', true)
            ->where('status', 1)
            ->where(function ($query) use ($total) {
                $query->whereNull('min_order_total')
                    ->orWhere('min_order_total', '<=', $total);
            })
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('coupon_id')
                    ->from('coupon_user')
                    ->where('user_id', $userId)
                    ->where('used', true);
            })
            ->select(
                'id',
                'code',
                'type',
                'price',
                'min_order_total',
                'maximum_amount',
                DB::raw('false as used'),
                DB::raw('true as is_public')
            )
            ->get();

        // Private coupons assigned to user and not used
        $privateCoupons = DB::table('coupon_user')
            ->join('coupons', 'coupon_user.coupon_id', '=', 'coupons.id')
            ->where('coupon_user.user_id', $userId)
            ->where('coupon_user.used', false)
            ->where('coupons.status', 1)
            ->where(function ($query) use ($total) {
                $query->whereNull('coupons.min_order_total')
                    ->orWhere('coupons.min_order_total', '<=', $total);
            })
            ->select(
                'coupons.id',
                'coupons.code',
                'coupons.type',
                'coupons.price',
                'coupons.min_order_total',
                'coupons.maximum_amount',
                'coupon_user.used',
                DB::raw('false as is_public')
            )
            ->get();

        // Merge and return
        Log::info('Private coupons fetched for user', ['user_id' => $userId, 'privateCoupons' => $privateCoupons]);
        $coupons = $publicCoupons->merge($privateCoupons);

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = Auth::id();

        // Check public coupon
        $publicCoupon = DB::table('coupons')
            ->where('code', $request->code)
            ->where('is_public', true)
            ->where('status', 1)
            ->first();

        if ($publicCoupon) {
            // Check if user has already used this public coupon
            $used = DB::table('coupon_user')
                ->where('user_id', $userId)
                ->where('coupon_id', $publicCoupon->id)
                ->where('used', true)
                ->exists();

            if ($used) {
                return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này trước đó.']);
            }

            // Store coupon in session
            session(['coupon' => [
                'id' => $publicCoupon->id,
                'code' => $publicCoupon->code,
                'type' => $publicCoupon->type,
                'price' => $publicCoupon->price,
                'min_order_total' => $publicCoupon->min_order_total,
                'maximum_amount' => $publicCoupon->maximum_amount,
                'is_public' => true,
            ]]);
            return response()->json(['success' => true, 'message' => 'Áp dụng mã giảm giá thành công!']);
        }

        // Check private coupon
        $privateCoupon = DB::table('coupon_user')
            ->join('coupons', 'coupon_user.coupon_id', '=', 'coupons.id')
            ->where('coupon_user.user_id', $userId)
            ->where('coupons.code', $request->code)
            ->where('coupon_user.used', false)
            ->where('coupons.status', 1)
            ->select('coupons.*', 'coupon_user.coupon_id')
            ->first();

        if ($privateCoupon) {
            session(['coupon' => [
                'id' => $privateCoupon->id,
                'code' => $privateCoupon->code,
                'type' => $privateCoupon->type,
                'price' => $privateCoupon->price,
                'min_order_total' => $privateCoupon->min_order_total,
                'maximum_amount' => $privateCoupon->maximum_amount,
                'is_public' => false,
            ]]);
            return response()->json(['success' => true, 'message' => 'Áp dụng mã giảm giá thành công!']);
        }

        return response()->json(['success' => false, 'message' => 'Bạn không có mã giảm giá này hoặc đã sử dụng rồi!']);
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return response()->json(['success' => true]);
    }

    public function checkChanges(Request $request)
    {
        try {
            $initialState = $request->input('initial_state', []);
            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) {
                return response()->json(['has_changes' => false]);
            }

            $currentItems = CartItem::where('cart_id', $cart->id)
                ->whereIn('id', array_keys($initialState))
                ->get();

            foreach ($currentItems as $item) {
                $initialItem = $initialState[$item->id] ?? null;
                if (!$initialItem) {
                    return response()->json(['has_changes' => true]);
                }

                if ($item->quantity != $initialItem['quantity']) {
                    return response()->json(['has_changes' => true]);
                }

                $currentPrice = $item->productVariant ?
                    ($item->productVariant->price_sale ?? $item->productVariant->price) : ($item->product->price_sale ?? $item->product->price);

                if ($currentPrice != $initialItem['price']) {
                    return response()->json(['has_changes' => true]);
                }
            }

            return response()->json(['has_changes' => false]);
        } catch (\Exception $e) {
            return response()->json(['has_changes' => false]);
        }
    }
}
