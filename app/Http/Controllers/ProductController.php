<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])->paginate(10);

        $template = 'backend.products.index';
        return view('backend.dashboard.layout', compact('products', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $template = 'backend.products.create';
        // Lấy danh sách danh mục và thương hiệu
        $categories = Category::all();
        $brands = Brand::all();
        return view('backend.dashboard.layout', compact('template','categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        } else {
            $thumbnailPath = null;
        }

        // Tạo sản phẩm mới
        Product::create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'slug' => $request->slug,
            'thumbnail' => $thumbnailPath,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'is_variant' => $request->is_variant,
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Lấy thông tin sản phẩm theo ID
        $product = Product::with(['category', 'brand'])->findOrFail($id);

        // Truyền dữ liệu sang view
        $template = 'backend.products.show';
        return view('backend.dashboard.layout', compact('product', 'template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = 'backend.products.edit';
        // Lấy thông tin sản phẩm cần chỉnh sửa
        $product = Product::findOrFail($id);

        // Lấy danh sách danh mục và thương hiệu
        $categories = Category::all();
        $brands = Brand::all();

        // Truyền dữ liệu sang view
        return view('backend.dashboard.layout', compact('template', 'categories', 'brands', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Tìm sản phẩm cần cập nhật
        $product = Product::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
        ]);

        // Xử lý upload ảnh mới (nếu có)
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ (nếu có)
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            // Lưu ảnh mới
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
            $product->thumbnail = $thumbnailPath;
        }

        // Cập nhật thông tin sản phẩm
        $product->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'is_variant' => $request->is_variant,
        ]);

        // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Tìm sản phẩm cần xóa
        $product = Product::findOrFail($id);

        // Xóa ảnh đại diện (nếu có)
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Xóa sản phẩm
        $product->delete();

        // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}
