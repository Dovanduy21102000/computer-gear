<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = ['user_id', 'product_id', 'content', 'status'];

    // Liên kết với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Liên kết với Sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopesStatus($query, $status){
        return $query->where('status', $status);
    }
}
