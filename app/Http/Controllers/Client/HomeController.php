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
use App\Models\CategoryPost;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        // Hiển thị banner đang hoạt động
        $banners = Banner::where('status', 1)->get();

        // Điều kiện chung: category và brand đều active
        $activeCategoryBrand = function ($query) {
            $query->where('is_active', 1);
        };

        // Sản phẩm mới nhất
        $newProducts = Product::whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->latest()
            ->take(24)
            ->get();

        // Sản phẩm được xem nhiều
        $topViewedProducts = Product::with('brand')
            ->whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Sản phẩm có giảm giá
        $discountedProducts = Product::whereNotNull('price_sale')
            ->where('price_sale', '>', 0)
            ->whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->latest()
            ->take(5)
            ->get();

        // Sản phẩm bán chạy (ở đây chưa có tiêu chí cụ thể, lấy dạng paginate để show nhiều)
        $topSellingProducts = Product::with('brand')
            ->whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->paginate(9);

        // Một số sản phẩm nổi bật
        $products = Product::with('category')
            ->whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->take(4)
            ->get();

        // Sản phẩm thuộc một số danh mục nổi bật
        $topCategories = ['Chuột', 'Bàn phím', 'Bộ bàn phím và Chuột'];
        $keyboardMouseProducts = Product::whereHas('category', function ($query) use ($topCategories) {
            $query->whereIn('name', $topCategories)->where('is_active', 1);
        })
            ->whereHas('brand', $activeCategoryBrand)
            ->latest()
            ->take(10)
            ->get();

        // Danh sách thương hiệu đang hoạt động
        $brands = Brand::where('is_active', 1)->get();
        $total_items = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->get()->sum(function ($item) {
            // If product_variant_id contains multiple variants (e.g. "4 | 5")
            if (strpos($item->product_variant_id, '|') !== false) {
                return count(explode('|', $item->product_variant_id));
            }
            return 1;
        });

        $recentPosts = Post::where('status', 1)->latest()->take(5)->get();

        $category_post = CategoryPost::all();

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
            'total_items',
            'recentPosts',
            'category_post'
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

    public function footer()
    {

        $topViewedProducts = Product::orderByDesc('views') // Sắp xếp theo lượt xem giảm dần
            ->take(10) // Lấy top 10 sản phẩm nhiều lượt xem nhất (có thể điều chỉnh)
            ->get()
            ->shuffle() // Trộn ngẫu nhiên
            ->take(3); // Lấy 3 sản phẩm ngẫu nhiên trong số đó


        $activeProducts = Product::where('status', '1') // hoặc status = 1 tuỳ bạn định nghĩa

            ->take(10) // Lấy top 10 sản phẩm nhiều lượt xem nhất (có thể điều chỉnh)
            ->get()
            ->shuffle() // Trộn ngẫu nhiên
            ->take(3); // Lấy 3 sản phẩm ngẫu nhiên trong số đó
        $template = 'fontend.component.footer';

        return view('fontend.layout', compact(
            'template',
            'topViewedProducts',
            'activeProducts'
        ));
    }
}
