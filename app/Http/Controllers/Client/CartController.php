<?php

namespace App\Http\Controllers\Client;

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
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng.');
        }
        $userId = Auth::id();
        // Get the cart for the user
        $cart = Cart::where('user_id', $userId)->first();

        // If the cart exists, get its items with associated products
        $cartItems = [];
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'productVariant'])
                ->get();

            // Filter out invalid products
            $validCartItems = $cartItems->filter(function ($item) {
                // Check if product exists and is active
                if (!$item->product || !$item->product->status) {
                    // Remove invalid item from cart
                    $item->delete();
                    return false;
                }

                // If product has variant, check variant status
                if ($item->productVariant) {
                    if (!$item->productVariant->status) {
                        $item->delete();
                        return false;
                    }
                }

                return true;
            });

            // If any items were removed, update the cart
            if ($validCartItems->count() < $cartItems->count()) {
                session()->flash('warning', 'Một số sản phẩm không còn khả dụng đã được xóa khỏi giỏ hàng.');
            }

            $cartItems = $validCartItems;
        }

        $template = 'fontend.cart.index';
        return view('fontend.layout', compact('template', 'cart', 'cartItems'));
    }

    // Add Product to Cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'attributes' => 'nullable',
        ]);

        // Check if product exists and is active
        $product = Product::find($request->product_id);
        if (!$product) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        if (!$product->status) {
            return back()->with('error', 'Sản phẩm không khả dụng.');
        }

        // Check if product has variants but no attributes were provided
        if ($product->is_variant && (!$request->has('attributes') || empty($request->input('attributes')))) {
            return redirect()->route('client.products.detail', $product->slug)
                ->with('info', 'Vui lòng chọn biến thể sản phẩm.');
        }

        // If product has variants, validate the selected variant
        $variant = null;
        if ($request->has('attributes') && !empty($request->input('attributes'))) {
            // Get the attributes in format {"attribute_name": "attribute_value"}
            $attributeData = $request->input('attributes');

            // Log received attribute data
            Log::info('Received attribute data:', ['product_id' => $request->product_id, 'attributes' => $attributeData]);

            // Find the variant that matches the selected attributes
            $productVariants = ProductVariant::where('product_id', $product->id)
                ->with('attributeValues.attribute')
                ->get();

            foreach ($productVariants as $productVariant) {
                $matches = true;
                $variantAttributeValues = $productVariant->attributeValues;

                // Log the variant being checked
                Log::info('Checking variant:', [
                    'variant_id' => $productVariant->id,
                    'attributes' => $variantAttributeValues->map(function ($value) {
                        return [
                            'name' => $value->attribute->name ?? null,
                            'value' => $value->value
                        ];
                    })->toArray()
                ]);

                // Check if each selected attribute matches this variant
                foreach ($attributeData as $attributeName => $attributeValue) {
                    // Convert attribute name to match database format (e.g., "mau_sac" to "màu sắc")
                    $formattedAttributeName = str_replace('_', ' ', $attributeName);

                    // Find the matching attribute value for this variant
                    $matchingValue = $variantAttributeValues->first(function ($value) use ($formattedAttributeName, $attributeValue) {
                        return isset($value->attribute) &&
                            strtolower($value->attribute->name) === strtolower($formattedAttributeName) &&
                            strtolower($value->value) === strtolower($attributeValue);
                    });

                    if (!$matchingValue) {
                        $matches = false;
                        break;
                    }
                }

                // Also check if all variant attributes are matched
                if ($matches) {
                    $allAttributesMatched = true;
                    foreach ($variantAttributeValues as $variantValue) {
                        $attributeName = strtolower($variantValue->attribute->name ?? '');
                        $attributeValue = strtolower($variantValue->value);

                        $hasMatch = false;
                        foreach ($attributeData as $selectedName => $selectedValue) {
                            $formattedSelectedName = str_replace('_', ' ', $selectedName);
                            if (
                                strtolower($formattedSelectedName) === $attributeName &&
                                strtolower($selectedValue) === $attributeValue
                            ) {
                                $hasMatch = true;
                                break;
                            }
                        }

                        if (!$hasMatch) {
                            $allAttributesMatched = false;
                            break;
                        }
                    }

                    if ($allAttributesMatched) {
                        $variant = $productVariant;
                        Log::info('Found matching variant:', [
                            'variant_id' => $variant->id,
                            'attributes' => $variantAttributeValues->map(function ($value) {
                                return [
                                    'name' => $value->attribute->name ?? null,
                                    'value' => $value->value
                                ];
                            })->toArray()
                        ]);
                        break;
                    }
                }
            }

            if (!$variant) {
                return back()->with('error', 'Không tìm thấy biến thể sản phẩm với các thuộc tính đã chọn.');
            }

            if (!$variant->status) {
                return back()->with('error', 'Biến thể sản phẩm không khả dụng.');
            }

            // Check variant quantity
            if ($variant->quantity < $request->quantity) {
                return back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }
        } else {
            // Check product quantity
            if ($product->quantity < $request->quantity) {
                return back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['user_id' => Auth::id()]
        );

        // Check if product already exists in cart with the same variant
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $variant ? $variant->id : null)
            ->first();

        if ($existingItem) {
            // Update quantity if product exists
            $newQuantity = $existingItem->quantity + $request->quantity;

            // Check if new quantity exceeds available stock
            $maxQuantity = $variant ? $variant->quantity : $product->quantity;
            if ($newQuantity > $maxQuantity) {
                return back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }

            $existingItem->update(['quantity' => $newQuantity]);
            Log::info('Updated existing cart item', [
                'item_id' => $existingItem->id,
                'product_id' => $existingItem->product_id,
                'variant_id' => $existingItem->product_variant_id,
                'new_quantity' => $newQuantity
            ]);
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $variant ? $variant->id : null,
                'quantity' => $request->quantity,
            ]);
            Log::info('Created new cart item', [
                'item_id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'variant_id' => $cartItem->product_variant_id,
                'quantity' => $cartItem->quantity
            ]);
        }

        return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
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
