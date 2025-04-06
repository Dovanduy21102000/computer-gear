<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumImage extends Model
{
    use HasFactory;

    // Khai báo tên bảng (nếu khác với tên mặc định)
    protected $table = 'album_images';

    // Khai báo các trường có thể gán đại trà (mass assignable)
    protected $fillable = ['image', 'product_id']; // Thêm 'product_id' nếu muốn liên kết với sản phẩm

    // Khai báo quan hệ với sản phẩm (nếu có)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

