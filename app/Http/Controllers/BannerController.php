<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(5);
        $template = 'backend.banners.index';

        return view('backend.dashboard.layout', compact('banners', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $template = 'backend.banners.create';
        return view('backend.dashboard.layout', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'title.required' => 'Tiêu đề không được để trống.',
            'image.required' => 'Hình ảnh banner không được để trống.',
            'btn_url.url' => 'Đường dẫn phải là một URL hợp lệ.',
            'description.required' => 'Mô tả không được để trống.',
            'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
        ];

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image'  => 'required|image|max:2048',
            'btn_url' => 'required|url',
            'description' => 'required|string|max:255',
            'status' => 'required|boolean',
        ], $messages);
        try {
            if ($request->hasFile('image')) {
                $data['image']  = Storage::put('banners', $request->file('image'));
            }
            Banner::query()->create($data);
            return redirect()
                ->route('banners.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            if (!empty($data['image'])   && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
        // Banner::create($request->all());

        // return redirect()->back()->with('success', 'Thêm banner thành công!');
    }


    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        $template = 'backend.banners.show';

        return view('backend.dashboard.layout', compact('template', 'banner'));
    }
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $template = 'backend.banners.edit';

        return view('backend.dashboard.layout', compact('template', 'banner'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $messages = [
            'title.required' => 'Tiêu đề không được để trống.',
            'image.required' => 'Hình ảnh banner không được để trống.',
            'btn_url.url' => 'Đường dẫn phải là một URL hợp lệ.',
            'description.required' => 'Mô tả không được để trống.',
            'status.boolean' => 'Trạng thái phải là đúng hoặc sai.',
        ];

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'btn_url' => 'nullable|url',
            'description' => 'required|string|max:255',
            'status' => 'required|boolean',
        ], $messages);
        try {
            if ($request->hasFile('image')) {
                $data['image']  = Storage::put('banners', $request->file('image'));
            }

            $currenAvatar = $banner->image;
            $banner->update($data);

            if($request->hasFile('image')  && !empty($currenAvatar)  &&  Storage::exists($data['image'])){
                Storage::delete($currenAvatar);
            }

            return back()->with('success', true);
        } catch (\Throwable $th) {
            if (!empty($data['image'])   && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(banner $banner)
    {
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner đã được xóa!');
    }
}
