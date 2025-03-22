<?php

namespace App\Http\Controllers;

use App\Models\vnpay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $data = $request->all();
        $order_id = uniqid();  // Unique order ID
        $order_info = "Thanh toán đơn hàng #{$order_id}";  // Order description
        $amount = (int) $request->input('total_price');  // Amount in VND (ensure it's an integer)
        $returnUrl = route('vnpay.return');   // This is the URL VNPay will redirect to after payment completion
        $date = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
        $date->modify('+15 minutes');
        $vnp_ExpireDate = $date->format('YmdHis');
        // Ensure amount is an integer and multiply by 100 to convert to cents
        $amount = intval($amount) * 100;

        // Prepare input data to send to VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => env('VNP_TMN_CODE'), // Merchant code, set in .env file
            "vnp_Amount" => $data['total_price'],  // Convert amount to cents
            "vnp_Command" => "pay",  // Payment command
            "vnp_CreateDate" => date('YmdHis'),  // Current date and time
            "vnp_CurrCode" => "VND",  // Currency
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],  // User's IP address
            "vnp_Locale" => "vn",  // Language
            "vnp_OrderInfo" => $order_info,  // Order information
            "vnp_TxnRef" => $order_id,  // Transaction reference
            "vnp_ReturnUrl" => $returnUrl,  // The URL to redirect after payment completion
            "vnp_SecureHashType" => "SHA256",  // Hash algorithm
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_OrderType" => "billpayment"  // Type of transaction (e.g., bill payment)
        );

        // Sort data alphabetically to prepare for secure hash generation
        ksort($inputData);

        // Generate the secure hash
        $hashData = urldecode(http_build_query($inputData));  // Prepare data for hash calculation
        $vnp_SecureHash = hash_hmac('sha512', $hashData, env('VNP_HASH_SECRET'));  // Generate secure hash using the secret key
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;  // Add secure hash to input data

        // Generate the payment URL by appending the query string to VNPay URL
        $vnp_Url = env('VNP_URL') . "?" . http_build_query($inputData);  // This URL will send user to VNPay payment page

        // Log the input data to verify
        Log::info('VNPay Input Data:', $inputData);
        Log::info('Server Time: ' . date('Y-m-d H:i:s'));
        Log::info('VNPay Expiration Time:', ['vnp_ExpireDate' => $inputData['vnp_ExpireDate']]);
        Log::info('VNPay Hash Data:', ['hashData' => $hashData]);
        Log::info('VNPay Secure Hash:', ['vnp_SecureHash' => $vnp_SecureHash]);


        // Redirect to VNPay payment page
        return redirect($vnp_Url);
    }




    public function vn_pay(Request $request)
    {
        $data = $request->all();
        // dd($data);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:5173/checkout";


        $vnp_TmnCode = "M67ZGUOW";  // VNPay website code
        $vnp_HashSecret = "FAYRFHNOB5LNPMGX0YPVKJK7OKJGTG1N";  // Secret key

        $vnp_TxnRef = rand(00, 9999);  // Transaction reference ID, should be unique
        $vnp_OrderInfo = "thanh toán hóa đơn";
        $vnp_OrderType = "thanh toán online";
        $vnp_Amount = $data['total_price'] * 100;  // Convert to cents
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        // Prepare input data for VNPay
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sort input data
        ksort($inputData);

        // Generate query string and hash data
        $query = "";
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Add SecureHash
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Prepare data to return
        $returnData = [
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url,
            'input' => $inputData
        ];

        // If redirect flag is set, redirect to VNPay
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }

        // Save payment data to database
        $paymentData = [
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_Amount' => $vnp_Amount,
            'vnp_BankCode' => $vnp_BankCode,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_SecureHash' => $vnpSecureHash,
            'vnp_PayDate' => $inputData['vnp_CreateDate'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        vnpay::create($paymentData);  // Insert payment record to DB
    }


    public function updateVnpay(Request $request, String $vnp_TxnRef)
    {
        $paymentData = $request->input('paymentData');
        if (!$paymentData) {
            return response()->json(['message' => 'Dữ liệu thanh toán không được cung cấp'], 400);
        }

        $vnPay = vnpay::query()->where('vnp_TxnRef', $vnp_TxnRef)->first();
        if (!$vnPay) {
            return response()->json(['message' => 'Không tìm thấy giao dịch tương ứng'], 404);
        }

        if ($vnPay->vnp_ResponseCode === '00') {
            return response()->json(['message' => 'Giao dịch đã được thanh toán trước đó'], 404);
        }

        if ($paymentData['vnp_ResponseCode'] === '00') {
            $vnPay->update($paymentData);
            return response()->json(['message' => 'Thanh toán thành công'], 200);
        }

        return response()->json(['message' => 'Thanh toán không thành công'], 400);
    }




    public function paymentReturn(Request $request)
    {
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        if ($vnp_ResponseCode == '00') {
            return redirect()->route('checkout.success')->with('success', 'Thanh toán thành công!');
        } else {
            return redirect()->route('checkout.fail')->with('error', 'Thanh toán thất bại!');
        }
    }

    public function ipn(Request $request)
    {
        $inputData = $request->all();
        file_put_contents(storage_path('logs/vnpay_ipn.log'), json_encode($inputData) . "\n", FILE_APPEND);

        if ($request->get('vnp_ResponseCode') == '00') {
            return response()->json(['message' => 'Payment successful'], 200);
        } else {
            return response()->json(['message' => 'Payment failed'], 400);
        }
    }
}
