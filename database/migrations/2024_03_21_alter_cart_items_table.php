<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('price_at_checkout', 12, 2)->nullable()->after('quantity');
            $table->timestamp('checkout_at')->nullable()->after('price_at_checkout');
            $table->string('checkout_session_id')->nullable()->after('checkout_at');
            $table->index('checkout_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['checkout_session_id']);
            $table->dropColumn([
                'price_at_checkout',
                'checkout_at',
                'checkout_session_id'
            ]);
        });
    }
};
