<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-5">
        <!-- Tiêu đề -->
        <div class="border-bottom border-color-1 mb-4">
            <h3 class="section-title mb-0 pb-2 font-size-26 text-center">Đăng ký tài khoản</h3>
        </div>
        <p class="text-gray-90 text-center">Chào mừng đến với Computer Gear</p>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif

        <!-- Form đăng ký -->
        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
            @csrf

            <div class="form-group">
                <label for="name">Tên đầy đủ <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên đầy đủ" required>
            </div>

            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Nhập số điện thoại">
            </div>

            <div class="form-group">
                <label for="avatar">Ảnh đại diện</label>
                <input type="file" class="form-control-file" name="avatar" id="avatar">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Nhập lại mật khẩu" required>
            </div>

            <!-- Các giá trị mặc định -->
            <input type="hidden" name="role" value="member">
            <input type="hidden" name="status" value="active">

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
            </div>

            <div class="text-center mt-3">
                <p>Đã có tài khoản? <a href="{{ route('login.form') }}">Đăng nhập</a></p>
            </div>
        </form>
    </div>
</div>
