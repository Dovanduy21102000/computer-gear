@if(session('status'))
    <div class="alert alert-success text-center">
        {{ session('status') }}
    </div>
@endif

<div class="container d-flex justify-content-center mt-5 min-vh-100">
    <div class="col-md-5">
        <!-- Tiêu đề form -->
        <div class="border-bottom border-color-1 mb-4">
            <h3 class="section-title mb-0 pb-2 font-size-26 text-center">Đặt lại mật khẩu</h3>
        </div>
        <p class="text-gray-90 text-center">Nhập thông tin bên dưới để đặt lại mật khẩu mới.</p>

        @if($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="p-4 shadow rounded bg-white">
            @csrf

            <!-- Truyền token reset -->
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <!-- Hiển thị email dưới dạng disabled để người dùng chỉ xem -->
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    value="{{ old('email') ?? request('email') }}" 
                    disabled>
                <!-- Gửi giá trị email qua input ẩn -->
                <input 
                    type="hidden" 
                    name="email" 
                    value="{{ old('email') ?? request('email') }}">
            </div>
            

            <div class="form-group">
                <label for="password">Mật khẩu mới <span class="text-danger">*</span></label>
                <input 
                    type="password" 
                    class="form-control" 
                    name="password" 
                    id="password" 
                    placeholder="Nhập mật khẩu mới" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input 
                    type="password" 
                    class="form-control" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    placeholder="Xác nhận mật khẩu" 
                    required
                >
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary w-100">Đặt lại mật khẩu</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a class="text-black" href="{{ route('login') }}">Quay về trang đăng nhập</a>
        </div>
    </div>
</div>
