<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\SlugHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BrandController extends BaseCRUDController
{
    public $model       = Brand::class;
    public $pathView    = 'backend.brands.';
    public $urlBase     = 'brands.';
    public $fieldImage  = 'logo';
    public $folderImage = 'brands/images';

    public $titleIndex = 'Danh sách thương hiệu';
    public $titleCreate = 'Thêm mới thương hiệu';
    public $titleEdit = 'Chỉnh sửa thương hiệu';

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
        $data       = Brand::paginate(10);
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
            $request->merge(['slug' => SlugHelper::createSlug($request->name)]);
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
            $request->merge(['slug' => SlugHelper::createSlug($request->name)]);
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

    public function destroy($id)
    {
        try {
            $brand = $this->model::findOrFail($id);

            // Check if brand has associated products
            if ($brand->products()->exists()) {
                return redirect()->back()->with([
                    'alert' => [
                        'type' => 'warning',
                        'content' => 'Không thể xóa thương hiệu này vì có sản phẩm đang sử dụng. Vui lòng xóa hoặc chuyển các sản phẩm sang thương hiệu khác trước khi xóa thương hiệu này.'
                    ]
                ]);
            }

            // Delete the brand's logo if it exists
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            // Delete the brand
            $brand->delete();

            return redirect()->route($this->urlBase . 'index')->with([
                'alert' => [
                    'content' => 'Xóa thương hiệu thành công.'
                ]
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'content' => 'Có lỗi xảy ra khi xóa thương hiệu: ' . $th->getMessage()
                ]
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $brand = $this->model::findOrFail($id);
            $brand->is_active = !$brand->is_active;
            $brand->save();

            return redirect()->back()->with([
                'alert' => [
                    'content' => 'Cập nhật trạng thái thành công.'
                ]
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'content' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $th->getMessage()
                ]
            ]);
        }
    }
}
