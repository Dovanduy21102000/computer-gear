<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Show Cart Page
    public function index()
    {
        $userId = 10;

        // Get the cart for the user
        $cart = Cart::where('user_id', $userId)->first();

        // If the cart exists, get its items with associated products
        $cartItems = CartItem::with(['product', 'productVariant'])->get();

        $template = 'fontend.cart.index';
        return view('fontend.layout', compact('template', 'cart', 'cartItems'));
    }

    // Add Product to Cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = 10;

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Update Cart Item Quantity
    public function update(Request $request)
    {
        if (!$request->has('cart') || !is_array($request->cart)) {
            return back()->with('error', 'No cart items to update.');
        }

        foreach ($request->cart as $cartItem) {
            $cartItemModel = CartItem::where('id', $cartItem['id'])->first(); // Find cart item by ID

            if ($cartItemModel) {
                $cartItemModel->quantity = (int) $cartItem['quantity']; // Convert quantity to integer
                $cartItemModel->save(); // Save the updated quantity
            }
        }

        return back()->with('success', 'Cart updated successfully!');
    }





    public function remove(Request $request, $id)
    {
        // dd(1);
        $cartItem = CartItem::find($id);

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item removed from cart.');
        }

        return redirect()->back()->with('error', 'Item not found.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->selected_items) {
            CartItem::whereIn('id', $request->selected_items)->delete();
            return redirect()->back()->with('success', 'Selected items removed from cart.');
        }

        return redirect()->back()->with('error', 'No items selected.');
    }
    // Clear Cart
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->back()->with('success', 'Cart cleared!');
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
        return back()->with('error', 'Invalid or expired coupon.');
    }

    // Ensure user hasn't used the coupon before
    if (Auth::check()) {
        $couponUsed = CouponUser::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();
        if ($couponUsed) {
            return back()->with('error', 'You have already used this coupon.');
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

    return back()->with('success', 'Coupon applied successfully!');
}

}
