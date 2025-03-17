<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCouponsTable extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('max_uses'); // Xóa max_uses
            $table->integer('quantity')->default(1); // Thêm số lượng mã giảm giá
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('max_uses')->nullable(); // Nếu rollback thì thêm lại
            $table->dropColumn('quantity'); // Xóa quantity nếu rollback
        });
    }
}

