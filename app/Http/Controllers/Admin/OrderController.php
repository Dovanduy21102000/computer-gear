<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $template = 'backend.orders.index';
        // dd($orders);
        return view('backend.dashboard.layout', compact('orders', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = $order->user;
        // Lấy danh sách tỉnh/thành phố từ API
        $response = Http::get('https://provinces.open-api.vn/api/p');
        $provinces = json_decode($response->body(), true) ?? [];

        // Kiểm tra và lấy tên tỉnh/thành phố
        $provinceName = '';
        foreach ($provinces as $province) {
            if (!empty($order->province_id) && $province['code'] == $order->province_id) {
                $provinceName = $province['name'];
                break;
            }
        }


        // Lấy danh sách quận/huyện theo tỉnh cũ nếu có
        $districts = [];
        if (!empty($order->province_id)) {
            $response = Http::get("https://provinces.open-api.vn/api/p/{$order->province_id}?depth=2");

            $districtData = json_decode($response->body(), true);
            $districts = $districtData['districts'] ?? [];
        }

        // Kiểm tra và lấy tên quận/huyện
        $districtName = '';
        foreach ($districts as $district) {
            if (!empty($order->district_id) && $district['code'] == $order->district_id) {
                $districtName = $district['name'];
                break;
            }
        }
        $template = 'backend.orders.show';
        return view('backend.dashboard.layout', compact(
            'template',
            'order',
            'user',
            'provinces',
            'districts',
            'provinceName',
            'districtName'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Lấy thông tin đơn hàng
        $order = Order::findOrFail($id);

        // Lấy danh sách tỉnh/thành phố từ API
        $response = Http::get('https://provinces.open-api.vn/api/p');
        $provinces = json_decode($response->body(), true) ?? [];

        // Kiểm tra và lấy tên tỉnh/thành phố
        $provinceName = '';
        foreach ($provinces as $province) {
            if (!empty($order->province_id) && $province['code'] == $order->province_id) {
                $provinceName = $province['name'];
                break;
            }
        }


        // Lấy danh sách quận/huyện theo tỉnh cũ nếu có
        $districts = [];
        if (!empty($order->province_id)) {
            $response = Http::get("https://provinces.open-api.vn/api/p/{$order->province_id}?depth=2");

            $districtData = json_decode($response->body(), true);
            $districts = $districtData['districts'] ?? [];
        }

        // Kiểm tra và lấy tên quận/huyện
        $districtName = '';
        foreach ($districts as $district) {
            if (!empty($order->district_id) && $district['code'] == $order->district_id) {
                $districtName = $district['name'];
                break;
            }
        }

        // Truyền dữ liệu vào view
        $template = 'backend.orders.edit';
        return view('backend.dashboard.layout', compact('template', 'order', 'provinces', 'districts', 'provinceName', 'districtName'));
    }
    public function getDistricts(Request $request, $provinceId)
    {
        $response = Http::get("https://provinces.open-api.vn/api/p/{$provinceId}?depth=2");
        $districtData = json_decode($response->body(), true);
        $districts = $districtData['districts'] ?? [];

        return response()->json($districts);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'shipping_user_name' => in_array($request->status, ['delivered', 'completed', 'canceled']) ? 'nullable|string|max:255' : 'required|string|max:255',
            'shipping_email' => in_array($request->status, ['delivered', 'completed', 'canceled']) ? 'nullable|email|max:255' : 'required|email|max:255',
            'shipping_phone' => in_array($request->status, ['delivered', 'completed', 'canceled']) ? 'nullable|string|max:20' : 'required|string|max:20',
            'shipping_address' => in_array($request->status, ['delivered', 'completed', 'canceled']) ? 'nullable|string|max:255' : 'required|string|max:255',
            'specific_address' => in_array($request->status, ['delivered', 'completed', 'canceled']) ? 'nullable|string|max:255' : 'required|string|max:255',
            'status' => 'nullable|string|in:pending,processing,delivered,completed,canceled',
            'payment_method' => 'string|in:cash,vn_pay,momo',
            'payment_status' => 'nullable|in:0,1',
            'notes' => 'nullable|string', // Loại bỏ yêu cầu "required" cho ghi chú, cho phép null
        ], [
            'shipping_user_name.required' => 'Tên người nhận không được để trống.',
            'shipping_email.required' => 'Email người nhận không được để trống.',
            'shipping_email.email' => 'Email người nhận không hợp lệ.',
            'shipping_email.max' => 'Email người nhận không được vượt quá 255 ký tự.',
            'shipping_phone.required' => 'Số điện thoại không được để trống.',
            'shipping_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'shipping_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'shipping_address.required' => 'Địa chỉ giao hàng không được để trống.',
            'specific_address.required' => 'Địa chỉ chi tiết không được để trống.',
        ]);

        // Lấy trạng thái mới từ yêu cầu
        $newStatus = $request->status;

        // Kiểm tra chuyển trạng thái hợp lệ theo $validTransitions
        if ($newStatus) {
            $validTransitions = [
                'pending' => ['pending', 'processing', 'canceled'],
                'processing' => ['processing', 'delivered', 'canceled'],
                'delivered' => ['delivered', 'completed', 'canceled'],
                'completed' => [],
                'canceled' => ['pending', 'processing', 'delivered']
            ];

            // Kiểm tra xem trạng thái mới có hợp lệ không
            if (!in_array($newStatus, $validTransitions[$order->status])) {
                return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
            }
        }

        // Kiểm tra nếu trạng thái của đơn hàng là 'delivered', 'completed' hoặc 'canceled'
        if (in_array($order->status, ['delivered', 'completed', 'canceled'])) {
            // Nếu có bất kỳ trường nào sau đây được cập nhật, trả về thông báo lỗi
            if ($request->hasAny(['shipping_user_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'specific_address', 'province_id', 'district_id'])) {
                return redirect()->back()->with('error', 'Bạn không thể thay đổi thông tin giao hàng khi đơn hàng đã được giao, hoàn thành hoặc hủy.')->withInput();
            }

            // Chỉ cập nhật trạng thái nếu có thay đổi
            $dataToUpdate = ['status' => $newStatus ?? $order->status];
        } else {
            // Nếu trạng thái không phải 'delivered', 'completed' hoặc 'canceled', cho phép cập nhật các thông tin khác
            $dataToUpdate = array_merge([
                'status' => $newStatus ?? $order->status,
                'shipping_user_name' => $request->get('shipping_user_name', $order->shipping_user_name),
                'shipping_email' => $request->get('shipping_email', $order->shipping_email),
                'shipping_phone' => $request->get('shipping_phone', $order->shipping_phone),
                'shipping_address' => $request->get('shipping_address', $order->shipping_address),
                'specific_address' => $request->get('specific_address', $order->specific_address),
                'province_id' => $request->get('province_id', $order->province_id),
                'district_id' => $request->get('district_id', $order->district_id),
                'notes' => $request->get('notes', $order->notes),
            ]);
        }

        // Cập nhật đơn hàng
        try {
            $order->update($dataToUpdate);
            return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $oder)
    {
        //
    }
    /**
     * Force Remove the specified resource from storage.
     */
    public function forceDestroy(Order $oder)
    {
        //
    }
}
