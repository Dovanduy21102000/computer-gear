<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        // Kiểm tra xem cột 'code' đã tồn tại chưa
        if (!Schema::hasColumn('orders', 'code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('code')->after('id'); // Thêm cột 'code' nếu chưa tồn tại
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('code'); // Xóa cột nếu rollback
        });
    }
};
