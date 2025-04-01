<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends BaseCRUDController
{
    public $pathView = 'backend.attributevalues.';
    protected $model = AttributeValue::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'attributevalues.';
    public $titleIndex  = 'Danh sách giá tri thuộc tính';
    public $titleCreate = 'Nhập giá trị thuộc tính';
    public $titleEdit   = 'Nhập giá trị thuộc tính';

    public $columns = [
        'attribute_id'  => 'Thuộc tính',
        'value'         => 'Giá trị thuộc tính',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối chỉnh sửa',
    ];


    public function index()
    {
        $data       = AttributeValue::paginate(10);
        $attributes = Attribute::all();
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;

        $template = 'backend.attributevalues.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'attributes', 'urlBase'));
    }

    public function create()
    {
        $attributes = Attribute::all();
        $title      = $this->titleCreate;
        $urlBase    = $this->urlBase;

        $template = 'backend.attributevalues.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'attributes'));
    }
    public function store(Request $request)
    {
        Log::info('Dữ liệu nhận được:', $request->all()); // Ghi log dữ liệu đầu vào

        try {
            // Gọi validate trước khi lưu
            $validatedData = $this->validateStore($request);

            // Tạo bản ghi mới
            $attributeValue = AttributeValue::create($validatedData);

            Log::info('Lưu thành công:', $attributeValue->toArray()); // Ghi log dữ liệu đã lưu

            return redirect()->route('attributevalues.index')->with('success', 'Thêm giá trị thuộc tính thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu:', ['error' => $e->getMessage()]); // Ghi log lỗi

            return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm giá trị thuộc tính.');
        }
    }


    public function edit($id)
    {
        $attributes          = Attribute::all();
        $attributeValue      = $this->model::findOrFail($id);
        $title               = $this->titleCreate;
        $urlBase             = $this->urlBase;

        $template = 'backend.attributevalues.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'attributes', 'attributeValue'));
    }

    public function show($id)
    {
        $attributes         = Attribute::all();
        $attributeValue     = $this->model::findOrFail($id);
        $urlBase            = $this->urlBase;

        $template = 'backend.attributevalues.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'attributes', 'attributeValue'));
    }

    protected function validateStore(Request $request)
    {
        return $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value'        => 'required|string|max:255',
        ], [
            'attribute_id.required' => 'Vui lòng chọn thuộc tính.',
            'attribute_id.exists'   => 'Thuộc tính không hợp lệ.',
            'value.required'        => 'Giá trị thuộc tính không được để trống.',
            'value.max'             => 'Giá trị thuộc tính không được vượt quá 255 ký tự.',
        ]);
    }

}
