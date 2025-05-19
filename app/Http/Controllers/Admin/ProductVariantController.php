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
        'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'attributes'        => 'required|array',
        'attributes.*.key'   => 'required',
        'attributes.*.value' => 'required',
    ]);
    $attributesInput = $request->input('attributes');
    // dd($attributesInput);
    $submittedAttrValueIds = collect($attributesInput)
                                    ->map(function ($attr) {
                                        return $attr['value'];
                                    })
                                    ->sort()
                                    ->values()
                                    ->all();

    $existingVariant = $product->variants->first(function ($variant) use ($submittedAttrValueIds) {
        $existingAttrValueIds = $variant->attributeValues->pluck('id')
                                        ->sort()
                                        ->values()
                                        ->all();
        return $existingAttrValueIds == $submittedAttrValueIds;
    });

    if ($existingVariant) {
        $existingVariant->update([
            'quantity' => $existingVariant->quantity + $validated['quantity']
        ]);
        return redirect()->route('variants.index', ['product' => $product->id])
                         ->with('success', 'Biến thể đã tồn tại, số lượng đã được cập nhật.');
    }

    $imagePath = $request->hasFile('image')
        ? $request->file('image')->store('variants', 'public')
        : null;

    $variant = $product->variants()->create([
        'sku'        => $validated['sku'],
        'price'      => $validated['price'],
        'price_sale' => $validated['price_sale'] ?? null,
        'quantity'   => $validated['quantity'],
        'image'      => $imagePath,
        'status'     => 1, 
    ]);

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
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        
        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $imagePath = $request->file('image')->store('variants', 'public');
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
}
