<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Banner; // Import model Banner

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $banners = Banner::where('status', 1)->get();
        $products = Product::orderBy('created_at', 'desc')->take(24)->get();
        $topViewedProducts = Product::with('brand')->orderBy('views', 'desc')->take(5)->get();
        $discountedProducts = Product::whereNotNull('price_sale') // Lọc sản phẩm có giá giảm
            ->where('price_sale', '>', 0) // Đảm bảo giá giảm lớn hơn 0
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo mới nhất
            ->take(5) // Lấy 5 sản phẩm
            ->get();
        $topSellingProducts = Product::with('brand')->orderBy('quantity_sold', 'desc') // Sắp xếp theo lượt bán từ cao đến thấp
            ->paginate(9);
        $template = 'fontend.home.index';
        return view('fontend.layout', compact('template', 'banners', 'products', 'topViewedProducts', 'discountedProducts', 'topSellingProducts'));
    }
}
