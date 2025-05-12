<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class WishlistController extends Controller
{
     public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        $products = Product::all();

        $template = 'fontend.wishlists.index';
        return view('fontend.layout', compact('template', 'products','wishlists'));
    }
    public function toggle(Request $request, $productId)
    {
        $user = Auth::user();
        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added']);
        }
    }
    public function remove($productId)
    {
    $user = auth()->user();
        $deleted = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();

    return redirect()->route('wishlist.index')->with('success', 'Đã xóa khỏi danh sách yêu thích');
    }
}