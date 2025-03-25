<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MOMOController extends Controller
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;

    public function __construct()
    {
        $this->endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $this->partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
        $this->accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
        $this->secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
    }

    public function createPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán đơn hàng qua MoMo ATM";
        $amount = $request->total_price;
        $orderId = time() . "";
        $redirectUrl = route('home.index');
        $ipnUrl = route('momo.ipn');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithCC"; // Cập nhật để dùng MoMo ATM

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        // dd($data);

        $response = Http::post($endpoint, $data);
        $result = $response->json();

        if (isset($result['payUrl'])) {
            return redirect($result['payUrl']); // Chuyển hướng đến trang nhập thông tin ngân hàng
        }

        return back()->with('error', 'Lỗi khi tạo thanh toán MoMo ATM.');
    }

    public function ipn(Request $request)
    {
        return response()->json(["status" => "success"]);
    }

    public function handleReturn(Request $request)
    {
        if ($request->query('resultCode') == 0) {
            return redirect('/checkout-success')->with('success', 'Thanh toán thành công!');
        } else {
            return redirect('/checkout-failed')->with('error', 'Thanh toán thất bại!');
        }
    }

    public function handleIPN(Request $request)
    {
        // Xử lý IPN từ MoMo (cập nhật trạng thái đơn hàng)
        $data = $request->all();
        Log::info('MoMo IPN Data: ', $data);

        return response()->json(['message' => 'IPN Received'], 200);
    }
}
