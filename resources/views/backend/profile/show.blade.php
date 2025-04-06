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
      <h1>Profile</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item">Users</li>
              <li class="breadcrumb-item active">Profile</li>
          </ol>
      </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
      <div class="row">
          <div class="col-xl-4">
              <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                      <!-- Hiển thị ảnh đại diện của admin -->
                      <img src="{{ $admin->avatar ? Storage::url($admin->avatar) : asset('images/default-avatar.png') }}" alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                      <h2>{{ $admin->name }}</h2>
                      <h3>{{ $admin->job ?? 'Admin' }}</h3>
                  </div>
              </div>
          </div>

          <div class="col-xl-8">
              <div class="card">
                  <div class="card-body pt-3">
                      <!-- Bordered Tabs -->
                      <ul class="nav nav-tabs nav-tabs-bordered">
                          <li class="nav-item">
                              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                          </li>
                          <li class="nav-item">
                              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                          </li>
                          <li class="nav-item">
                              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                          </li>
                      </ul>
                      <div class="tab-content pt-2">
                          <!-- Overview tab -->
                          <div class="tab-pane fade show active profile-overview" id="profile-overview">
                              <h5 class="card-title">About</h5>
                              <p class="small fst-italic">{{ $admin->about }}</p>

                              <h5 class="card-title">Profile Details</h5>
                              <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Full Name</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->name }}</div>
                              </div>
                              {{-- <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Company</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->company ?? 'N/A' }}</div>
                              </div> --}}
                              <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Job</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->job ?? 'Admin' }}</div>
                              </div>
                              {{-- <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Country</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->country ?? 'N/A' }}</div>
                              </div> --}}
                              <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Address</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->address ?? 'Hà Nội' }}</div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Phone</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->phone ?? '0987654321' }}</div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Email</div>
                                  <div class="col-lg-9 col-md-8">{{ $admin->email }}</div>
                              </div>
                          </div>

                          <!-- Edit Profile tab -->
                          <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                              <!-- Profile Edit Form -->
                              <form method="POST" action="{{ route('backend.profile.update') }}" enctype="multipart/form-data">
                                  @csrf
                                  @method('PUT')

                                  <div class="row mb-3">
                                      <label for="avatar" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                      <div class="col-md-8 col-lg-9">
                                          <!-- Hiển thị ảnh đại diện -->
                                          <div class="profile-image-container text-center">
                                              <img src="{{ $admin->avatar ? Storage::url($admin->avatar) : asset('images/default-avatar.png') }}" alt="Profile Image" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                          </div>

                                          <!-- Nút upload và xóa ảnh -->
                                          <div class="pt-3 text-center">
                                              <input type="file" name="avatar" class="btn btn-primary btn-sm">
                                              <a href="{{ route('backend.profile.deleteImage') }}" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i> Remove</a>
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
                                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                      <div class="col-md-8 col-lg-9">
                                          <input name="name" type="text" class="form-control" id="fullName" value="{{ old('name', $admin->name) }}">
                                      </div>
                                  </div>

                                  <div class="row mb-3">
                                      <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                      <div class="col-md-8 col-lg-9">
                                          <input name="phone" type="text" class="form-control" id="phone" value="{{ old('phone', $admin->phone) }}">
                                      </div>
                                  </div>

                                  <div class="text-center">
                                      <button type="submit" class="btn btn-primary">Save Changes</button>
                                  </div>
                              </form><!-- End Profile Edit Form -->
                          </div>

                          <!-- Change Password tab -->
                          <div class="tab-pane fade pt-3" id="profile-change-password">
                              <!-- Change Password Form -->
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
                            
                                <!-- Mật khẩu xác nhận -->
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

                      </div><!-- End Bordered Tabs -->

                  </div>
              </div>

          </div>
      </div>
  </section>
</main><!-- End #main -->
