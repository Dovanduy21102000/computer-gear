<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest('id')->get();
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
        'price.min' => 'Số tiền giảm phải lớn hơn 0.',
        'price.max' => 'Số tiền giảm không được vượt quá 100% nếu là giảm giá phần trăm.',
        'maximum_amount.required_if' => 'Giá trị tối đa là bắt buộc khi chọn giảm giá phần trăm.',
        'maximum_amount.numeric' => 'Giá trị tối đa phải là số.',
        'min_order_total.numeric' => 'Đơn hàng tối thiểu phải là số.',
        'quantity.required' => 'Số lượng mã giảm giá là bắt buộc.',
        'quantity.integer' => 'Số lượng mã giảm giá phải là số nguyên.',
        'quantity.min' => 'Số lượng mã giảm giá phải lớn hơn 0.',
        'expire_date.date' => 'Ngày hết hạn không đúng định dạng.',
        'expire_date.after_or_equal' => 'Ngày hết hạn phải từ hôm nay trở đi.',
        'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
    ];

    // Validate dữ liệu
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:coupons,code',
        'type' => 'required|in:percent,fixed',
        'price' => [
            'required',
            'numeric',
            'min:1',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->type === 'percent' && $value > 100) {
                    $fail('Số tiền giảm không được vượt quá 100% nếu chọn loại giảm giá phần trăm.');
                }
            }
        ],
        'maximum_amount' => 'nullable|numeric|min:0|required_if:type,percent',
        'min_order_total' => 'nullable|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'expire_date' => 'nullable|date|after_or_equal:today',
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
        'name.max' => 'Tên khuyến mại không được vượt quá 255 ký tự.',
        'code.required' => 'Mã giảm giá không được để trống.',
        'code.unique' => 'Mã giảm giá đã tồn tại.',
        'code.min' => 'Mã giảm giá phải có ít nhất 5 ký tự.',
        'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
        'type.required' => 'Loại giảm giá không được để trống.',
        'type.in' => 'Loại giảm giá phải là "percent" hoặc "fixed".',
        'price.required' => 'Số tiền giảm không được để trống.',
        'price.numeric' => 'Số tiền giảm phải là số.',
        'price.min' => 'Số tiền giảm không được nhỏ hơn 0.',
        'price.max' => 'Nếu là giảm giá phần trăm, giá trị không được lớn hơn 100%.',
        'maximum_amount.required_if' => 'Giá trị tối đa là bắt buộc khi chọn loại giảm giá phần trăm.',
        'maximum_amount.numeric' => 'Giá trị tối đa phải là số.',
        'maximum_amount.min' => 'Giá trị tối đa không được nhỏ hơn 0.',
        'maximum_amount.required' => 'Giá trị tối đa không được bỏ trống',
        
        'min_order_total.numeric' => 'Đơn hàng tối thiểu phải là số.',
        'min_order_total.min' => 'Đơn hàng tối thiểu không được nhỏ hơn 0.',
        'quantity.integer' => 'Số lượng phải là số nguyên.',
        'quantity.min' => 'Số lượng không được nhỏ hơn 1.',
        'expire_date.date' => 'Ngày hết hạn không đúng định dạng.',
        'expire_date.after_or_equal' => 'Ngày hết hạn phải từ hôm nay trở đi.',
        'status.required' => 'Trạng thái không được để trống.',
        'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
    ];

    $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|min:5|max:50|unique:coupons,code,' . $coupon->id,
        'type' => 'required|in:percent,fixed',
        'price' => 'required|numeric|min:0',
        'maximum_amount' => 'nullable|numeric|min:0',
        'min_order_total' => 'nullable|numeric|min:0',
        'quantity' => 'nullable|integer|min:1',
        'expire_date' => 'nullable|date|after_or_equal:today',
        'status' => 'required|boolean',
    ];

    // Nếu loại giảm giá là phần trăm, giới hạn tối đa là 100 và bắt buộc nhập giá trị tối đa
    if ($request->type === 'percent') {
        $rules['price'] .= '|max:100';
        $rules['maximum_amount'] = 'required|numeric|min:0';
    }

    $validatedData = $request->validate($rules, $messages);

    // Cập nhật dữ liệu
    $coupon->update($validatedData);

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


    // public function applyCoupon(Request $request)
    // {
    //     $coupon = Coupon::where('code', $request->code)->first();

    //     if (!$coupon) {
    //         return back()->with('error', 'Mã giảm giá không hợp lệ.');
    //     }

    //     // Kiểm tra user đã sử dụng mã này chưa
    //     if (CouponUser::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->exists()) {
    //         return back()->with('error', 'Bạn đã sử dụng mã giảm giá này rồi.');
    //     }

    //     // Kiểm tra số lượng mã giảm giá còn lại
    //     if ($coupon->quantity <= 0) {
    //         return back()->with('error', 'Mã giảm giá đã hết lượt sử dụng.');
    //     }

    //     // Lưu vào bảng coupon_user
    //     CouponUser::create([
    //         'user_id' => auth()->id(),
    //         'coupon_id' => $coupon->id
    //     ]);

    //     // Giảm số lượng mã còn lại
    //     $coupon->decrement('quantity');

    //     return back()->with('success', 'Mã giảm giá đã được áp dụng!');
    // }
}
