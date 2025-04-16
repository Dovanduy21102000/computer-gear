<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sửa cột status để thêm giá trị 'pending_cancel'
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'delivered', 'completed', 'canceled', 'pending_cancel'])
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nếu cần, bạn có thể quay lại sửa ENUM bằng cách loại bỏ giá trị 'pending_cancel'
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'delivered', 'completed', 'canceled'])
                ->default('pending')
                ->change();
        });
    }
}
