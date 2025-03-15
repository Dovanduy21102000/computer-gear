<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    // hiển thị danh sách thuộc tính
    public function index(){
        $attributes = Attribute::with('values')->paginate(10);
        $template = 'backend.attributes.index';
        return view('backend.dashboard.layout', compact('attributes','template'));
    }

    // hiển thị form thêm mới thuộc tính
    public function create(){
        $attributes = Attribute::all();
        $template = 'backend.attributes.create';
        return view('backend.dashboard.layout', compact('attributes','template'));
    }

    // xử lý thêm mới thuộc tính
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tên thuộc tính không được để trống',
            'name.string'   => 'Tên thuộc tính phải là một chuỗi',
            'name.max'      => 'Tên không được quá 255 kí tự',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ];

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ], $messages);

        Attribute::create($request->all());

        return redirect()->route('attributes.index')->with('success', 'Thêm thuộc tính thành công!');
    }

    // hiển thị form chỉnh sửa thuộc tính
    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        $template = 'backend.attributes.edit';
        return view('backend.dashboard.layout', compact('template', 'attribute'));
    }

    // Xử lý cập nhật thuộc tính
    public function update(Request $request, Attribute $attribute)
    {
        $messages = [
            'name.required' => 'Tên thuộc tính không được để trống',
            'name.string'   => 'Tên thuộc tính phải là một chuỗi',
            'name.max'      => 'Tên không được quá 255 kí tự',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ];

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ], $messages);

        $attribute->update($request->all());

        return redirect()->route('attributes.index')->with('success', 'Thuộc tính đã được cập nhật thành công!');
    }

}
