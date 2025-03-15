<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends BaseCRUDController
{
    public $model       = Brand::class;
    public $pathView    = 'backend.brands.';
    public $urlBase     = 'brands.';
    public $fieldImage  = 'logo';
    public $folderImage = 'brands/images';

    public $titleIndex = 'Danh sách hãng';
    public $titleCreate = 'Thêm mới hãng';
    public $titleEdit = 'Chỉnh sửa hãng';

    public $columns = [
        'name'          => 'Tên hãng',
        'slug'          => 'Slug',
        'logo'          => 'Logo',
        'description'   => 'Mô tả',
        'is_active'     => 'Trạng thái',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối cập nhật'
    ];

    public function index()
    {
        $data       = Brand::paginate(2);
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;

        $template = 'backend.brands.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase'));
    }


    public function create()
    {
        $brands        = Brand::all();
        $title         = $this->titleCreate;
        $urlBase       = $this->urlBase;

        $template = 'backend.brands.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'brands'));
    }

    public function edit($id)
    {
        $brand         = $this->model::findOrFail($id);
        $title         = $this->titleCreate;
        $urlBase       = $this->urlBase;

        $template = 'backend.brands.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'brand'));
    }

    public function show($id)
    {
        $brand         = $this->model::findOrFail($id);
        $urlBase       = $this->urlBase;

        $template = 'backend.brands.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'brand'));
    }

    protected function validateStore(Request $request)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        return parent::validateStore($request) + $request->validate([
            'name'        => ['required', 'max:255', Rule::unique('brands', 'name')],
            'slug'        => ['nullable', 'max:255', Rule::unique('brands', 'slug')],
            'logo'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'description' => ['nullable', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Tên thương hiệu là bắt buộc.',
            'slug.unique'   => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'logo.image'    => 'Logo phải là một tệp hình ảnh.',
            'logo.mimes'    => 'Định dạng logo không hợp lệ (jpeg, png, jpg, gif, svg).',
            'logo.max'      => 'Kích thước logo tối đa là 2MB.',
        ]);
    }

    protected function validateUpdate(Request $request, $id)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        return $request->validate([
            'name'        => ['required', 'max:255', Rule::unique('brands', 'name')->ignore($id)],
            'slug'        => ['nullable', 'max:255', Rule::unique('brands', 'slug')->ignore($id)],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Tên thương hiệu là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'logo.image' => 'Logo phải là một tệp hình ảnh.',
            'logo.mimes' => 'Định dạng logo không hợp lệ (chỉ chấp nhận jpeg, png, jpg, gif, svg).',
            'logo.max' => 'Kích thước logo không được vượt quá 2MB.',
        ]);
    }
}
