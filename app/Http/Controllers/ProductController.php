<?php

namespace App\Http\Controllers;

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

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand', 'variants.attributeValues.attribute'])->paginate(10);
        $template = 'backend.products.index';
        return view('backend.dashboard.layout', compact('products', 'template'));
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric',
            'variants.*.quantity' => 'required|integer',
            'variants.*.attributes' => 'required|array',
            'variants.*.attributes.*' => 'exists:attribute_values,id',
        ]);

        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        // Xử lý upload ảnh
        $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('products', 'public') : null;

        // Tạo sản phẩm mới
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
            'quantity' => $request->quantity,
            'status' => $request->status,
            'is_variant' => $request->is_variant,
            'views' => 0
        ]);

        // Xử lý các biến thể nếu có
        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                ]);

                foreach ($variantData['attributes'] as $attributeValueId) {
                    ProductVariantAttributeValue::create([
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $attributeValueId,
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'brand', 'variants.attributeValues.attribute'])->findOrFail($id);
        $template = 'backend.products.show';
        return view('backend.dashboard.layout', compact('product', 'template'));
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
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'status' => 'boolean',
            'is_variant' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric',
            'variants.*.quantity' => 'required|integer',
            'variants.*.attributes' => 'required|array',
            'variants.*.attributes.*' => 'exists:attribute_values,id',
        ]);

        // Xử lý upload ảnh mới (nếu có)
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
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

        // Xóa các biến thể cũ (nếu có)
        $product->variants()->delete();

        // Xử lý các biến thể mới (nếu có)
        if ($request->is_variant && $request->variants) {
            foreach ($request->variants as $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                ]);

                foreach ($variantData['attributes'] as $attributeValueId) {
                    ProductVariantAttributeValue::create([
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $attributeValueId,
                    ]);
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
