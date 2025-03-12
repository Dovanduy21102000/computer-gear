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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed'])->default('fixed');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('maximum_amount', 10, 2)->default(0);
            $table->decimal('min_order_total', 10, 2)->default(0);
            $table->integer('max_uses')->default(0);
            $table->integer('used_count')->default(0);
            $table->boolean('status')->default(1);
            $table->date('expire_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
