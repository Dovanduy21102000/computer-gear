<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumImageController extends Controller
{   //
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

    //
    public function create($product_id)
    {
        // Lấy thông tin sản phẩm
        $product = Product::findOrFail($product_id);

        // Đặt template cho view
        $template = 'backend.album.create';
        return view('backend.dashboard.layout', compact('product', 'template'));
    }
    //
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Lưu ảnh vào thư mục 'public/storage/album_images'
        foreach ($request->file('images') as $image) {
            // Lưu từng ảnh vào thư mục storage và lưu đường dẫn vào cơ sở dữ liệu
            $path = $image->store('public/album_images');

            // Lưu vào cơ sở dữ liệu
            AlbumImage::create([
                'product_id' => $product_id,
                'image' => $path,
            ]);
        }

        return redirect()->route('backend.album.index', ['product_id' => $product_id])->with('success', 'Thêm ảnh thành công!');
    }

    //
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
