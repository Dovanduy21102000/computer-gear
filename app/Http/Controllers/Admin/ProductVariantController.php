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
        $attributes = ModelsAttribute::with('attributeValues')->get();

        return view('backend.dashboard.layout', compact('product', 'template', 'attributes'));
    }
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku'               => 'required|string|max:255|unique:product_variants,sku',
            'price'             => 'required|numeric',
            'price_sale'        => 'nullable|numeric',
            'quantity'          => 'required|integer',
            'thumbnail'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes'        => 'required|array',
            'attributes.*.key'   => 'required',
            'attributes.*.value' => 'required',
        ]);

        // 2. Kiểm tra số lượng thuộc tính gửi lên (nếu sản phẩm yêu cầu số lượng cụ thể)
        $requiredAttributesCount = 2;  // Ví dụ: sản phẩm máy tính cần 2 thuộc tính (có thể là Card màn hình và Màu sắc)
        $attributesInput = $validated['attributes'];
        if (count($attributesInput) != $requiredAttributesCount) {
            return redirect()->back()->withInput()
                ->withErrors([
                    'attributes' => "Bạn phải chọn đầy đủ {$requiredAttributesCount} thuộc tính."
                ]);
        }

        // 3. Kiểm tra tính duy nhất của mỗi thuộc tính (không cho phép duplicate attribute key)
        $attributeKeys = array_column($attributesInput, 'key');
        if (count($attributeKeys) !== count(array_unique($attributeKeys))) {
            return redirect()->back()->withInput()
                ->withErrors([
                    'attributes' => 'Bạn không thể chọn cùng một thuộc tính nhiều hơn một lần.'
                ]);
        }

        // 4. Chuyển đổi dữ liệu thuộc tính thành mảng chứa các attribute value ID
        $submittedAttrValueIds = collect($attributesInput)
                                    ->map(function ($attr) {
                                        return $attr['value'];
                                    })
                                    ->sort()
                                    ->values()
                                    ->all();

        // 5. Kiểm tra xem có biến thể nào của sản phẩm đã có tập hợp attribute đó chưa
        $existingVariant = $product->variants->first(function ($variant) use ($submittedAttrValueIds) {
            $existingAttrValueIds = $variant->attributeValues->pluck('id')
                                            ->sort()
                                            ->values()
                                            ->all();
            return $existingAttrValueIds == $submittedAttrValueIds;
        });

        if ($existingVariant) {
            return redirect()->back()->withInput()
                ->withErrors(["attributes" => "Biến thể với tập hợp thuộc tính này đã tồn tại."]);
        }

        // 6. Xử lý upload ảnh
        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('variants', 'public')
            : null;

        // 7. Tạo biến thể mới
        $variant = $product->variants()->create([
            'sku'        => $validated['sku'],
            'price'      => $validated['price'],
            'price_sale' => $validated['price_sale'] ?? null,
            'quantity'   => $validated['quantity'],
            'image'      => $thumbnailPath,
            'status'     => 1,
        ]);

        // 8. Attach các attribute value cho biến thể mới
        $attributeValues = collect($attributesInput)
                                ->map(function ($attr) {
                                    return $attr['value'];
                                })
                                ->toArray();
        $variant->attributeValues()->attach($attributeValues);

        return redirect()->route('variants.index', ['product' => $product->id])
                        ->with('success', 'Biến thể đã được thêm thành công.');
    }


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
        $attributes = ModelsAttribute::with('attributeValues')->get();

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
        //     public function update(Request $request, Product $product, ProductVariant $variant)
    // {
    //     $request->validate([
    //         'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
    //         'price' => 'required|numeric',
    //         'price_sale' => 'nullable|numeric',
    //         'quantity' => 'required|integer',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    
        
    //     if ($request->hasFile('image')) {
    //         if ($variant->image) {
    //             Storage::disk('public')->delete($variant->image);
    //         }
    //         $imagePath = $request->file('image')->store('variants', 'public');
    //     } else {
    //         $imagePath = $variant->image; 
    //     }
    
    //     $variant->update([
    //         'sku' => $request->sku,
    //         'price' => $request->price,
    //         'price_sale' => $request->price_sale,
    //         'quantity' => $request->quantity,
    //         'image' => $imagePath, 
    //         'status' => $request->status,
    //     ]);
    
    //     return redirect()->route('variants.index', ['product' => $product->id])
    //         ->with('success', 'Biến thể đã được cập nhật.');
    // }
}
