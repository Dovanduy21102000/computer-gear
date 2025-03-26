<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryPost extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'category_post';
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'is_active',
    ];

    public $attributes = [
        'is_active' => 1
    ];
    // App\Models\Category.php


    public function posts()
    {
        return $this->hasMany(Post::class, 'category_post_id');
    }

    public function children()
    {
        return $this->hasMany(CategoryPost::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CategoryPost::class, 'parent_id');
    }
}
