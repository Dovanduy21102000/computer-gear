@if(session('status'))
    <div class="alert alert-success text-center">
        {{ session('status') }}
    </div>
@endif

<div class="container d-flex justify-content-center mt-5 min-vh-100">
    <div class="col-md-5">
        <!-- Title -->
        <div class="border-bottom border-color-1 mb-4">
            <h3 class="section-title mb-0 pb-2 font-size-26 text-center">Quên mật khẩu</h3>
        </div>
        <p class="text-gray-90 text-center">Nhập email của bạn để nhận liên kết thay đổi mật khẩu.</p>

        @if($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="p-4 shadow rounded bg-white">
            @csrf
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email của bạn" required>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary w-100">Gửi mail</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a class="text-black" href="{{ route('login') }}">Trở lại trang đăng nhập</a>
        </div>
    </div>
</div>
