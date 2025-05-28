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
        Schema::table('posts', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['category_id']);

            // Rename the column
            $table->renameColumn('category_id', 'category_post_id');

            // Add the new foreign key constraint
            $table->foreign('category_post_id')
                ->references('id')
                ->on('category_post')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['category_post_id']);

            // Rename the column back
            $table->renameColumn('category_post_id', 'category_id');

            // Add back the original foreign key constraint
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }
};
