<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AlbumImage;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Product; // Import model Product
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $query = Product::where('status', true);
        $category = null;
        // Nếu có truyền category dạng slug (?category=printer)
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)
                ->where('is_active', 1)
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                // Không tìm thấy category theo slug → trả về danh sách rỗng
                $query->whereRaw('0 = 1');
            }
        }

        // Nếu truyền dạng category_id (?category_id=5)
        elseif ($request->filled('category_id')) {
            $category = Category::where('is_active', 1)->find($request->category_id);

            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                // Không tìm thấy category theo ID → trả về danh sách rỗng
                $query->whereRaw('0 = 1');
            }
        }

        // Lọc theo thương hiệu nếu có brand[]
        if ($request->filled('brand')) {
            $brandsFilter = is_array($request->brand)
                ? $request->brand
                : explode(',', $request->brand);

            $brandsFilter = array_filter(array_map('intval', $brandsFilter));

            if (!empty($brandsFilter)) {
                $query->whereIn('brand_id', $brandsFilter);
            }
        }

        $products = $query->paginate(20);

        // Danh mục cấp cha
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Lọc danh sách brand có sản phẩm trong category (nếu có)
        $brandsQuery = Brand::where('is_active', 1);
        if ($category) {
            $brandsQuery->whereHas('products', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            });
        }
        $brands = $brandsQuery->get();
        $newProduct = $this->getNewProduct();

        $template = 'fontend.products.index';


        return view('fontend.layout', compact('template', 'products', 'categories', 'brands', 'category', 'newProduct'));
    }

    public function getNewProduct()
    {
        $activeCategoryBrand = function ($query) {
            $query->where('is_active', 1);
        };

        // Lấy sản phẩm mới nhất
        return Product::whereHas('category', $activeCategoryBrand)
            ->whereHas('brand', $activeCategoryBrand)
            ->where('status', true)
            ->orderByDesc('created_at')
            ->inRandomOrder()
            ->take(5)
            ->get();
    }




    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->increment('views');

        // Lấy thông tin biến thể + thuộc tính
        $variants = ProductVariant::where('product_id', $product->id)
            ->with(['attributeValues.attribute'])
            ->get();

        $productImage = ProductImage::where('product_id', $product->id)->first();
        $images = $productImage ? $productImage->images : [];


        // Lấy các sản phẩm liên quan
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();
        $comments = Comment::where('product_id', $product->id)
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        $totalReviews = $comments->count();

        // Tính điểm đánh giá trung bình
        $averageRating = $totalReviews > 0 ? round($comments->avg('rating'), 1) : 0;

        $comment = $product->comments()->where('user_id', auth()->id())->first();
        // Lấy số lượng đánh giá theo từng mức sao
        $ratingsCount = Comment::where('product_id', $product->id)
            ->where('status', 1)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = collect(); // Trả về danh sách rỗng thay vì null
        }

        $template = 'fontend.products.detail';
        return view('fontend.layout', compact('template', 'product', 'variants', 'relatedProducts', 'images', 'comments', 'totalReviews', 'averageRating', 'ratingsCount', 'comment'));
    }

    public function getVariant(Request $request)
    {
        // Log để kiểm tra request
        Log::info('Received request:', $request->all());

        // Truy vấn biến thể sản phẩm dựa trên product_id
        $query = ProductVariant::where('product_id', $request->product_id);

        // Duyệt qua tất cả các tham số của request (trừ product_id) và thêm điều kiện vào truy vấn
        foreach ($request->except('product_id') as $key => $value) {
            if (!empty($value)) {
                Log::info('Áp dụng điều kiện: ' . $key . ' = ' . $value);  // Log điều kiện tìm kiếm
                $query->whereHas('attributeValues', function ($query) use ($key, $value) {
                    $query->where('value', $value)
                        ->whereHas('attribute', function ($subQuery) use ($key) {
                            // Điều kiện tìm kiếm linh hoạt cho tên thuộc tính
                            $subQuery->where('name', 'like', '%' . $key . '%');
                        });
                });
            }
        }

        // Trả về biến thể đầu tiên thỏa mãn điều kiện
        $variant = $query->first();

        // Log kết quả
        Log::info('Kết quả truy vấn biến thể:', ['variant' => $variant]);

        // Nếu không tìm thấy biến thể
        if (!$variant) {
            Log::error('Không tìm thấy biến thể cho sản phẩm:', ['request' => $request->all()]);
            return response()->json(['error' => 'Không tìm thấy biến thể'], 404);
        }

        // Trả về thông tin biến thể
        return response()->json([
            'price' => number_format($variant->price, 0, ',', '.') . '₫',
            'price_sale' => $variant->price_sale ? number_format($variant->price_sale, 0, ',', '.') . '₫' : null,
            'quantity' => $variant->quantity ?? 0,
        ]);
    }
    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }

        return $ids;
    }

    public function categoryProducts($slug)
    {
        $category = Category::with('children')->where('slug', $slug)->firstOrFail();

        // Lấy tất cả ID của danh mục cha + con
        $categoryIds = $this->getAllCategoryIds($category);

        // Truy vấn sản phẩm theo nhiều category_id
        $productsQuery = Product::whereIn('category_id', $categoryIds)
            ->where('status', true);

        // Lọc theo thương hiệu nếu có
        if ($brandIds = request('brand')) {
            $productsQuery->whereIn('brand_id', $brandIds);
        }

        $products = $productsQuery->paginate(20);

        // Dữ liệu khác
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::with(['children.products', 'products'])
            ->whereNull('parent_id')
            ->get();

        foreach ($categories as $category) {
            $category->total_products = $category->products->count()
                + $category->children->sum(fn($child) => $child->products->count());

            foreach ($category->children as $child) {
                $child->total_products = $child->products->count();
            }
        }
        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'category', 'categories', 'brands'));
    }


    public function showByBrand($brandSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->where('status', true)
            ->paginate(20);

        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();

        $brands = Brand::all();

        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'categories', 'brand', 'brands'));
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

    public function filteredProducts(Request $request)
    {
        $productsQuery = Product::query()->where('status', true);

        // Lấy danh mục theo slug nếu có
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $productsQuery->where('category_id', $category->id);
        }

        // Lọc theo thương hiệu nếu có
        if ($request->has('brand')) {
            $brandIds = (array) $request->brand;
            $productsQuery->whereIn('brand_id', $brandIds);
        }



        $products = $productsQuery->paginate(20);


        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $brands = Brand::where('is_active', 1)->get();

        $template = 'fontend.products.index';

        return view('fontend.layout', compact('template', 'products', 'categories', 'brands'));
    }
}
