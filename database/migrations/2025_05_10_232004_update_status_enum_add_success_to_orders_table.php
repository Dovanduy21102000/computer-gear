<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumAddSuccessToOrdersTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Thêm giá trị 'success' vào ENUM
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'processing',
                'delivered',
                'completed',
                'success',
                'canceled',
                'pending_cancel'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Quay lại ENUM cũ không có 'success'
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'processing',
                'delivered',
                'completed',
                'canceled',
                'pending_cancel'
            ])->default('pending')->change();
        });
    }
}
