<?php

namespace App\Http\Controllers\Admin;  // Sửa namespace cho đúng với vị trí của controller

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index()
    {

        $comments = Comment::latest('id')->get();
        $template = 'backend.comments.index';

        return view('backend.dashboard.layout', compact('comments', 'template'));
    }


    public function show($id)
    {
        $comment = Comment::with('user', 'product')->findOrFail($id);
        $template = 'backend.comments.show';
        return view('backend.dashboard.layout', compact('comment', 'template'));
    }


    public function store(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        // Kiểm tra trạng thái của đơn hàng
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'success') // Trạng thái đơn hàng "hoàn thành"
            ->whereHas('items', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Bạn phải mua sản phẩm và có đơn hàng đã hoàn thành để bình luận.');
        }

        // Kiểm tra xem người dùng đã bình luận sản phẩm này chưa
        $existingComment = Comment::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingComment) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể cập nhật bình luận một lần!');
        }

        // Kiểm tra và lưu ảnh
        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('comments_images', 'public');
        }

        // Lưu bình luận
        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'content' => $request->content,
            'rating' => $request->rating,
            'image' => $imagePath,
            'status' => 1
        ]);

        $product = Product::find($request->product_id);
        return redirect()->route('client.products.detail', ['slug' => $product->slug])
            ->with(['success' => 'Bình luận và đánh giá thành công!']);
    }




    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment || $comment->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền sửa bình luận này.');
        }

        if ($comment->updated_at != $comment->created_at) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể chỉnh sửa bình luận một lần!');
        }

        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($comment->image) {
                Storage::delete('public/' . $comment->image);
            }

            $imagePath = $request->file('image')->store('comments_images', 'public');
            $comment->image = $imagePath;
        }

        $comment->content = $request->content;
        $comment->rating = $request->rating;
        $comment->save();

        return redirect()->route('client.products.detail', ['slug' => $comment->product->slug])
            ->with(['success' => 'Bình luận đã được cập nhật thành công!']);
    }

    // Ẩn hoặc hiện bình luận trong admin
    public function toggleStatus($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = !$comment->status;
        $comment->save();

        return redirect()->route('comments.index')->with('success', 'Cập nhật trạng thái thành công!');
    }
}
