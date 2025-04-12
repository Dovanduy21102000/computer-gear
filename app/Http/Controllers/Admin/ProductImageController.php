<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index($product_id)
    {
        $product = Product::findOrFail($product_id);
        $productImage = ProductImage::where('product_id', $product_id)->first();
        $images = $productImage ? $productImage->images : [];

        $template = 'backend.product_image.index';
        return view('backend.dashboard.layout', compact('product', 'images', 'template'));
    }

    public function create($product_id)
    {
        $product = Product::findOrFail($product_id);
        $template = 'backend.product_image.create';
        return view('backend.dashboard.layout', compact('product', 'template'));
    }

    public function store(Request $request, $product_id)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $storedPaths = [];
        foreach ($request->file('images') as $img) {
            $storedPaths[] = $img->store('album_images', 'public');
        }

        $productImage = ProductImage::firstOrNew(['product_id' => $product_id]);
        $productImage->images = array_merge($productImage->images ?? [], $storedPaths);
        $productImage->save();

        return redirect()->route('backend.product_images.index', ['product_id' => $product_id])
            ->with('success', 'Thêm ảnh thành công!');
    }

    public function destroy($product_id, $key)
    {
        $productImage = ProductImage::where('product_id', $product_id)->firstOrFail();
        $images = $productImage->images ?? [];
    
        // Kiểm tra nếu không tìm thấy ảnh tại chỉ mục key
        if (!isset($images[$key])) {
            return redirect()->back()->with('error', 'Không tìm thấy ảnh!');
        }
    
        // Kiểm tra file có tồn tại trên server hay không trước khi xóa
        if (Storage::disk('public')->exists($images[$key])) {
            Storage::disk('public')->delete($images[$key]);
        }
    
        // Xóa ảnh khỏi mảng
        unset($images[$key]);
        $productImage->images = array_values($images); // Reset lại các chỉ mục mảng
        $productImage->save();
    
        return redirect()->route('backend.product_images.index', ['product_id' => $product_id])
            ->with('success', 'Xóa ảnh thành công!');
    }
    
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);
        $productImage = ProductImage::where('product_id', $product_id)->first();
    
        // Kiểm tra nếu album ảnh không tồn tại, tạo mới
        if (!$productImage) {
            $productImage = new ProductImage();
            $productImage->product_id = $product_id;
            $productImage->images = []; // Khởi tạo mảng ảnh rỗng
            $productImage->save();
        }
    
        $images = $productImage->images ?? [];
    
        $template = 'backend.product_image.edit';
        return view('backend.dashboard.layout', compact('product', 'images', 'template'));
    }
    
    

    public function update(Request $request, $product_id)
    {
        $productImage = ProductImage::where('product_id', $product_id)->firstOrFail();
        $images = $productImage->images ?? [];
    
        // Xử lý xóa ảnh
        $deletedIndexes = json_decode($request->input('deleted_images'), true);
        if (is_array($deletedIndexes)) {
            foreach ($deletedIndexes as $index) {
                if (isset($images[$index])) {
                    // Kiểm tra nếu file tồn tại trước khi xóa
                    if (Storage::disk('public')->exists($images[$index])) {
                        Storage::disk('public')->delete($images[$index]);
                    }
                    unset($images[$index]); // Xóa ảnh khỏi mảng
                }
            }
        }
    
        // Cập nhật ảnh hiện có
        if ($request->hasFile('updated_images')) {
            foreach ($request->file('updated_images') as $index => $file) {
                if ($file && isset($images[$index])) {
                    // Kiểm tra nếu file tồn tại trước khi xóa
                    if (Storage::disk('public')->exists($images[$index])) {
                        Storage::disk('public')->delete($images[$index]);
                    }
                    $images[$index] = $file->store('album_images', 'public');
                }
            }
        }
    
        // Thêm ảnh mới
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $images[] = $file->store('album_images', 'public');
            }
        }
    
        // Cập nhật lại dữ liệu ảnh vào database
        $productImage->images = array_values($images); // Reset lại các chỉ mục mảng
        $productImage->save();
    
        return redirect()->route('backend.product_images.index', ['product_id' => $product_id])
            ->with('success', 'Cập nhật album thành công!');
    }
    
}
