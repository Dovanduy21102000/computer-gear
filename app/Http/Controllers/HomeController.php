<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Banner; // Import model Banner
use App\Models\Cart;
use App\Models\CartItem;

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $banners = Banner::where('status', 1)->get();
        $newProducts = Product::orderBy('created_at', 'desc')->take(24)->get();
        $topViewedProducts = Product::with('brand')->orderBy('views', 'desc')->take(5)->get();
        $discountedProducts = Product::whereNotNull('price_sale') // Lọc sản phẩm có giá giảm
            ->where('price_sale', '>', 0) // Đảm bảo giá giảm lớn hơn 0
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo mới nhất
            ->take(5) // Lấy 5 sản phẩm
            ->get();
        $topSellingProducts = Product::with('brand')->orderBy('quantity_sold', 'desc') // Sắp xếp theo lượt bán từ cao đến thấp
            ->paginate(9);
        $products = Product::with('category')->take(4)->get();

        $topCategories = ['Chuột', 'Bàn phím', 'Bộ bàn phím và chuột'];
        $keyboardMouseProducts = Product::whereHas('category', function ($query) {
            $query->whereIn('name', ['Chuột', 'Bàn phím', 'Bộ bàn phím và Chuột']);
        })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $brands = Brand::get();
        $template = 'fontend.home.index';
        return view('fontend.layout', compact('template', 'banners', 'newProducts', 'topViewedProducts', 'discountedProducts', 'topSellingProducts', 'products', 'keyboardMouseProducts', 'brands'));
    }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = 10;

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công');
    }
}
