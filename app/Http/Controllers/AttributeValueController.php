<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    // Hiển thị danh sách giá trị thuộc tính
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->paginate(10);
        $template = 'backend.attribute_values.index';
        return view('backend.dashboard.layout', compact('attributeValues', 'template'));
    }

    // Hiển thị form thêm mới giá trị thuộc tính
    public function create()
    {
        $attributes = Attribute::where('status', true)->get(); // Chỉ lấy thuộc tính đang hoạt động
        $template = 'backend.attribute_values.create';
        return view('backend.dashboard.layout', compact('attributes', 'template'));
    }

    // Xử lý thêm mới giá trị thuộc tính
    public function store(Request $request)
    {
        $messages = [
            'attribute_id.required' => 'Vui lòng chọn thuộc tính',
            'attribute_id.exists'   => 'Thuộc tính không hợp lệ',
            'value.required'        => 'Giá trị không được để trống',
            'value.string'          => 'Giá trị phải là một chuỗi',
            'value.max'             => 'Giá trị không được quá 255 kí tự',
        ];

        $request->validate([
            'attribute_id' => ['required', 'exists:attributes,id'],
            'value'        => ['required', 'string', 'max:255'],
        ], $messages);

        AttributeValue::create($request->all());

        return redirect()->route('attribute-values.index')->with('success', 'Thêm giá trị thuộc tính thành công!');
    }

    // Hiển thị form chỉnh sửa giá trị thuộc tính
    public function edit($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::where('status', true)->get();
        $template = 'backend.attribute_values.edit';

        return view('backend.dashboard.layout', compact('attributeValue', 'attributes', 'template'));
    }

    // Xử lý cập nhật giá trị thuộc tính
    public function update(Request $request, AttributeValue $attributeValue)
    {
        $messages = [
            'attribute_id.required' => 'Vui lòng chọn thuộc tính',
            'attribute_id.exists'   => 'Thuộc tính không hợp lệ',
            'value.required'        => 'Giá trị không được để trống',
            'value.string'          => 'Giá trị phải là một chuỗi',
            'value.max'             => 'Giá trị không được quá 255 kí tự',
        ];

        $request->validate([
            'attribute_id' => ['required', 'exists:attributes,id'],
            'value'        => ['required', 'string', 'max:255'],
        ], $messages);

        $attributeValue->update($request->all());

        return redirect()->route('attribute-values.index')->with('success', 'Giá trị thuộc tính đã được cập nhật thành công!');
    }
}
