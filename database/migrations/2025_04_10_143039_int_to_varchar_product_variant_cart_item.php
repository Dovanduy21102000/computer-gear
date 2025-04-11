<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add temp column
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']); // drops by column
            $table->string('product_variant_id_temp', 255)->nullable();
        });

        // 2. Cast and copy data
        DB::statement('UPDATE cart_items SET product_variant_id_temp = CAST(product_variant_id AS CHAR)');

        // 3. Drop old column
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('product_variant_id');
        });

        // 4. Add new VARCHAR column
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('product_variant_id', 255)->nullable();
        });

        // 5. Copy back values
        DB::statement('UPDATE cart_items SET product_variant_id = product_variant_id_temp');

        // 6. Drop temp column
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('product_variant_id_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: convert back to BIGINT if needed
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id_temp')->nullable();
        });

        DB::statement('UPDATE cart_items SET product_variant_id_temp = CAST(product_variant_id AS UNSIGNED)');

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('product_variant_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->nullable();
        });

        DB::statement('UPDATE cart_items SET product_variant_id = product_variant_id_temp');

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('product_variant_id_temp');
        });
    }
};
