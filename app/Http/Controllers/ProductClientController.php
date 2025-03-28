<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product; // Import model Product
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $query = Product::where('status', true);
        $category = null;

        // Lọc theo danh mục nếu có category_id
        if ($request->filled('category_id')) {
            $category = Category::where('is_active', 1)->find($request->category_id);
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Lọc theo thương hiệu nếu có brand[]
        if ($request->filled('brand')) {
            $brandsFilter = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $brandsFilter = array_filter(array_map('intval', $brandsFilter)); // Chỉ lấy số hợp lệ

            if (!empty($brandsFilter)) {
                $query->whereIn('brand_id', $brandsFilter);
            }
        }

        // Áp dụng bộ lọc vào danh sách sản phẩm
        $products = $query->paginate(20);

        // Lấy danh sách danh mục cha (chỉ danh mục đang hoạt động)
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

        $brandsQuery = Brand::where('is_active', 1);

        if ($category) {
            $brandsQuery->whereHas('products', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            });
        }

        $brands = $brandsQuery->get();

        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'categories', 'brands', 'category'));
    }


    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->increment('views');

        // Lấy thông tin biến thể + thuộc tính
        $variants = ProductVariant::where('product_id', $product->id)
            ->with(['attributeValues.attribute'])
            ->get();

        // Lấy danh sách ảnh của sản phẩm
        $images = $product->images;

        // Lấy các sản phẩm liên quan
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = collect(); // Trả về danh sách rỗng thay vì null
        }

        $template = 'fontend.products.detail';
        return view('fontend.layout', compact('template', 'product', 'variants', 'relatedProducts', 'images'));
    }


    public function getVariant(Request $request)
    {
        $variant = ProductVariant::where('product_id', $request->product_id)
            ->whereHas('attributeValues', function ($query) use ($request) {
                $query->whereHas('attribute', function ($subQuery) {
                    $subQuery->where('name', 'RAM');
                })->where('value', $request->ram);
            })
            ->whereHas('attributeValues', function ($query) use ($request) {
                $query->whereHas('attribute', function ($subQuery) {
                    $subQuery->where('name', 'Màu sắc');
                })->where('value', $request->color);
            })
            ->first();

        if (!$variant) {
            return response()->json(['error' => '❌ Không tìm thấy biến thể'], 404);
        }

        return response()->json([
            'price' => number_format($variant->price, 0, ',', '.') . '₫',
            'price_sale' => $variant->price_sale
                ? number_format($variant->price_sale, 0, ',', '.') . '₫'
                : null,
            'quantity' => $variant->quantity ?? 0 // Lấy giá trị từ quantity thay vì stock
        ]);
    }








    public function categoryProducts($slug)
    {
        // Lấy danh mục theo slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Lấy danh sách sản phẩm thuộc danh mục này
        $products = Product::where('category_id', $category->id)
            ->where('status', true)
            ->paginate(20);

        // Lấy danh sách danh mục cha
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

        // Lấy danh sách thương hiệu (CẦN DÒNG NÀY ĐỂ TRÁNH LỖI Undefined variable $brands)
        $brands = Brand::where('is_active', 1)->get();

        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'categories', 'category', 'brands'));
    }





    public function search(Request $request)
    {
        $query = $request->input('query');

        // Nếu không có từ khóa tìm kiếm, quay về trang trước
        if (!$query) {
            return redirect()->back()->with('error', 'Vui lòng nhập từ khóa tìm kiếm!');
        }

        // Lấy danh sách sản phẩm theo từ khóa
        $products = Product::where('name', 'LIKE', "%{$query}%")->paginate(10);

        // Lấy danh sách danh mục cha
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Lấy danh sách thương hiệu
        $brands = Brand::where('is_active', 1)->get();

        // Trả về trang kết quả tìm kiếm
        $template = 'fontend.products.search-results';

        return view('fontend.layout', compact('template', 'products', 'categories', 'query', 'brands'));
    }
}
