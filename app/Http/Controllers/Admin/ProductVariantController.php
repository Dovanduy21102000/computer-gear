<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants;
        return view('backend.dashboard.layout', [
            'variants' => $variants,
            'product' => $product,
            'template' => 'backend.variants.index'
        ]);
    }


    /**
     * Hiển thị form tạo biến thể.
     */
    public function create($productId)
    {
        $template = 'backend.variants.add';
        $product = Product::findOrFail($productId);
       
        return view('backend.dashboard.layout', compact('product', 'template'));
    }

    /**
     * Lưu biến thể mới.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'required|array',
        ]);

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('variants', 'public')
            : null;

        $product->variants()->create([
            'sku' => $request->sku,
            'name' => $request->name,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'quantity' => $request->quantity,
            'thumbnail' => $thumbnailPath,
            'attributes' => json_encode($request->attributes),
        ]);

        return redirect()->route('variants.index', ['product' => $product->id])
            ->with('success', 'Biến thể đã được thêm thành công.');
    }


    public function edit(Product $product, ProductVariant $variant)
    {
        return view('backend.dashboard.layout', [
            'product' => $product,
            'variant' => $variant,
            'template' => 'backend.variants.edit'
        ]);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
{
    $request->validate([
        'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'price_sale' => 'nullable|numeric',
        'quantity' => 'required|integer',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'attributes' => 'required|array',
    ]);

    if ($request->hasFile('thumbnail')) {
        if ($variant->thumbnail) {
            Storage::disk('public')->delete($variant->thumbnail);
        }
        $thumbnailPath = $request->file('thumbnail')->store('variants', 'public');
    } else {
        $thumbnailPath = $variant->thumbnail;
    }

    $variant->update([
        'sku' => $request->sku,
        'name' => $request->name,
        'price' => $request->price,
        'price_sale' => $request->price_sale,
        'quantity' => $request->quantity,
        'thumbnail' => $thumbnailPath,
        'attributes' => json_encode($request->attributes),
    ]);

    return redirect()->route('variants.index', ['product' => $product->id])
                     ->with('success', 'Biến thể đã được cập nhật.');
}


    public function destroy(Product $product, ProductVariant $variant)
{
    if ($variant->thumbnail) {
        Storage::disk('public')->delete($variant->thumbnail);
    }
    $variant->delete();

    return redirect()->route('variants.index', ['product' => $product->id])
                     ->with('success', 'Biến thể đã được xóa.');
}

}
