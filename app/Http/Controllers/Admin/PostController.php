<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends BaseCRUDController
{
    public $pathView = 'backend.posts.';
    protected $model = Post::class;
    public $fieldImage = 'image';
    public $folderImage = 'posts/images';
    public $urlBase     = 'posts.';
    public $titleIndex  = 'Danh sách bài viết';
    public $titleCreate = 'Tạo mới bài viết';
    public $titleEdit   = 'Chỉnh sửa bài viết';

    public $titleShow   = 'Thông tin bài viết có id:';

    public $columns = [
        'category_id'   => 'Danh mục',
        'title'         => 'Tiêu đề',
        'slug'          => 'Slug',
        'image'         => 'Thumbnail',
        'description'   => 'Mô tả',
        'content'       => 'Nội dung',
        'status'        => 'Trạng thái',
        'is_hot'        => 'Nổi bật?',
        'views'         => 'Lượt xem',
        'created_at'    => 'Thời gian tạo',
        'updated_at'    => 'Lần cuối chỉnh sửa',
        'deleted_at'    => 'Thời gian xoá',
    ];


    public function index()
    {
        $data           = Post::paginate(8);
        $categories     = Category::all();
        $title          = $this->titleIndex;
        $columns        = $this->columns;
        $urlBase        = $this->urlBase;

        $template = 'backend.posts.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase', 'categories'));
    }

    public function create()
    {
        $categories     = Category::all();
        $title          = $this->titleCreate;
        $urlBase        = $this->urlBase;
        $fieldImage     = $this->fieldImage;
        $folderImage    = $this->folderImage;

        $template = 'backend.posts.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'categories', 'fieldImage', 'folderImage'));
    }

    public function edit($id)
    {
        $categories    = Category::all();
        $post          = $this->model::findOrFail($id);
        $title         = $this->titleEdit;
        $urlBase       = $this->urlBase;

        $template = 'backend.posts.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'post', 'categories'));
    }

    public function show($id)
    {
        $categories     = Category::all();
        $post           = $this->model::findOrFail($id);
        $urlBase        = $this->urlBase;
        $title          = $this->titleShow;

        $template = 'backend.posts.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'post', 'categories', 'title'))->with('isShowMode', true);;
    }




    protected function validateStore(Request $request)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->title)]); // Slug generated from title
        }

        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'nullable|boolean',
            'is_hot' => 'nullable|boolean',
            'views' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Ảnh phải là định dạng hợp lệ (jpeg, png, jpg, gif).',
            'image.max' => 'Ảnh không được vượt quá 5MB.',
            'content.required' => 'Nội dung bài viết là bắt buộc.',
        ]);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            ]);

            // Get uploaded file
            $file = $request->file('upload');

            // Generate a unique filename
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store the file in storage/app/public/uploads
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Generate the accessible URL
            $fileUrl = asset("storage/uploads/{$fileName}");

            // Respond with success
            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $fileUrl,
            ]);
        } catch (\Exception $e) {
            Log::error("File Upload Error: " . $e->getMessage());

            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => 'File upload failed: ' . $e->getMessage(),
                ],
            ]);
        }
    }
}
