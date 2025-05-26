<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, get the actual foreign key names
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'order_items' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        Schema::table('order_items', function (Blueprint $table) use ($foreignKeys) {
            // Drop existing foreign key constraints using their actual names
            foreach ($foreignKeys as $key) {
                $table->dropForeign($key->CONSTRAINT_NAME);
            }

            // Make columns nullable if they aren't already
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('product_variant_id')->nullable()->change();

            // Add new foreign key constraints with nullOnDelete
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->nullOnDelete();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the new foreign key names
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'order_items' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        Schema::table('order_items', function (Blueprint $table) use ($foreignKeys) {
            // Drop the new foreign key constraints
            foreach ($foreignKeys as $key) {
                $table->dropForeign($key->CONSTRAINT_NAME);
            }

            // Make product_id non-nullable again
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->unsignedBigInteger('product_variant_id')->nullable()->change();

            // Add back the original foreign key constraints
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->cascadeOnDelete();
        });
    }
};
