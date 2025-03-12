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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->string('shipping_user_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('specific_address')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);
            $table->boolean('payment_status')->default(0);
            $table->enum('status', [
                'pending',
                'processing',
                'delivered',
                'completed',
                'canceled',
            ])->default('pending');
            $table->enum('payment_method', [
                'vn_pay',
                'momo',
                'cash'
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
