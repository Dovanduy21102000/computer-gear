<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute as ModelsAttribute;
use App\Models\Product;
use App\Models\ProductVariant;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Events\VariantUpdated;

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
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'required|array',
        ], [
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'image.image' => 'Ảnh phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'attributes.required' => 'Vui lòng chọn thuộc tính.',
            'attributes.array' => 'Thuộc tính không hợp lệ.',
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
        event(new VariantUpdated($variant));

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

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        // 1. Validate các trường cơ bản và mảng attributes (nếu có gửi lên)
        $validated = $request->validate([
            'sku'               => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
            'price'             => 'required|numeric',
            'price_sale'        => 'nullable|numeric',
            'quantity'          => 'required|integer',
            'status'            => 'required|integer',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Nếu gửi thông tin thuộc tính thì bắt buộc phải có key và value
            'attributes'        => 'nullable|array',
            'attributes.*.key'   => 'required_with:attributes',
            'attributes.*.value' => 'required_with:attributes',
        ]);

        // 2. Nếu có dữ liệu thuộc tính (người dùng chỉnh sửa thuộc tính)
        if (isset($validated['attributes'])) {
            $attributesInput = $validated['attributes'];

            // Kiểm tra tính duy nhất của mỗi attribute key (không cho chọn cùng một thuộc tính nhiều lần)
            $attributeKeys = array_column($attributesInput, 'key');
            if (count($attributeKeys) !== count(array_unique($attributeKeys))) {
                return redirect()->back()->withInput()
                    ->withErrors([
                        'attributes' => 'Bạn không thể chọn cùng một thuộc tính nhiều hơn một lần.'
                    ]);
            }
        }

        // 3. Xử lý upload ảnh (nếu có)
        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $imagePath = $request->file('image')->store('variants', 'public');
        } else {
            $imagePath = $variant->image;
        }

        // 4. Cập nhật các trường cơ bản của biến thể
        $variant->update([
            'sku'        => $validated['sku'],
            'price'      => $validated['price'],
            'price_sale' => $validated['price_sale'] ?? null,
            'quantity'   => $validated['quantity'],
            'status'     => $validated['status'],
            'image'      => $imagePath,
        ]);

        // 5. Nếu có attribute được gửi lên, cập nhật lại quan hệ cho biến thể
        if (isset($validated['attributes'])) {
            $attributeValueIds = collect($validated['attributes'])
                                    ->map(function ($attr) {
                                        return $attr['value'];
                                    })
                                    ->toArray();

            // Dùng sync() để đồng bộ lại các attribute value trong bảng pivot
            $variant->attributeValues()->sync($attributeValueIds);
        }

        return redirect()->route('variants.index', ['product' => $product->id])
                         ->with('success', 'Biến thể đã được cập nhật.');
    }




    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }
        $variant->delete();

        return redirect()->route('variants.index', ['product' => $product->id])
            ->with('success', 'Biến thể đã được xóa.');
    }
    /**
     * Lưu biến thể mới.
     */
    // public function store(Request $request, Product $product)
    // {
    //     $request->validate([
    //         'sku' => 'required|string|max:255|unique:product_variants',
    //         'price' => 'required|numeric',
    //         'price_sale' => 'nullable|numeric',
    //         'quantity' => 'required|integer',
    //         'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'attributes' => 'required|array',
    //     ]);

    //     $thumbnailPath = $request->hasFile('image')
    //         ? $request->file('thumbnail')->store('variants', 'public')
    //         : null;

    //     $product->variants()->create([
    //         'sku' => $request->sku,
    //         'name' => $request->name,
    //         'price' => $request->price,
    //         'price_sale' => $request->price_sale,
    //         'quantity' => $request->quantity,
    //         'thumbnail' => $thumbnailPath,
    //         'attributes' => json_encode($request->attributes),
    //     ]);

    //     return redirect()->route('variants.index', ['product' => $product->id])
    //         ->with('success', 'Biến thể đã được thêm thành công.');
    // }
    // public function show($productId, $variantId)
    // {
    //     $template = 'backend.variants.show';
    //     $variant = ProductVariant::where('id', $variantId)
    //         ->where('product_id', $productId)
    //         ->firstOrFail();
    //     $product = Product::findOrFail($productId);

    //     return view('backend.dashboard.layout', compact('variant', 'template','product'));
    // }
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
        ], [
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'image.image' => 'Ảnh phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
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
        event(new VariantUpdated($variant));

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
