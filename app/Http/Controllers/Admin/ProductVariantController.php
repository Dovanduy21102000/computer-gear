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
        $attributes = \App\Models\Attribute::with('attributeValues')->get();
        // Generate a suggested SKU
        $baseSku = $product->sku ?? 'SKU';
        $variantCount = $product->variants()->count() + 1;
        $suggestedSku = $baseSku . '-' . $variantCount;
        return view('backend.dashboard.layout', compact('product', 'template', 'attributes', 'suggestedSku'));
    }

    /**
     * Lưu biến thể mới.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'required|array',
        ]);

        // Log::info('Attributes from request:', ['attributes' => $request->input('attributes')]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        $variant = $product->variants()->create([
            'sku' => $request->sku,
            'name' => $request->name,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'quantity' => $request->quantity,
            'image' => $imagePath,
            'attributes' => json_encode($request->input('attributes')),
        ]);

        // Sync attribute values
        $inputAttributes = $request->input('attributes', []);
        if (!empty($inputAttributes)) {
            $attributeValueIds = [];
            foreach ($inputAttributes as $attributeId => $attributeValueId) {
                $attributeValueIds[] = $attributeValueId;
            }
            $variant->attributeValues()->sync($attributeValueIds);
        } else {
            $variant->attributeValues()->detach();
        }

        return redirect()->route('variants.index', ['product' => $product->id])
            ->with('success', 'Biến thể đã được thêm thành công.');
    }

    // public function show($productId, $variantId)
    // {
    //     $template = 'backend.variants.show';
    //     $variant = ProductVariant::where('id', $variantId)
    //         ->where('product_id', $productId)
    //         ->firstOrFail();
    //     $product = Product::findOrFail($productId);

    //     return view('backend.dashboard.layout', compact('variant', 'template','product'));
    // }
    public function show($productId, $variantId)
    {
        $template = 'backend.variants.show';

        $variant = ProductVariant::with(['product', 'attributes'])
            ->where('id', $variantId)
            ->where('product_id', $productId)
            ->firstOrFail();

        return view('backend.dashboard.layout', compact('variant', 'template'));
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        $attributes = \App\Models\Attribute::with('attributeValues')->get();
        return view('backend.dashboard.layout', [
            'product' => $product,
            'variant' => $variant,
            'attributes' => $attributes,
            'template' => 'backend.variants.edit'
        ]);
    }

    // public function update(Request $request, Product $product, ProductVariant $variant)
    // {
    //     $request->validate([
    //         'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'price_sale' => 'nullable|numeric',
    //         'quantity' => 'required|integer',
    //         'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'attributes' => 'required|array',
    //     ]);

    //     if ($request->hasFile('thumbnail')) {
    //         if ($variant->thumbnail) {
    //             Storage::disk('public')->delete($variant->thumbnail);
    //         }
    //         $thumbnailPath = $request->file('thumbnail')->store('variants', 'public');
    //     } else {
    //         $thumbnailPath = $variant->thumbnail;
    //     }

    //     $variant->update([
    //         'sku' => $request->sku,
    //         'name' => $request->name,
    //         'price' => $request->price,
    //         'price_sale' => $request->price_sale,
    //         'quantity' => $request->quantity,
    //         'thumbnail' => $thumbnailPath,
    //         'attributes' => json_encode($request->attributes),
    //     ]);

    //     return redirect()->route('variants.index', ['product' => $product->id])
    //         ->with('success', 'Biến thể đã được cập nhật.');
    // }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $variant->image;
        }

        $variant->update([
            'sku' => $request->sku,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'quantity' => $request->quantity,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        // Sync attribute values
        $inputAttributes = $request->input('attributes', []);
        if (!empty($inputAttributes)) {
            $attributeValueIds = [];
            foreach ($inputAttributes as $attributeId => $attributeValueId) {
                $attributeValueIds[] = $attributeValueId;
            }
            $variant->attributeValues()->sync($attributeValueIds);
        } else {
            $variant->attributeValues()->detach();
        }

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
