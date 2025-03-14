<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all(); // Lấy tất cả các bản ghi từ bảng coupons
        $template = 'backend.coupons.index';

        return view('backend.dashboard.layout', compact('coupons', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $template = 'backend.coupons.create';
        return view('backend.dashboard.layout', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tên khuyến mại không được để trống.',
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'type.required' => 'Loại giảm giá không được để trống.',
            'price.required' => 'Số tiền giảm không được để trống.',
            'price.numeric' => 'Số tiền giảm phải là số.',
            'maximum_amount.numeric' => 'Giá trị tối đa phải là số.',
            'min_order_total.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'max_uses.integer' => 'Số lần sử dụng phải là số nguyên.',
            'expire_date.date' => 'Ngày hết hạn không đúng định dạng.',
            'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:percent,fixed',
            'price' => 'required|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'expire_date' => 'nullable|date',
            'status' => 'required|boolean',
        ], $messages);

        Coupon::create($request->all());

        return redirect()->route('coupons.index')->with('success', 'Thêm mã khuyến mại thành công!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        $template = 'backend.coupons.show';

        return view('backend.dashboard.layout', compact('template', 'coupon'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $template = 'backend.coupons.edit';

        return view('backend.dashboard.layout', compact('template', 'coupon'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $messages = [
            'name.required' => 'Tên khuyến mại không được để trống.',
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'type.required' => 'Loại giảm giá không được để trống.',
            'price.required' => 'Số tiền giảm không được để trống.',
            'price.numeric' => 'Số tiền giảm phải là số.',
            'maximum_amount.numeric' => 'Giá trị tối đa phải là số.',
            'min_order_total.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'max_uses.integer' => 'Số lần sử dụng tối đa phải là số nguyên.',
            'expire_date.date' => 'Ngày hết hạn không đúng định dạng.',
            'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'price' => 'required|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expire_date' => 'nullable|date|after:today',
            'status' => 'required|boolean',
        ], $messages);

        $coupon->update($request->all());

        return redirect()->route('coupons.index')->with('success', 'Mã khuyến mại đã được cập nhật thành công!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Mã khuyến mại đã được xóa!');
    }
}
