<?php

include_once 'helper_momo.php';
header('Content-type: text/html; charset=utf-8');


$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán qua ATM MoMo";
$amount = $_POST['tong'];
$orderId = time() . "";
//    $redirectUrl = BASE_URL . "?act=perfect";
$redirectUrl = "http://computer-gear.com:81/";
$ipnUrl = "http://computer-gear.com:81/";
$extraData = "";




$requestId = time() . "";
$requestType = "payWithATM";
$extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
//before sign HMAC SHA256 signature
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);
$data = array(
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
);
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);  // decode json

// Create an associative array to hold the payUrl
$response = array('payUrl' => $jsonResult['payUrl']);

// Output the response as JSON
echo json_encode($response);
var_dump($jsonResult['payUrl']);
header('Location: ' . $jsonResult['payUrl']);
header("Refresh:5");
