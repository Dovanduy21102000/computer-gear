<?php


namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;

// use App\Models\Category;
use App\Models\CategoryPost;

use Illuminate\Http\Request;

use App\Models\Post;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('status', 1); // Lấy bài viết đã xuất bản

        // Lọc theo danh mục nếu có
        if ($request->has('category_post_id') && !empty($request->category_post_id)) {
            $query->where('category_post_id', $request->category_post_id);
        }
    
        // Lọc theo từ khóa tìm kiếm nếu có
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
    
        $posts = $query->latest()->paginate(5);
    
        // Lấy danh sách danh mục để hiển thị trên form tìm kiếm
        $category_post = CategoryPost::all();
    
        $template = 'fontend.blog.index';
        return view('fontend.layout', compact('template', 'posts', 'category_post'));
    }

    public function show(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $recentPosts = Post::where('status', 1)->latest()->take(5)->get();
        $category_post = CategoryPost::all();
    
        // Kiểm tra xem có tham số category_post_id trong request không
        $categoryId = $request->input('category_post_id');
        if ($categoryId) {
            $filteredPosts = Post::where('status', 1)
                ->whereHas('category_post', function ($query) use ($categoryId) {
                    $query->where('categories.id', $categoryId);
                })
                ->latest()
                ->get();
        } else {
            $filteredPosts = collect(); // Trả về collection rỗng nếu không lọc theo danh mục
        }
    
        $template = 'fontend.blog.show';
    
        return view('fontend.layout', compact('template', 'post', 'recentPosts', 'category_post', 'filteredPosts'));
    }
    
}