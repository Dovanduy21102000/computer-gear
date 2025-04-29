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
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        $products = Product::all();

        $wishlistProductIds = [];
            if (Auth::check()) {
                $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                                      ->pluck('product_id')
                                      ->toArray();
            }
        $template = 'fontend.wishlist.index';
        return view('fontend.layout', compact('template', 'wishlistProductIds', 'products','wishlists'));
    }

    public function store(Request $request)
    {
        $productId = $request->input('product_id');

        // Kiểm tra đã tồn tại trong wishlist chưa
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Đã thêm vào yêu thích']);
    }

    public function destroy($id)
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Đã xoá khỏi danh sách yêu thích');
    }
}
