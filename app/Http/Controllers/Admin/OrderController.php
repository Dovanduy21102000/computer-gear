<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CancelRequestStatusMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        // Lọc các đơn hàng không có trạng thái 'canceled' và 'pending_cancel'
        $orders = Order::whereNotIn('status', ['canceled', 'pending_cancel'])
            ->orderBy('created_at', 'desc')
            ->get();

        $template = 'backend.orders.index';

        return view('backend.dashboard.layout', compact('orders', 'template'));
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
        $orderItems = $order->items()->with('product', 'productVariant')->get();
        $template = 'backend.orders.show';
        return view('backend.dashboard.layout', compact(
            'template',
            'order',
            'user',
            'provinces',
            'districts',
            'provinceName',
            'districtName',
            'orderItems'
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
        // Lấy trạng thái mới từ request hoặc giữ nguyên trạng thái cũ
        $newStatus = $request->input('status', $order->status);
        if ($newStatus === 'success') {
            return redirect()->back()->with('error', 'Không được cập nhật trạng thái hoàn thành bằng tay.');
        }
        // Định nghĩa các trạng thái hợp lệ khi chuyển đổi
        $validTransitions = [
            'pending' => ['pending', 'processing', 'canceled'],
            'processing' => ['processing', 'delivered', 'canceled'],
            'delivered' => ['delivered', 'completed', 'canceled'],
            'completed' => ['completed','success'],
            'success'=> [],
            'canceled' => ['pending', 'processing', 'delivered']
        ];

        // Kiểm tra xem trạng thái mới có hợp lệ không
        if (!isset($validTransitions[$order->status]) || !in_array($newStatus, $validTransitions[$order->status])) {
            return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
        }

        // Nếu đơn hàng đã giao, hoàn thành hoặc hủy => KHÔNG CHO PHÉP CHỈNH SỬA BẤT KỲ THÔNG TIN NÀO, chỉ được đổi trạng thái hợp lệ
        if (in_array($order->status, ['delivered', 'completed', 'canceled'])) {
            if ($newStatus === $order->status) {
                return redirect()->back()->with('error', 'Bạn không thể chỉnh sửa đơn hàng đã hoàn thành hoặc bị hủy.');
            }

            // Chỉ cho phép cập nhật trạng thái nếu hợp lệ
            try {
                $order->update(['status' => $newStatus]);
                return redirect()->route('orders.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại.')->withInput();
            }
        }

        // Nếu trạng thái mới là "canceled", "delivered", hoặc "completed" thì KHÔNG yêu cầu các trường vận chuyển
        $requiresValidation = !in_array($newStatus, ['delivered', 'completed', 'canceled']);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'shipping_user_name' => $requiresValidation ? 'required|string|max:255' : 'nullable|string|max:255',
            'shipping_email' => $requiresValidation ? 'required|email|max:255' : 'nullable|email|max:255',
            'shipping_phone' => $requiresValidation ? 'required|string|max:20' : 'nullable|string|max:20',
            'shipping_address' => $requiresValidation ? 'required|string|max:255' : 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,processing,delivered,completed,canceled',
            'payment_method' => 'nullable|string|in:cash,vn_pay,momo',
            'payment_status' => 'nullable|in:0,1',
            'notes' => 'nullable|string',
        ], [
            'shipping_user_name.required' => 'Tên người nhận không được để trống.',
            'shipping_email.required' => 'Email người nhận không được để trống.',
            'shipping_email.email' => 'Email người nhận không hợp lệ.',
            'shipping_phone.required' => 'Số điện thoại không được để trống.',
            'shipping_address.required' => 'Địa chỉ giao hàng không được để trống.',
        ]);

        // Cập nhật thông tin đơn hàng (chỉ khi chưa ở trạng thái "delivered", "completed", "canceled")
        $dataToUpdate = [
            'status' => $newStatus,
            'shipping_user_name' => $request->input('shipping_user_name', $order->shipping_user_name),
            'shipping_email' => $request->input('shipping_email', $order->shipping_email),
            'shipping_phone' => $request->input('shipping_phone', $order->shipping_phone),
            'shipping_address' => $request->input('shipping_address', $order->shipping_address),
            'province_id' => $request->input('province_id', $order->province_id),
            'district_id' => $request->input('district_id', $order->district_id),
            'notes' => $request->input('notes', $order->notes),
        ];

        try {
            $order->update($dataToUpdate);
            return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại.')->withInput();
        }
    }

    public function cancelTabs()
    {
        // Lấy các đơn hàng đã huỷ với trạng thái 'canceled'
        $canceledOrders = Order::where('status', 'canceled')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);  // Phân trang với 10 đơn mỗi trang
        $pendingCancelOrders = Order::where('cancel_requested', true)  // Yêu cầu huỷ
            ->where('status', 'pending_cancel')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        $template = 'backend.orders.canceled';
        return view('backend.dashboard.layout', compact('canceledOrders', 'pendingCancelOrders', 'template'));
    }



    public function approveCancel($id)
    {
        $order = Order::findOrFail($id);

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'pending_cancel') {
            return back()->with('error', 'Đơn hàng không thể huỷ vì không phải trạng thái đang xử lý.');
        }

        // Kiểm tra yêu cầu huỷ
        if (!$order->cancel_requested) {
            return back()->with('error', 'Đơn hàng này không có yêu cầu huỷ.');
        }

        // Cộng lại số lượng sản phẩm vào kho
        foreach ($order->orderItems as $item) {
            if ($item->product_variant_id) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->quantity += $item->quantity;
                    $variant->save();
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }
        // Cập nhật trạng thái đơn hàng
        $order->status = 'canceled';
        $order->cancel_requested = false;
        $order->save();

        // Gửi email thông báo
        try {
            Mail::to($order->shipping_email)->send(new CancelRequestStatusMail($order, true));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể gửi email thông báo. Lỗi: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã duyệt yêu cầu huỷ đơn hàng.');
    }

    public function rejectCancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'pending_cancel' && $order->cancel_requested) {
            $order->cancel_requested = false;
            $order->cancel_reason = null;
            $order->save();
            Mail::to($order->shipping_email)->send(new CancelRequestStatusMail($order, false));

            return back()->with('success', 'Đã từ chối yêu cầu huỷ đơn hàng.');
        }
        return back()->with('error', 'Không thể từ chối yêu cầu.');
    }
}
