<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_post_id',
        'title',
        'slug',
        'image',
        'description',
        'content',
        'status',
        'is_hot',
        'views',
    ];
    // Scope để lấy các bài viết có trạng thái "published"
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    public function category_post()
    {
        return $this->belongsTo(CategoryPost::class, 'category_id');
    }
}
