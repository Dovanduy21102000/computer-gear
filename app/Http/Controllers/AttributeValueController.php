<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends BaseCRUDController
{
    public $pathView = 'backend.attributevalues.';
    protected $model = AttributeValue::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'attributevalues.';
    public $titleIndex  = 'Danh sách thuộc tính con';
    public $titleCreate = 'Tạo mới thuộc tính con';
    public $titleEdit   = 'Chỉnh sửa thuộc tính con';

    public $columns = [
        'attribute_id'  => 'Thuộc tính cha',
        'value'         => 'Giá trị',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối chỉnh sửa',
    ];


    public function index()
    {
        $data       = AttributeValue::paginate(2);
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
            'name'          => 'required|string|max:255',
            'attribute_id'  => 'nullable|exists:attributes,id',
            'value'         =>  'required|string|max:255',
        ], [
            'name.required'         => 'Tên thuộc tính con là bắt buộc.',
            'attribute_id.exists'   => 'Thuộc tính cha không hợp lệ.',
            'value.required'        => 'Giá trị là bắt buộc'
        ]);
    }
}
