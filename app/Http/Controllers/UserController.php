<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        $template = 'backend.users.index';
        return view('backend.dashboard.layout', compact('users', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $template = 'backend.users.create';
        return view('backend.dashboard.layout', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $messages = [
        'name.required' => 'Tên không được để trống.',
        'name.string' => 'Tên phải là chuỗi ký tự.',
        'name.max' => 'Tên không được vượt quá 255 ký tự.',

        'email.required' => 'Email không được để trống.',
        'email.email' => 'Email không hợp lệ.',
        'email.unique' => 'Email đã tồn tại.',

        'password.required' => 'Mật khẩu không được để trống.',
        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',

        'phone.required' => 'Số điện thoại không được để trống.',

        'avatar.image' => 'Ảnh đại diện phải là định dạng ảnh.',
        'avatar.mimes' => 'Ảnh chỉ được phép có định dạng jpg, jpeg, png.',
        'avatar.max' => 'Ảnh không được vượt quá 2MB.',
    ];

    // Kiểm tra dữ liệu đầu vào
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'required',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], $messages);

    // Tạo user mới
    $user = new User();
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->password = bcrypt($validatedData['password']); // Mã hóa mật khẩu
    $user->phone = $validatedData['phone'];

    // Kiểm tra và lưu ảnh đại diện
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'Thêm mới thành công');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = 'backend.users.show';
        $user = User::findOrFail($id);
        return view('backend.dashboard.layout', compact('user', 'template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $template = 'backend.users.edit';
        return view('backend.dashboard.layout', compact('user', 'template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    $messages = [
        'name.required' => 'Tên không được để trống.',
        'name.string' => 'Tên phải là chuỗi ký tự.',
        'name.max' => 'Tên không được vượt quá 255 ký tự.',

        'email.required' => 'Email không được để trống.',
        'email.email' => 'Email không hợp lệ.',
        'email.unique' => 'Email đã tồn tại.',

        'phone.required' => 'Số điện thoại không được để trống.',

        'avatar.image' => 'Ảnh đại diện phải là định dạng ảnh.',
        'avatar.mimes' => 'Ảnh chỉ được phép có định dạng jpg, jpeg, png.',
        'avatar.max' => 'Ảnh không được vượt quá 2MB.',
    ];

    // Kiểm tra dữ liệu đầu vào
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id, // Bỏ qua email của chính user đang cập nhật
        'password' => 'nullable|min:6', // Cho phép không nhập mật khẩu mới
        'phone' => 'required',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], $messages);

    // Cập nhật dữ liệu
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->phone = $validatedData['phone'];

    // Kiểm tra nếu người dùng có nhập mật khẩu mới thì mới cập nhật
    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    // Kiểm tra nếu có tải lên ảnh mới thì cập nhật
    if ($request->hasFile('avatar')) {
        // Xóa ảnh cũ nếu có
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Lưu ảnh mới
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'Cập nhật thành công');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Xóa thành công');
    }
}
