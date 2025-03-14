<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(){
        $comments = Comment::with(['user', 'product'])->latest()->paginate(10);
        return view('backend.comment.index', compact('comments'));
    }
}
