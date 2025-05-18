<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
};
