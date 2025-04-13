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
        // Kiểm tra xem cột đã tồn tại chưa
        if (!Schema::hasColumn('products', 'quantity_sold')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('quantity_sold')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity_sold');
        });
    }
};
