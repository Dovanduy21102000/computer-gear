<main id="main" class="main">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
  
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
  
    <div class="pagetitle">
        <h1>Hồ sơ cá nhân</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Trang chủ</a></li>
                <li class="breadcrumb-item">Người dùng</li>
                <li class="breadcrumb-item active">Hồ sơ</li>
            </ol>
        </nav>
    </div><!-- Kết thúc tiêu đề trang -->
  
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <!-- Hiển thị ảnh đại diện của admin -->
                        <img src="{{ $admin->avatar ? Storage::url($admin->avatar) : asset('images/default-avatar.png') }}" alt="Ảnh đại diện" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <h2>{{ $admin->name }}</h2>
                        <h3>{{ $admin->job ?? 'Quản trị viên' }}</h3>
                    </div>
                </div>
            </div>
  
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Tabs có đường viền -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Tổng quan</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Chỉnh sửa hồ sơ</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Đổi mật khẩu</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <!-- Tab Tổng quan -->
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Giới thiệu</h5>
                                <p class="small fst-italic">{{ $admin->about }}</p>
  
                                <h5 class="card-title">Chi tiết hồ sơ</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Họ và tên</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->name }}</div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Công ty</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->company ?? 'Không có' }}</div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Nghề nghiệp</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->job ?? 'Quản trị viên' }}</div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Quốc gia</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->country ?? 'Không có' }}</div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Địa chỉ</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->address ?? 'Hà Nội' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Số điện thoại</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->phone ?? '0987654321' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $admin->email }}</div>
                                </div>
                            </div>
  
                            <!-- Tab Chỉnh sửa hồ sơ -->
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Form chỉnh sửa hồ sơ -->
                                <form method="POST" action="{{ route('backend.profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
  
                                    <div class="row mb-3">
                                        <label for="avatar" class="col-md-4 col-lg-3 col-form-label">Ảnh đại diện</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="profile-image-container text-center">
                                                <img src="{{ $admin->avatar ? Storage::url($admin->avatar) : asset('images/default-avatar.png') }}" alt="Ảnh đại diện" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                            </div>
  
                                            <div class="pt-3 text-center">
                                                <input type="file" name="avatar" class="btn btn-primary btn-sm">
                                                <a href="{{ route('backend.profile.deleteImage') }}" class="btn btn-danger btn-sm" title="Xóa ảnh đại diện"><i class="bi bi-trash"></i> Xóa</a>
                                            </div>
                                        </div>
                                    </div>
  
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email" value="{{ old('email', $admin->email) }}">
                                        </div>
                                    </div>
  
                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Họ và tên</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control" id="fullName" value="{{ old('name', $admin->name) }}">
                                        </div>
                                    </div>
  
                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Số điện thoại</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text" class="form-control" id="phone" value="{{ old('phone', $admin->phone) }}">
                                        </div>
                                    </div>
  
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form><!-- Kết thúc form chỉnh sửa -->
                            </div>
  
                            <!-- Tab Đổi mật khẩu -->
                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Form đổi mật khẩu -->
                                <form method="POST" action="{{ route('backend.profile.changePassword') }}">
                                  @csrf
                              
                                  <!-- Mật khẩu hiện tại -->
                                  <div class="row mb-3">
                                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Mật khẩu hiện tại</label>
                                      <div class="col-md-8 col-lg-9">
                                          <input name="currentPassword" type="password" class="form-control @error('currentPassword') is-invalid @enderror" id="currentPassword">
                                          @error('currentPassword')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                  </div>
                              
                                  <!-- Mật khẩu mới -->
                                  <div class="row mb-3">
                                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Mật khẩu mới</label>
                                      <div class="col-md-8 col-lg-9">
                                          <input name="newPassword" type="password" class="form-control @error('newPassword') is-invalid @enderror" id="newPassword">
                                          @error('newPassword')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                  </div>
                              
                                  <!-- Nhập lại mật khẩu mới -->
                                  <div class="row mb-3">
                                      <label for="newPassword_confirmation" class="col-md-4 col-lg-3 col-form-label">Nhập lại mật khẩu mới</label>
                                      <div class="col-md-8 col-lg-9">
                                          <input name="newPassword_confirmation" type="password" class="form-control @error('newPassword_confirmation') is-invalid @enderror" id="newPassword_confirmation">
                                          @error('newPassword_confirmation')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                  </div>
                              
                                  <div class="text-center">
                                      <button type="submit" class="btn btn-primary">Thay đổi mật khẩu</button>
                                  </div>
                              </form>
                            </div>
  
                        </div><!-- Kết thúc Tabs có đường viền -->
  
                    </div>
                </div>
  
            </div>
        </div>
    </section>
  </main><!-- Kết thúc #main -->
  