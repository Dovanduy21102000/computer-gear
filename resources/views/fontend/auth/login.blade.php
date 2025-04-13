@if(session('login_error'))
    <div class="alert alert-danger">
        {{ session('login_error') }}
    </div>
@endif

<div class="container d-flex justify-content-center mt-5 min-vh-100">
    <div class="col-md-5">
        <!-- Title -->
        <div class="border-bottom border-color-1 mb-4">
            <h3 class="section-title mb-0 pb-2 font-size-26 text-center">Đăng nhập</h3>
        </div>
        <p class="text-gray-90 text-center">Chào mừng đến với Computer Gear</p>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif
        <form action="{{ route('login') }}" method="POST" class="p-4 shadow rounded bg-white">
            @csrf
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email" required>
            </div>
        
            <div class="form-group">
                <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Nhập mật khẩu" required>
            </div>
            <a href="{{route('register.form')}}">Bạn chưa có tài khoản?</a>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </div>
        </form>
        
    </div>
</div>
