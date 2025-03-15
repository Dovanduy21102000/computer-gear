<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseCRUDController
{
    public $pathView = 'backend.categories.';
    protected $model = Category::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'categories.';
    public $titleIndex  = 'Danh sách danh mục';
    public $titleCreate = 'Tạo mới danh mục';
    public $titleEdit   = 'Chỉnh sửa danh mục';

    public $columns = [
        'name'          => 'Tên danh mục',
        'slug'          => 'Slug',
        'parent_id'     => 'Cấp bậc',
        'is_active'     => 'Tình trạng',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối chỉnh sửa',
        'deleted_at'    => 'Thời gian xoá',
    ];


    public function index()
    {
        $data       = Category::paginate(2);
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;

        $template = 'backend.categories.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase'));
    }

    public function create()
    {
        $categories = Category::all();
        $title      = $this->titleCreate;
        $urlBase    = $this->urlBase;

        $template = 'backend.categories.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'categories'));
    }

    public function edit($id)
    {
        $categories    = Category::all();
        $category      = $this->model::findOrFail($id);
        $title         = $this->titleCreate;
        $urlBase       = $this->urlBase;

        $template = 'backend.categories.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'categories', 'category'));
    }

    public function show($id)
    {
        $categories    = Category::all();
        $category      = $this->model::findOrFail($id);
        $urlBase       = $this->urlBase;

        $template = 'backend.categories.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'categories', 'category'));
    }

    protected function validateStore(Request $request)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
        ]);
    }
}
