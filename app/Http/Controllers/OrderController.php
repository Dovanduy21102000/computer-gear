<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Province;
use Illuminate\Http\Request;
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
        $template = 'backend.orders.show';
        return view('backend.dashboard.layout', compact('template', 'order', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // // Lấy thông tin người dùng liên kết với đơn hàng
        // $user = $order->user;

        // // Gọi API để lấy danh sách tỉnh thành phố
        // $response = Http::get('https://provinces.open-api.vn/api/p');
        // $provinc = json_decode($response->body(), true);
        // // dd($provinces);
        // // Kiểm tra nếu API trả về đúng dạng mảng
        // if (is_array($provinc)) {
        //     $provinces = $provinc; // Lấy phần 'data' chứa danh sách tỉnh/thành phố
        //     // dd($provinces);
        // } else {
        //     $provinces = [];
        // }
        // // dd($provinces);
        // // Kiểm tra xem đã có mã tỉnh (provinceCode) hay chưa
        // $provinceCode = $order->shipping_city; // Giả sử mã tỉnh được lưu trong trường 'shipping_city'

        // // Nếu có mã tỉnh thì lấy danh sách quận huyện
        // $districts = [];
        // // dd($response->body());
        // if ($provinceCode) {
        //     $response = Http::get("https://provinces.open-api.vn/api/p/01?depth={$provinceCode}");
        //     $district = json_decode($response->body(), true);
        //     if (is_array($district)) {

        //         $districts = $district;

        //         // dd($district); // Lấy phần 'data' chứa danh sách quận/huyện
        //     } else {
        //         $districts = [];
        //     }
        // }

        // // Đặt tên template và truyền các biến vào view
        // $template = 'backend.orders.edit';
        // return view('backend.dashboard.layout', compact('template', 'order', 'user', 'provinces', 'districts'));


        // Lấy thông tin đơn hàng dựa trên ID
        $order = Order::findOrFail($id);
        $user = $order->user;

        // Lấy thông tin tỉnh thành phố (province) từ API
        $response = Http::get('https://provinces.open-api.vn/api/p');
        $province = json_decode($response->body(), true); // Giả sử API trả về dữ liệu là mảng
        // dd($response->body());  // In ra dữ liệu trả về từ API để kiểm tra

        // Kiểm tra nếu API trả về dữ liệu hợp lệ
        if (is_array($province)) {
            $provinces = $province; // Nếu không có dữ liệu thì gán mảng rỗng
        } else {
            $provinces = [];
        }

        // dd($provinces);
        // Lấy thông tin quận huyện dựa trên province_id
        $districts = [];
        if ($order->province_id) {
            // Gọi API hoặc lấy quận huyện theo province_id
            $response = Http::get("https://provinces.open-api.vn/api/p/{$order->province_id}?depth=02");
            $districtData = json_decode($response->body(), true);
            if (is_array($districtData)) {
                $districts = $districtData; // Lấy danh sách quận huyện từ API
            }
        }

        // Truyền các thông tin vào view để hiển thị
        $template = 'backend.orders.edit'; // Tên view
        return view('backend.dashboard.layout', compact('template', 'order', 'provinces', 'districts'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $oder)
    {
        //
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
    // public function getProvinces()
    // {
    //     $response = Http::get('https://provinces.open-api.vn/api/pprovinces');
    //     $provinces = $response->json();

    //     return response()->json($provinces);
    // }

    // // Lấy quận/huyện theo tỉnh
    // public function getDistricts($provinceCode)
    // {
    //     $response = Http::get("https://provinces.open-api.vn/api/pprovinces/{$provinceCode}/districts");
    //     $districts = $response->json();

    //     return response()->json($districts);
    // }

    // Lấy phường/xã theo quận
    // public function getWards($districtCode)
    // {
    //     $response = Http::get("https://provinces.open-api.vn/api/pdistricts/{$districtCode}/wards");
    //     $wards = $response->json();

    //     return response()->json($wards);
    // }
}
