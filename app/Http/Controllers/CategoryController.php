<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Database\Seeders\CategoriesSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        
        $categories = Category::with('parent')->get();
        $template = 'backend.categories.index';
        return view('backend.dashboard.layout',compact('categories','template'));
    }

    public function create()
    {
        $categories = Category::all();
        $template = 'backend.categories.create';
        return view('backend.dashboard.layout', compact('template','categories'));
    }

    // Xử lý thêm mới danh mục
    public function store(Request $request)
    {
    $messages = [
        'name.required' => 'Tên không được để trống',
        'name.string'   => 'Tên phải là một chuỗi',
        'name.max'      => 'Tên không được quá 255 kí tự',
        'parent_id.integer' => 'Danh mục cha không hợp lệ',
        'status.in'     => 'Trạng thái không hợp lệ',
    ];

    $request->validate([
        'name'        => ['required','string','max:255'],
        'parent_id'   => ['nullable','integer','exists:categories,id'],
        'status'      => [Rule::in(0,1)],
    ], $messages);

    Category::create($request->all());

    return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // Chi tiết danh mục
    public function show($id)
    {
        
        $categories = Category::findOrFail($id);
        $template = 'backend.categories.show';
    
        return view('backend.dashboard.layout', compact('template', 'categories'));
    }
    

    // Sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->get();
        $template = 'backend.categories.edit';

        return view('backend.dashboard.layout', compact('template','category','categories')); 
    }
    

    // Xử lý cập nhật danh mục
    public function update(Request $request, Category $category)
    {
        $messages = [
            'name.required' => 'Tên không được để trống',
            'name.string'   => 'Tên phải là một chuỗi',
            'name.max'      => 'Tên không được quá 255 kí tự',
            'parent_id.integer' => 'Danh mục cha không hợp lệ',
            'status.in'     => 'Trạng thái không hợp lệ',
        ];
    
        $request->validate([
            'name'        => ['required','string','max:255'],
            'parent_id'   => ['nullable','integer','exists:categories,id'],
            'status'      => [Rule::in(0,1)],
        ], $messages);


        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công !');
    }


    
    // public function destroy(Category $category)
    // {
    //     $category->delete();
    //     return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa !');
    // }
}
