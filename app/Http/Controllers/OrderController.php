<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        // // Xác thực dữ liệu đầu vào
        // $request->validate([
        //     'shipping_user_name' => 'nullable|string|max:255',
        //     'shipping_email' => 'nullable|email|max:255',
        //     'shipping_phone' => 'nullable|string|max:20',
        //     'shipping_address' => 'nullable|string|max:255',
        //     'specific_address' => 'nullable|string|max:255',
        //     'status' => 'nullable|string|in:pending,processing,delivered,completed,canceled',
        //     'payment_method' => 'string|in:cash,vn_pay,momo',
        //     'payment_status' => 'nullable|in:0,1',
        //     'notes' => 'nullable|string',
        // ]);

        // // Lấy trạng thái hiện tại và trạng thái mới của đơn hàng
        // $newStatus = $request->status;


        // // Định nghĩa trạng thái hợp lệ có thể chuyển đổi từ trạng thái hiện tại
        // if ($newStatus) {
        //     $validTransitions = [
        //         'pending' => ['pending', 'processing', 'canceled'],
        //         'processing' => ['processing', 'delivered', 'canceled'],
        //         'delivered' => ['delivered', 'completed', 'canceled'],
        //         'completed' => [], // Completed không thể thay đổi
        //         'canceled' => ['pending', 'processing', 'delivered']
        //     ];

        //     if (!in_array($newStatus, $validTransitions[$order->status])) {
        //         return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ.');
        //     }
        // }


        // // Cập nhật thông tin đơn hàng nếu chưa hoàn thành
        // $order->update([
        //     'shipping_user_name' => $request->shipping_user_name ?? $order->shipping_user_name,
        //     'shipping_email' => $request->shipping_email ?? $order->shipping_email,
        //     'shipping_phone' => $request->shipping_phone ?? $order->shipping_phone,
        //     'shipping_address' => $request->shipping_address ?? $order->shipping_address,
        //     'specific_address' => $request->specific_address ?? $order->specific_address,
        //     'status' => $newStatus ?? $order->status,
        //     'payment_method' => $request->payment_method ?? $order->payment_method,
        //     'notes' => $request->notes ?? $order->notes,
        //     'payment_status' => $request->payment_status ?? $order->payment_status,
        // ]);


        // return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công.');


        // Xác thực dữ liệu đầu vào
        $request->validate([
            'shipping_user_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'specific_address' => 'required|string|max:255',
            'status' => 'nullable|string|in:pending,processing,delivered,completed,canceled',
            'payment_method' => 'string|in:cash,vn_pay,momo',
            'payment_status' => 'nullable|in:0,1',
            'notes' => 'required|string',
        ]);

        // Lấy trạng thái hiện tại và trạng thái mới của đơn hàng
        $newStatus = $request->status;

        // Định nghĩa trạng thái hợp lệ có thể chuyển đổi từ trạng thái hiện tại
        if ($newStatus) {
            $validTransitions = [
                'pending' => ['pending', 'processing', 'canceled'],
                'processing' => ['processing', 'delivered', 'canceled'],
                'delivered' => ['delivered', 'completed', 'canceled'],
                'completed' => [], // Completed không thể thay đổi
                'canceled' => ['pending', 'processing', 'delivered']
            ];

            if (!in_array($newStatus, $validTransitions[$order->status])) {
                return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ.');
            }
        }

        // Khởi tạo mảng dữ liệu để cập nhật
        $dataToUpdate = [];

        // Kiểm tra trạng thái của đơn hàng
        if (in_array($order->status, ['pending', 'processing'])) {
            $dataToUpdate['shipping_user_name'] = $request->has('shipping_user_name') ? $request->shipping_user_name : $order->shipping_user_name;
            $dataToUpdate['shipping_email'] = $request->has('shipping_email') ? $request->shipping_email : $order->shipping_email;
            $dataToUpdate['shipping_phone'] = $request->has('shipping_phone') ? $request->shipping_phone : $order->shipping_phone;
            $dataToUpdate['shipping_address'] = $request->has('shipping_address') ? $request->shipping_address : $order->shipping_address;
            $dataToUpdate['specific_address'] = $request->has('specific_address') ? $request->specific_address : $order->specific_address;
            $dataToUpdate['province_id'] = $request->has('province_id') ? $request->province_id : $order->province_id;
            $dataToUpdate['district_id'] = $request->has('district_id') ? $request->district_id : $order->district_id;
            $dataToUpdate['notes'] = $request->has('notes') ? $request->notes : $order->notes;
        }

        // Các trường không bao giờ được phép chỉnh sửa
        $dataToUpdate['status'] = $newStatus ?? $order->status;

        try {
            $order->update($dataToUpdate);
            return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật đơn hàng. Vui lòng thử lại.')->withInput();
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
