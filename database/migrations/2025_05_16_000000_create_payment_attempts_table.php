<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('order_code')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->json('selected_items')->nullable();
            $table->json('shipping_info')->nullable();
            $table->json('coupon_info')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_attempts');
    }
};
