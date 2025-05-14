<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thay đổi kiểu dữ liệu
            $table->decimal('total_price', 15, 2)->change();
            $table->decimal('final_price', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Quay lại kiểu cũ
            $table->decimal('total_price', 10, 2)->change();
            $table->decimal('final_price', 10, 2)->change();
        });
    }
};
