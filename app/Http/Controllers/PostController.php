<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends BaseCRUDController
{
    public $pathView = 'backend.dashboard.posts.';
    protected $model = Post::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'posts.';
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
        $data       = Post::paginate(2);
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;

        $template = 'backend.dashboard.posts.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase'));
    }

    public function create()
    {
        $posts      = Post::all();
        $title      = $this->titleCreate;
        $urlBase    = $this->urlBase;

        $template = 'backend.dashboard.posts.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'posts'));
    }

    public function edit($id)
    {
        $posts         = Post::all();
        $post          = $this->model::findOrFail($id);
        $title         = $this->titleEdit;
        $urlBase       = $this->urlBase;

        $template = 'backend.dashboard.posts.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'posts', 'category'));
    }

    public function show($id)
    {
        $posts         = Post::all();
        $post      = $this->model::findOrFail($id);
        $urlBase       = $this->urlBase;

        $template = 'backend.dashboard.posts.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'posts', 'category'));
    }

    protected function validateStore(Request $request)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug',
            'parent_id' => 'nullable|exists:posts,id',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
        ]);
    }
}
