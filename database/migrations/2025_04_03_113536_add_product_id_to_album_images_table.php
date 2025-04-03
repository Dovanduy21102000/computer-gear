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
        Schema::table('album_images', function (Blueprint $table) {
            // Thêm cột product_id vào bảng album_images
            $table->unsignedBigInteger('product_id')->after('id');  // Thêm sau cột 'id'
            
            // Tạo khóa ngoại với bảng products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('album_images', function (Blueprint $table) {
            // Xóa khóa ngoại và cột product_id
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};
