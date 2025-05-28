<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        'category_post_id'   => 'Danh mục',
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
        $query = Post::with('category_post');

        if (request()->has('category') && request()->category != '') {
            $query->where('category_post_id', request()->category);
        }

        $data = $query->paginate(8);
        $category_post = CategoryPost::orderBy('name')->get(['id', 'name', 'parent_id'])->toArray();
        $title = $this->titleIndex;
        $columns = $this->columns;
        $urlBase = $this->urlBase;

        $template = 'backend.posts.index';
        return view('backend.dashboard.layout', compact('template', 'data', 'title', 'columns', 'urlBase', 'category_post'));
    }

    public function create()
    {
        $category_post     = CategoryPost::all();
        $title          = $this->titleCreate;
        $urlBase        = $this->urlBase;
        $fieldImage     = $this->fieldImage;
        $folderImage    = $this->folderImage;

        $template = 'backend.posts.add';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'category_post', 'fieldImage', 'folderImage'));
    }

    public function edit($id)
    {
        $category_post     = CategoryPost::all();
        $post          = $this->model::findOrFail($id);
        $title         = $this->titleEdit;
        $urlBase       = $this->urlBase;

        $template = 'backend.posts.edit';
        return view('backend.dashboard.layout', compact('template', 'title', 'urlBase', 'post', 'category_post'));
    }

    public function show($id)
    {
        $category_post     = CategoryPost::all();
        $post           = $this->model::findOrFail($id);
        $urlBase        = $this->urlBase;
        $title          = $this->titleShow;

        $template = 'backend.posts.show';
        return view('backend.dashboard.layout', compact('template', 'urlBase', 'post', 'category_post', 'title'))->with('isShowMode', true);;
    }

    public function store(Request $request)
    {
        $validated = $this->validateStore($request);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
        }

        $post = $this->model::create([
            'category_post_id' => $validated['category_post_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'image' => $imagePath,
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'],
            'status' => $request->has('status') ? 1 : 0,
            'is_hot' => $request->has('is_hot') ? 1 : 0,
            'views' => $validated['views'] ?? 0,
        ]);

        return redirect()->route('posts.index')->with('success', 'Bài viết đã được tạo thành công!');
    }

    protected function validateStore(Request $request)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->title)]); // Slug generated from title
        }

        return $request->validate([
            'category_id' => 'nullable|exists:category_post,id',
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

    protected function validateUpdate(Request $request, $id)
    {
        if (!$request->slug) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }
        return $request->validate([
            'category_post_id' => 'nullable|exists:category_post,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'nullable|boolean',
            'is_hot' => 'nullable|boolean',
            'views' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'category_post_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Ảnh phải là định dạng hợp lệ (jpeg, png, jpg, gif).',
            'image.max' => 'Ảnh không được vượt quá 5MB.',
            'content.required' => 'Nội dung bài viết là bắt buộc.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateUpdate($request, $id);

        $post = $this->model::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('posts/images', 'public');
        } else {
            $imagePath = $post->image;
        }

        $post->update([
            'category_post_id' => $validated['category_post_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'image' => $imagePath,
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'],
            'status' => $request->has('status') ? 1 : 0,
            'is_hot' => $request->has('is_hot') ? 1 : 0,
        ]);

        return redirect()->route('posts.index')->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function toggleStatus($id)
    {
        $post = $this->model::findOrFail($id);
        $post->status = $post->status ? 0 : 1;
        $post->save();
        return response()->json(['status' => $post->status]);
    }
}
