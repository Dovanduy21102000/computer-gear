<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumImageController extends Controller
{
    public function index($product_id)
    {
        // Lấy thông tin sản phẩm
        $product = Product::findOrFail($product_id);
    
        // Lấy tất cả ảnh từ album của sản phẩm này
        $images = AlbumImage::where('product_id', $product_id)->get();
    
        // Đặt template cho view
        $template = 'backend.album.index';
        return view('backend.dashboard.layout', compact('images', 'product', 'product_id', 'template'));
    }
    

    public function create($product_id)
    {
        // Lấy thông tin sản phẩm
        $product = Product::findOrFail($product_id);

        // Đặt template cho view
        $template = 'backend.album.create';
        return view('backend.dashboard.layout', compact('product', 'template'));
    }

    public function store(Request $request, $product_id)
    {
        // Validate và lưu ảnh vào database
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Lưu ảnh vào thư mục public/images
        $imagePath = $request->file('image')->store('public/images');

        // Lưu thông tin ảnh vào bảng album_images
        AlbumImage::create([
            'product_id' => $product_id, // Thêm product_id vào album_image
            'image' => $imagePath,
        ]);

        return redirect()->route('backend.album.index', ['product_id' => $product_id])
                         ->with('success', 'Image added successfully');
    }

    public function edit($id)
    {
        $image = AlbumImage::findOrFail($id);
        $template = 'backend.album.edit';
        return view('backend.dashboard.layout', compact('image', 'template'));
    }

    public function update(Request $request, $id)
    {
        $image = AlbumImage::findOrFail($id);

        // Validate ảnh mới
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Kiểm tra xem có ảnh mới không
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ khỏi thư mục lưu trữ
            Storage::delete($image->image);

            // Lưu ảnh mới vào thư mục public/images
            $imagePath = $request->file('image')->store('public/images');

            // Cập nhật ảnh mới vào database
            $image->update([
                'image' => $imagePath,
            ]);
        }

        // Chuyển hướng lại đến trang album của sản phẩm
        return redirect()->route('backend.album.index', ['product_id' => $image->product_id])
                         ->with('success', 'Ảnh đã được cập nhật!');
    }

    public function destroy($id)
    {
        // Tìm và xóa ảnh
        $image = AlbumImage::findOrFail($id);
    
        // Lưu lại product_id để sử dụng sau khi xóa ảnh
        $product_id = $image->product_id;
    
        // Kiểm tra và xóa ảnh khỏi thư mục lưu trữ
        if (Storage::exists($image->image)) {
            Storage::delete($image->image);
        }
    
        // Xóa ảnh khỏi database
        $image->delete();
    
        // Quay lại trang album của sản phẩm đó
        return redirect()->route('backend.album.index', ['product_id' => $product_id])
                         ->with('success', 'Xóa thành công');
    }
    
}
