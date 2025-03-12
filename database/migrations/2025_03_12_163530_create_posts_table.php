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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Category::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_hot')->default(0);
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
