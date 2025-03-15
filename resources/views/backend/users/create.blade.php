<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý thành viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý thành viên</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nhập thông tin thành viên</h5>

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
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Nhập tên thành viên:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control"  value="{{ old('name') }}">
                                    
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-sm-2 col-form-label">Nhập số điện thoại:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control"  value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label" >Nhập email:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" class="form-control" value="{{ old('email') }}">
            
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label" >Nhập mật khẩu:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="password" class="form-control">
            
                                </div>
                            </div>
                            <div>
                                <label for="avatar">Ảnh đại diện:</label>
                                <input type="file" name="avatar" id="avatar" accept="image/*">
                                @error('avatar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" >Thêm thành viên</button>

                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
