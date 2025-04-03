<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    public function index($product_id)
    {
        $product = Product::findOrFail($product_id);
        $specifications = Specification::where('product_id', $product_id)->paginate(10);

        $template = 'backend.specifications.index';
        return view('backend.dashboard.layout', compact('specifications', 'product', 'template'));
    }


    public function create($product_id)
    {
        $product = Product::findOrFail($product_id);
        $template = 'backend.specifications.create';

        return view('backend.dashboard.layout', compact('template', 'product'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'specifications' => 'required|array',
            'specifications.*.key' => 'required|string|max:255',
            'specifications.*.value' => 'required|string|max:255',
        ]);

        foreach ($request->specifications as $spec) {
            Specification::create([
                'product_id' => $request->product_id,
                'key' => $spec['key'],
                'value' => $spec['value'],
            ]);
        }

        return redirect()->route('admin.specifications.index', ['product_id' => $request->product_id])
            ->with('success', 'Thông số đã được thêm!');
    }


    public function edit($product_id)
    {
        $specifications = Specification::where('product_id', $product_id)->get();

        // Kiểm tra xem có thông số nào cho sản phẩm không
        if ($specifications->isEmpty()) {
            return redirect()->back()->with('error', 'Không có thông số cho sản phẩm này.');
        }

        $product = Product::findOrFail($product_id);
        $template = 'backend.specifications.edit';
        return view('backend.dashboard.layout', compact('specifications', 'product', 'template'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'required|string|max:255',
            'specifications.*.value' => 'required|string|max:255',
            'deleted_specifications' => 'nullable|string',
        ]);

        // Kiểm tra xem có danh sách ID cần xóa không
        if ($request->deleted_specifications) {
            $deletedSpecs = json_decode($request->deleted_specifications, true);
            if (is_array($deletedSpecs) && !empty($deletedSpecs)) {
                Specification::whereIn('id', $deletedSpecs)->delete();
            }
        }

        // Cập nhật hoặc thêm thông số mới
        if ($request->specifications) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['id'])) {
                    Specification::where('id', $spec['id'])->update([
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                } else {
                    Specification::create([
                        'product_id' => $request->product_id,
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.specifications.index', ['product_id' => $request->product_id])
            ->with('success', 'Thông số đã được cập nhật!');
    }

    public function show($productId)
    {
        // Lấy thông tin sản phẩm cùng với các thông số kỹ thuật
        $product = Product::with('specifications')->find($productId);

        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại');
        }
        $template = 'fontend.products.detail';
        return view('fontend.layout', compact('template', 'product'));
    }
}
