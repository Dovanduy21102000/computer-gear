<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariantAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Helpers\SlugHelper;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Product::with(['category', 'brand', 'variants.attributeValues.attribute'])->latest('id');


        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        $categories = Category::all();
        $brands = Brand::all();

        $products = $query->latest()->paginate(10);

        $template = 'backend.products.index';
        return view('backend.dashboard.layout', compact('products', 'categories', 'brands', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $template = 'backend.products.create';
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('attributevalues')->get();

        return view('backend.dashboard.layout', compact('template', 'categories', 'brands', 'attributes'));
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
            'slug' => 'nullable|string|max:255|unique:products',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => $request->is_variant ? 'nullable|numeric' : 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => $request->is_variant ? 'nullable|integer' : 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
            'variants' => $request->is_variant ? 'required|array' : 'nullable|array',
            'variants.*.sku' => $request->is_variant ? 'required|string|max:255' : 'nullable|string|max:255',
            'variants.*.price' => $request->is_variant ? 'required|numeric' : 'nullable|numeric',
            'variants.*.price_sale' => 'nullable|numeric',
            'variants.*.quantity' => $request->is_variant ? 'required|integer' : 'nullable|integer',
            'variants.*.attributes' => $request->is_variant ? 'required|array' : 'nullable|array',
            'variants.*.attributes.*' => 'exists:attribute_values,id',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá :max ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'thumbnail.image' => 'File tải lên phải là hình ảnh.',
            'thumbnail.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'thumbnail.max' => 'Ảnh không được vượt quá 2MB.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
            'is_variant.boolean' => 'Trường biến thể không hợp lệ.',
            'variants.required' => 'Vui lòng nhập thông tin biến thể.',
            'variants.*.sku.required' => 'Vui lòng nhập SKU cho biến thể.',
            'variants.*.price.required' => 'Vui lòng nhập giá cho biến thể.',
            'variants.*.quantity.required' => 'Vui lòng nhập số lượng cho biến thể.',
        ]);

        if (!$request->slug) {
            $request->merge(['slug' => SlugHelper::createSlug($request->name)]);
        }

        $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('products', 'public') : null;

        $totalQuantity = 0;
        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $variantData) {
                $totalQuantity += $variantData['quantity'];
            }
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'slug' => $request->slug,
            'thumbnail' => $thumbnailPath,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'price_sale' => $request->price_sale ?? null,
            'quantity' => $request->is_variant ? $totalQuantity : $request->quantity,
            'status' => $request->status,
            'is_variant' => $request->is_variant,
            'views' => 0
        ]);

        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $i => $variantData) {
                $imagePath = null;
                if ($request->hasFile("variants.$i.image")) {
                    $imagePath = $request->file("variants.$i.image")->store('variants', 'public');
                }
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'price_sale' => $variantData['price_sale'] ?? null,
                    'quantity' => $variantData['quantity'],
                    'image' => $imagePath,
                    'status' => true
                ]);

                if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attributeId => $attributeValueId) {
                        ProductVariantAttributeValue::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $attributeValueId,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'brand', 'variants.attributeValues.attribute'])->findOrFail($id);
        $albumImages = \App\Models\ProductImage::where('product_id', $id)->first()?->images ?: [];
        Log::debug('SHOW PRODUCT DATA', [
            'product' => $product->toArray(),
            'albumImages' => $albumImages
        ]);
        $template = 'backend.products.show';
        return view('backend.dashboard.layout', compact('product', 'template', 'albumImages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = 'backend.products.edit';
        $product = Product::with(['variants.attributeValues'])->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('attributeValues')->get();
        return view('backend.dashboard.layout', compact('template', 'categories', 'brands', 'product', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => $request->is_variant ? 'nullable|numeric' : 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => $request->is_variant ? 'nullable|integer' : 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
            'variants' => $request->is_variant ? 'required|array' : 'nullable|array',
            'variants.*.sku' => $request->is_variant ? 'required|string|max:255' : 'nullable|string|max:255',
            'variants.*.price' => $request->is_variant ? 'required|numeric' : 'nullable|numeric',
            'variants.*.price_sale' => 'nullable|numeric',
            'variants.*.quantity' => $request->is_variant ? 'required|integer' : 'nullable|integer',
            'variants.*.status' => 'boolean',
            'variants.*.attributes' => $request->is_variant ? 'required|array' : 'nullable|array',
            'variants.*.attributes.*' => 'exists:attribute_values,id',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá :max ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'thumbnail.image' => 'File tải lên phải là hình ảnh.',
            'thumbnail.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'thumbnail.max' => 'Ảnh không được vượt quá 2MB.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
            'is_variant.boolean' => 'Trường biến thể không hợp lệ.',
            'variants.required' => 'Vui lòng nhập thông tin biến thể.',
            'variants.*.sku.required' => 'Vui lòng nhập SKU cho biến thể.',
            'variants.*.price.required' => 'Vui lòng nhập giá cho biến thể.',
            'variants.*.quantity.required' => 'Vui lòng nhập số lượng cho biến thể.',
        ]);

        if (!$request->slug) {
            $request->merge(['slug' => SlugHelper::createSlug($request->name)]);
        }

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
            $product->thumbnail = $thumbnailPath;
        }

        $totalQuantity = 0;
        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $variantData) {
                $totalQuantity += $variantData['quantity'];
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'price_sale' => $request->price_sale ?? null,
            'quantity' => $request->is_variant ? $totalQuantity : $request->quantity,
            'status' => $request->status,
            'is_variant' => $request->is_variant,
        ]);

        $product->variants()->delete();

        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $i => $variantData) {
                $imagePath = null;
                if ($request->hasFile("variants.$i.image")) {
                    $imagePath = $request->file("variants.$i.image")->store('variants', 'public');
                }
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'price_sale' => $variantData['price_sale'] ?? null,
                    'quantity' => $variantData['quantity'],
                    'image' => $imagePath,
                    'status' => $variantData['status'] ?? true
                ]);

                if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attributeId => $attributeValueId) {
                        ProductVariantAttributeValue::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $attributeValueId,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}
