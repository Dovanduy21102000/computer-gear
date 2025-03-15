<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends BaseCRUDController
{
    public $pathView = 'backend.attributes.';
    protected $model = Attribute::class;
    protected $fieldImage = null;
    public $folderImage;
    public $urlBase     = 'attributes.';
    public $titleIndex  = 'Danh sách thuộc tính';
    public $titleCreate = 'Tạo mới thuộc tính';
    public $titleEdit   = 'Chỉnh sửa thuộc tính';

    public $columns = [
        'name'          => 'Tên thuộc tính',
        'status'        => 'Tình trạng',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối chỉnh sửa',
        'deleted_at'    => 'Thời gian xoá',
    ];


    public function index()
    {
        $data       = Attribute::paginate(2);
        $title      = $this->titleIndex;
        $columns    = $this->columns;
        $urlBase    = $this->urlBase;

        $template = 'backend.attributes.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase'));
    }

    public function create()
    {
        $title      = $this->titleCreate;
        $urlBase    = $this->urlBase;

        $template = 'backend.attributes.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase'));
    }

    public function edit($id)
    {
        $attribute     = $this->model::findOrFail($id);
        $title         = $this->titleCreate;
        $urlBase       = $this->urlBase;

        $template = 'backend.attributes.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'attribute'));
    }

    public function show($id)
    {
        $attribute     = $this->model::findOrFail($id);
        $urlBase       = $this->urlBase;

        $template = 'backend.attributes.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'attribute'));
    }

    protected function validateStore(Request $request)
    {

        return $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
        ]);
    }

    protected function validateUpdate(Request $request, $id)
    {   

        return $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
        ]);
    }
}
