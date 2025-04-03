<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // public function up(): void
    // {
    //     Schema::table('orders', function (Blueprint $table) {
    //         // Đổi tên cột shipping_city thành province_id
    //         $table->renameColumn('shipping_city', 'province_id');

    //         // Đổi tên cột shipping_province thành district_id
    //         $table->renameColumn('shipping_province', 'district_id');

    //         // Thay đổi kiểu dữ liệu của cột
    //         $table->unsignedBigInteger('province_id')->nullable()->change();
    //         $table->unsignedBigInteger('district_id')->nullable()->change();

    //         // Thêm index cho district_id
    //         $table->index('district_id');
    //     });
    // }

    // public function down(): void
    // {
    //     Schema::table('orders', function (Blueprint $table) {
    //         // Đổi lại tên cột nếu rollback
    //         $table->renameColumn('province_id', 'shipping_city');
    //         $table->renameColumn('district_id', 'shipping_province');

    //         // Thay đổi lại kiểu dữ liệu ban đầu
    //         $table->string('shipping_city')->nullable()->change();
    //         $table->string('shipping_province')->nullable()->change();

    //         // Xóa index
    //         $table->dropIndex(['district_id']);
    //     });
    // }
};