<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm trong wishlist của người dùng
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        $products = Product::all();

        // Lấy các ID sản phẩm yêu thích của người dùng
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                                          ->pluck('product_id')
                                          ->toArray();
        }

        // Hiển thị template wishlist
        $template = 'fontend.wishlist.index';
        return view('fontend.layout', compact('template', 'wishlistProductIds', 'products','wishlists'));
    }

    public function store(Request $request)
    {
        $productId = $request->input('product_id');

        // Kiểm tra nếu sản phẩm đã tồn tại trong wishlist
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if (!$exists) {
            // Nếu chưa có, thêm sản phẩm vào wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
            return response()->json(['success' => true, 'message' => 'Đã thêm vào yêu thích']);
        } else {
            // Nếu đã có, xóa sản phẩm khỏi wishlist
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
            return response()->json(['success' => true, 'message' => 'Đã bỏ yêu thích']);
        }
    }

    public function destroy($id)
    {
        // Xóa sản phẩm khỏi wishlist
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Đã xoá khỏi danh sách yêu thích');
    }
}
