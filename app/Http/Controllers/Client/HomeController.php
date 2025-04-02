<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Banner; // Import model Banner
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $banners = Banner::where('status', 1)->get();

        // Lấy sản phẩm mới nhất, chỉ hiển thị nếu danh mục và thương hiệu có is_active = 1
        $newProducts = Product::whereHas('category', function ($query) {
            $query->where('is_active', 1);
        })->whereHas('brand', function ($query) {
            $query->where('is_active', 1);
        })->orderBy('created_at', 'desc')->take(24)->get();

        // Lấy sản phẩm top lượt xem
        $topViewedProducts = Product::with('brand')
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', 1);
            })
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Sản phẩm giảm giá
        $discountedProducts = Product::whereNotNull('price_sale')
            ->where('price_sale', '>', 0)
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', 1);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sản phẩm bán chạy
        $topSellingProducts = Product::with('brand')
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', 1);
            })
            ->orderBy('quantity_sold', 'desc')
            ->paginate(9);

        // Lấy 4 sản phẩm bất kỳ (chỉ lấy khi danh mục và thương hiệu is_active = 1)
        $products = Product::with('category')
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', 1);
            })
            ->take(4)
            ->get();

        // Lọc sản phẩm theo danh mục Chuột, Bàn phím, Bộ bàn phím và Chuột
        $topCategories = ['Chuột', 'Bàn phím', 'Bộ bàn phím và Chuột'];
        $keyboardMouseProducts = Product::whereHas('category', function ($query) use ($topCategories) {
            $query->whereIn('name', $topCategories)->where('is_active', 1);
        })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', 1);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $brands = Brand::where('is_active', 1)->get();
        $total_items = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        $template = 'fontend.home.index';
        return view('fontend.layout', compact(
            'template',
            'banners',
            'newProducts',
            'topViewedProducts',
            'discountedProducts',
            'topSellingProducts',
            'products',
            'keyboardMouseProducts',
            'brands',
            'total_items'
        ));
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
    // public function showByCategory($slug)
    // {
    //     // Lấy danh mục theo slug
    //     $category = Category::where('slug', $slug)->firstOrFail();

    //     // Lấy danh sách sản phẩm thuộc danh mục này
    //     $products = Product::where('category_id', $category->id)
    //         ->where('status', true)
    //         ->get();

    //     // Lấy danh sách danh mục cha
    //     $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

    //     // Lấy danh sách thương hiệu (CẦN DÒNG NÀY ĐỂ TRÁNH LỖI Undefined variable $brands)
    //     $brands = Brand::all();

    //     $template = 'fontend.products.index';

    //     return view('fontend.layout', compact('template', 'products', 'categories', 'category', 'brands'));
    // }


    public function faqs()
    {
        $template = 'fontend.home.faqs';
        return view('fontend.layout', compact('template'));
    }

    public function about_us()
    {
        $template = 'fontend.home.about_us';
        return view('fontend.layout', compact('template'));
    }
}
