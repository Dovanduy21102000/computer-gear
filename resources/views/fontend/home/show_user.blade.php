<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-light shadow-sm">
        <div class="container">
            <div class="py-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="{{ route('home.index') }}" class="text-primary ">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="#" class="text-primary ">Quản lý tài khoản</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active text-dark" aria-current="page">
                            Tài khoản đăng nhập
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <!-- Bố cục chia cột: Cột bên trái chứa thông tin người dùng, bên phải chứa chỉnh sửa và thay đổi mật khẩu -->
            <div class="row w-100">
                <!-- Phần hiển thị thông tin người dùng -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <article class="card-body">
                            <!-- Hiển thị ảnh đại diện người dùng -->
                            <div class="text-center" style="width: 150px; height: 150px; margin: 0 auto; background-color: #f5f5f5; border: 2px solid #ddd; border-radius: 50%; overflow: hidden;">
                                <img class="img-fluid" 
                                     src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                     alt="User Avatar" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <hr class="mt-4">
                            
                            <!-- Hiển thị tên người dùng -->
                            <h3 class="text-center text-dark font-weight-bold">{{ $user->name }}</h3>
                            <hr class="mt-4">

                            <!-- Hiển thị thông tin chi tiết -->
                            <div class="px-3">
                                <div class="mb-3">
                                    <strong>Email:</strong> <span style="color: #222; font-weight: bold;">{{ $user->email }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Số điện thoại:</strong> <span style="color: #222; font-weight: bold;">{{ $user->phone }}</span>
                                </div>
                            </div>

                        </article>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <article class="card-body text-center">
                            <div class="btn-group w-100">
                                <button id="editInfoTab" class="btn btn-outline-primary w-50 active">Chỉnh sửa thông tin</button>
                                <button id="changePasswordTab" class="btn btn-outline-secondary w-50">Thay đổi mật khẩu</button>
                            </div>
                        </article>
                    </div>
        
                    <!-- Tab nội dung: Chỉnh sửa thông tin -->
                    <div id="editInfoForm" class="card border-0 shadow-sm mt-4">
                        <article class="card-body">
                            <h5 class="text-center font-weight-bold">Chỉnh sửa thông tin</h5>
                            <form action="{{ route('user.save') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name"><strong>Tên:</strong></label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone"><strong>Số điện thoại:</strong></label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="avatar"><strong>Ảnh đại diện:</strong></label>
                                    <input type="file" id="avatars" name="avatars" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Lưu thay đổi</button>
                            </form>
                        </article>
                    </div>
        
                    <!-- Tab nội dung: Thay đổi mật khẩu -->
                    <div id="changePasswordForm" class="card border-0 shadow-sm mt-4" style="display: none;">
                        <article class="card-body">
                            <h5 class="text-center font-weight-bold">Thay đổi mật khẩu</h5>
                            <form action="{{ route('user.change-password') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="currentPassword"><strong>Mật khẩu hiện tại:</strong></label>
                                    <input type="password" id="currentPassword" name="currentPassword" class="form-control" required>
                                    @error('currentPassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="newPassword"><strong>Mật khẩu mới:</strong></label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                                    @error('newPassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="renewPassword"><strong>Nhập lại mật khẩu mới:</strong></label>
                                    <input type="password" id="renewPassword" name="newPassword_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Thay đổi mật khẩu</button>
                            </form>
                        </article>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</main>
<br>
<script>
    // JavaScript để chuyển đổi tab
    document.getElementById('editInfoTab').addEventListener('click', () => {
        document.getElementById('editInfoForm').style.display = 'block';
        document.getElementById('changePasswordForm').style.display = 'none';
        document.getElementById('editInfoTab').classList.add('active');                                                                                                                      /
        document.getElementById('changePasswordTab').classList.remove('active');
    });

    document.getElementById('changePasswordTab').addEventListener('click', () => {
        document.getElementById('editInfoForm').style.display = 'none';
        document.getElementById('changePasswordForm').style.display = 'block';
        document.getElementById('changePasswordTab').classList.add('active');
        document.getElementById('editInfoTab').classList.remove('active');
    });
</script>