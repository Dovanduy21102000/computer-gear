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
        }

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
            'attributes' => 'required|array', // Expecting an array of attribute_id => attribute_value_id
        ]);

        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng.');
        }

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Ensure the requested quantity does not exceed available stock
        if ($request->quantity > $product->quantity) {
            return redirect()->back()->with('error', 'Sản phẩm không còn tồn hàng.');
        }

        // Get the selected attributes from the request
        $attributes = $request->attributes;  // Example: ['color' => 1, 'size' => 2]

        // Find the product variant by matching selected attributes
        $variant = ProductVariant::where('product_id', $request->product_id)
            ->whereHas('attributeValues', function ($query) use ($attributes) {
                foreach ($attributes as $attributeId => $valueId) {
                    $query->where('attribute_value_id', $valueId)
                        ->whereHas('attribute', function ($subQuery) use ($attributeId) {
                            $subQuery->where('id', $attributeId);
                        });
                }
            })
            ->first();

        // If no matching variant is found, return an error
        if (!$variant) {
            return redirect()->back()->with('error', 'Biến thể sản phẩm không hợp lệ.');
        }

        // Get or create the user's cart
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Check if the selected variant is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant->id) // Ensure we match the correct variant
            ->first();

        if ($cartItem) {
            // Update quantity if the item is already in the cart
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $variant->quantity) {
                return redirect()->back()->with('error', 'Không thể thêm quá số lượng tồn kho cho biến thể này.');
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Add the variant to the cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,  // Store the variant_id in the cart item
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công');
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
                $product = Product::findOrFail($cartItemModel->product_id);
                $newQuantity = (int) $cartItem['quantity'];

                // Check stock limit
                if ($newQuantity > $product->quantity) {
                    return back()->with('error', 'Số lượng sản phẩm không đủ.');
                }

                $cartItemModel->quantity = $newQuantity;
                $cartItemModel->save();
            }
        }

        return back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }





    public function remove(Request $request, $id)
    {
        // dd(1);
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
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->where('expire_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Phiếu giảm giá không hợp lệ hoặc đã hết hạn');
        }

        // Ensure user hasn't used the coupon before
        if (Auth::check()) {
            $couponUsed = CouponUser::where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->exists();
            if ($couponUsed) {
                return back()->with('error', 'Bạn đã sử dụng phiếu giảm giá này');
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

        return back()->with('success', 'Phiếu giảm giá được áp dụng thành công
!');
    }
}
