<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
class WishlistController extends Controller
{
    public function index(){
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        return view('fontend.wishlist.index', compact('wishlist'));
    }
    public function store(Request $request){
        $productId = $request->input('product_id');
        // check đã có trong mục sản phẩm yêu thích chưa
        $exists = Wishlist::when('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
        if(!$exists){
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' =>$productId,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Đã thêm vào yêu thích']);
    }
    public function destroy($id){
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Đã xoá khỏi danh sách yêu thích');
    }
}
