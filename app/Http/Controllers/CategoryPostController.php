<?php

namespace App\Http\Controllers;

use App\Models\CategoryPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryPostController extends BaseCRUDController
{
    public $pathView = 'backend.category_post.';
    protected $model = CategoryPost::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'category_post.';
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
        $data       = CategoryPost::with('parent')->paginate(5);
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;
        $template = 'backend.category_post.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase'));
    }

    public function create()
    {
        $category_post = CategoryPost::all();
        $title      = $this->titleCreate;
        $urlBase    = $this->urlBase;

        $template = 'backend.category_post.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'category_post'));
    }

    public function edit($id)
    {
        $category_post    = CategoryPost::all();
        $category      = $this->model::findOrFail($id);
        $title         = $this->titleCreate;
        $urlBase       = $this->urlBase;

        $template = 'backend.category_post.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'category_post', 'category'));
    }

    public function show($id)
    {
        $category_post    = CategoryPost::all();
        $category      = $this->model::findOrFail($id);
        $urlBase       = $this->urlBase;

        $template = 'backend.category_post.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'category_post', 'category'));
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
