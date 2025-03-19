<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách bài viết có trạng thái "published"
        // $posts = Post::where('status', 1)->take(5)->get();
        $categoryId = $request->input('category_id');

        // Nếu có category_id, lọc bài viết theo danh mục
        if ($categoryId) {
            $posts = Post::where('category_id', $categoryId)->where('status', 1)->latest()->paginate(5);
        } else {
            // Nếu không có category_id, lấy tất cả bài viết có trạng thái "published"
            $posts = Post::where('status', 1)->latest()->paginate(5);
        }
    
        // Trả dữ liệu ra view frontend
        $template = 'fontend.blog.index';
        return view('fontend.layout', compact('template', 'posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $recentPosts = Post::where('status', 1)->latest()->take(5)->get();
    
        $template = 'fontend.blog.show'; // Kế thừa từ layout
    
        // dd($template);
        // die;
        return view('fontend.layout', compact('template', 'post', 'recentPosts'));
    }
}