<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-primary fw-bold">Thêm Quảng Cáo</h1>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg p-4 rounded">
                    <div class="card-body">
                        <h5 class="card-title text-center text-uppercase text-secondary fw-bold">Nhập thông tin Banner</h5>

                        <!-- Hiển thị lỗi -->
                        @if (session()->has('success') && !session()->get('success'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
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

                        <!-- Form -->
                        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Nhập tiêu đề banner...">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="mb-3">
                                <label for="btn_url" class="form-label fw-bold">Liên kết (URL)</label>
                                <input type="url" class="form-control" name="btn_url" value="{{ old('btn_url') }}" placeholder="Nhập đường dẫn, VD: https://example.com">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả về banner...">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không hoạt động</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">Thêm Banner</button>
                            </div>
                        </form>
                        <!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
