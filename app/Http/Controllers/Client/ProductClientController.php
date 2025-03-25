<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product; // Import model Product
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        // dd($request->all()); // Kiểm tra dữ liệu request
        $query = Product::where('status', true);
        $category = null;
    
        // Lọc theo danh mục nếu có category_id
        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
    
        // Lọc theo thương hiệu nếu có brand[]
        if ($request->filled('brand')) {
            $brandsFilter = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereIn('brand_id', $brandsFilter);
        }
    
        $products = $query->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $brands = Brand::all();
        $template = 'fontend.products.index';
    
        return view('fontend.layout', compact('template', 'products', 'categories', 'brands', 'category'));
    }
    








    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem lên 1
        $product->increment('views');

        // Lấy sản phẩm cùng danh mục, loại trừ sản phẩm đang xem
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6) // Giới hạn số sản phẩm hiển thị
            ->get();

        $template = 'fontend.products.detail';

        return view('fontend.layout', compact('template', 'product', 'relatedProducts'));
    }



    public function categoryProducts($slug)
    {
        // Lấy danh mục theo slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Lấy danh sách sản phẩm thuộc danh mục này
        $products = Product::where('category_id', $category->id)
            ->where('status', true)
            ->get();

        // Lấy danh sách danh mục cha
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

        // Lấy danh sách thương hiệu (CẦN DÒNG NÀY ĐỂ TRÁNH LỖI Undefined variable $brands)
        $brands = \App\Models\Brand::all();

        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'categories', 'category', 'brands'));
    }
}
