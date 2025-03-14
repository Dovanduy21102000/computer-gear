<main id="main" class="main">

    <div class="pagetitle">
        <h1>Chỉnh sửa thành viên</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Chỉnh sửa thông tin thành viên</h5>

                        <!-- Hiển thị lỗi nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Nhập tên thành viên:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-sm-2 col-form-label">Nhập số điện thoại:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Nhập email:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Nhập mật khẩu mới:</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control" placeholder="Để trống nếu không muốn đổi">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="avatar" class="col-sm-2 col-form-label">Ảnh đại diện:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="avatar" id="avatar" accept="image/*">
                                    @error('avatar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <!-- Hiển thị ảnh hiện tại -->
                                    @if ($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" width="100">
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>

                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
