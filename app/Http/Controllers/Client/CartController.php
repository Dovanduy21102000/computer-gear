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
        Log::info('Initial cart items', [
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id
                ];
            })->toArray()
        ]);

        // Process each cart item to handle multiple variant IDs
        $processedItems = collect();
        $cartItems->each(function ($item) use ($processedItems) {
            if ($item->product_variant_id) {
                // Split the variant IDs if they exist
                $variantIds = explode(' | ', $item->product_variant_id);

                // Log the variant IDs
                Log::info('Processing cart item', [
                    'item_id' => $item->id,
                    'variant_ids' => $variantIds
                ]);

                // Get all variants with their attribute values
                $variants = ProductVariant::whereIn('id', $variantIds)
                    ->with(['attributeValues' => function ($query) {
                        $query->with('attribute')
                            ->orderBy('attribute_id'); // Order by attribute_id to ensure consistent order
                    }])
                    ->get();

                // Log the variants found
                Log::info('Variants found', [
                    'variants' => $variants->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'attributes' => $v->attributeValues->map(function ($av) {
                                return [
                                    'name' => $av->attribute->name,
                                    'value' => $av->value
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ]);

                // Create a new cart item for each variant
                foreach ($variants as $variant) {
                    // Create a new cart item instance
                    $newItem = new CartItem();
                    $newItem->id = $item->id;
                    $newItem->cart_id = $item->cart_id;
                    $newItem->product_id = $item->product_id;
                    $newItem->product_variant_id = (string)$variant->id;
                    $newItem->quantity = $item->quantity;
                    $newItem->product = $item->product;

                    // Create a new variants collection with just this variant
                    $newItem->variants = collect([$variant]);

                    // Add the variant's attributes to the item, ensuring unique attribute names
                    $newItem->variant_attributes = $variant->attributeValues->unique('attribute_id')->map(function ($value) {
                        return [
                            'name' => $value->attribute->name,
                            'value' => $value->value
                        ];
                    })->toArray();

                    // Log the new item details
                    Log::info('Created new cart item', [
                        'original_id' => $item->id,
                        'variant_id' => $variant->id,
                        'attributes' => $newItem->variant_attributes
                    ]);

                    $processedItems->push($newItem);
                }
            } else {
                $processedItems->push($item);
            }
        });

        // Log the final processed items
        Log::info('Final processed items', [
            'items' => $processedItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'attributes' => isset($item->variant_attributes) ? $item->variant_attributes : []
                ];
            })->toArray()
        ]);

        // Filter out invalid products and collect IDs of invalid items
        $invalidItemIds = collect();
        $validCartItems = $processedItems->filter(function ($item) use ($invalidItemIds) {
            $isValid = true;

            // Check if product exists and is active
            if (!$item->product || !$item->product->status) {
                $isValid = false;
            }

            // If product has variants, check variant status
            if ($isValid && isset($item->variants)) {
                foreach ($item->variants as $variant) {
                    if (!$variant->status) {
                        $isValid = false;
                        break;
                    }
                }
            }

            if (!$isValid) {
                $invalidItemIds->push($item->id);
            }

            return $isValid;
        });

        // If any items were invalid, delete them from the database
        if ($invalidItemIds->isNotEmpty()) {
            CartItem::whereIn('id', $invalidItemIds)->delete();
            session()->flash('warning', 'Một số sản phẩm không còn khả dụng đã được xóa khỏi giỏ hàng.');
        }

        $cartItems = $validCartItems;

        $template = 'fontend.cart.index';
        return view('fontend.layout', compact('template', 'cart', 'cartItems'));
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
            return back()->with('error', 'Không có mục giỏ hàng nào để cập nhật.');
        }

        foreach ($request->cart as $cartItem) {
            $cartItemModel = CartItem::where('id', $cartItem['id'])->first();

            if ($cartItemModel) {
                $newQuantity = (int) $cartItem['quantity'];

                // Check if this is a variant product
                if ($cartItemModel->product_variant_id) {
                    $variant = ProductVariant::find($cartItemModel->product_variant_id);
                    if (!$variant) {
                        return back()->with('error', 'Biến thể sản phẩm không tồn tại.');
                    }

                    // Check stock limit for variant
                    if ($newQuantity > $variant->quantity) {
                        return back()->with('error', 'Số lượng sản phẩm biến thể không đủ. Số lượng còn lại: ' . $variant->quantity);
                    }
                } else {
                    $product = Product::findOrFail($cartItemModel->product_id);
                    // Check stock limit for regular product
                    if ($newQuantity > $product->quantity) {
                        return back()->with('error', 'Số lượng sản phẩm không đủ. Số lượng còn lại: ' . $product->quantity);
                    }
                }

                $cartItemModel->quantity = $newQuantity;
                $cartItemModel->save();
            }
        }

        return back()->with('success', 'Cập nhật giỏ hàng thành công!');
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

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        // Check if user has already used this coupon
        $userId = Auth::id();
        $hasUsed = CouponUser::where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($hasUsed) {
            return back()->with('error', 'Bạn đã sử dụng mã giảm giá này trước đó.');
        }

        // Store coupon in session
        session(['coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_order_total' => $coupon->min_order_total,
            'maximum_amount' => $coupon->maximum_amount,
        ]]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã xóa mã giảm giá.');
    }
}
