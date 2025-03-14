<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(){
        $comments = Comment::paginate(10);
        $template = 'backend.comment.index';
        return view('backend.dashboard.layout', compact('comments','template'));
    }

    public function toggleStatus($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = $comment->status == 0 ? 1 : 0; // Chuyển đổi trạng thái
        $comment->save();

        return redirect()->route('comment.index')->with('success', 'Cập nhật trạng thái thành công!');
    }
}
