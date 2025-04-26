<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('status', 1)
            ->where('expire_date', '>', now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
            ]);
        }

        // Check if user has already used this coupon
        if (CouponUser::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng mã giảm giá này rồi.'
            ]);
        }

        // Check if coupon quantity is available
        if ($coupon->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng.'
            ]);
        }

        // Check minimum order total
        if ($request->order_total < $coupon->min_order_total) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.'
            ]);
        }

        // Calculate discount amount
        $discountAmount = 0;
        if ($coupon->type === 'percent') {
            $discountAmount = ($request->order_total * $coupon->price) / 100;
            if ($coupon->maximum_amount && $discountAmount > $coupon->maximum_amount) {
                $discountAmount = $coupon->maximum_amount;
            }
        } else {
            $discountAmount = $coupon->price;
        }

        // Store the coupon in session for later use
        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_amount' => $discountAmount,
            'type' => $coupon->type,
            'price' => $coupon->price
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được áp dụng!',
            'data' => [
                'coupon_id' => $coupon->id,
                'discount_amount' => $discountAmount,
                'final_total' => $request->order_total - $discountAmount
            ]
        ]);
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được xóa!'
        ]);
    }
}
