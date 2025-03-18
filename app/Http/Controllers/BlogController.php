<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài viết có trạng thái "published"
        $blogs = Post::where('status', 'published')->orderBy('created_at', 'desc')->get();

        // Trả dữ liệu ra view frontend
        $template = 'fontend.blog.index';
        return view('fontend.layout', compact('template', 'blogs'));
    }

    public function show($slug)
    {
        // Lấy bài viết theo slug
        $blog = Post::where('slug', $slug)->firstOrFail();

        return view('fontend.blog.detail', compact('blog'));
    }
}

