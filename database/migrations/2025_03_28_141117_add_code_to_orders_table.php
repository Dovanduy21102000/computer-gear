<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    // public function up()
    // {
    //     Schema::table('orders', function (Blueprint $table) {
    //         if (!Schema::hasColumn('orders', 'code')) {
    //             $table->string('code')->after('id')->nullable(false);
    //         }
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('orders', function (Blueprint $table) {
    //         if (Schema::hasColumn('orders', 'code')) {
    //             $table->dropColumn('code');
    //         }

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('code')->after('id'); // Thêm cột 'code' ngay sau cột 'id'
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('code'); // Xóa cột nếu rollback

        });
    }
};