<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vnpays', function (Blueprint $table) {
            $table->id();
            $table->string('vnp_TxnRef')->unique(); // Mã giao dịch VNPay
            $table->integer('vnp_Amount'); // Số tiền giao dịch (Được chuyển sang đơn vị đồng)
            $table->string('vnp_BankCode')->nullable(); // Mã ngân hàng (Có thể rỗng)
            $table->string('vnp_OrderInfo'); // Thông tin đơn hàng
            $table->string('vnp_OrderType'); // Loại đơn hàng (ví dụ: billpayment)
            $table->string('vnp_SecureHash'); // Chữ ký bảo mật
            $table->string('vnp_PayDate'); // Ngày thanh toán
            $table->timestamps(); // created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('vnpays');
    }
};
