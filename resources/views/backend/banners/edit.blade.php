<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-primary">Cập nhật quảng cáo</h1>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center text-uppercase">Cập nhật thông tin banner</h5>

                        <!-- Hiển thị lỗi -->
                        @if (session()->has('success') && !session()->get('success'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @if (session()->has('success') && session()->get('success'))
                            <div class="alert alert-success">
                                Thao tác thành công!
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
                        <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Ảnh</label>
                                <input type="file" class="form-control" name="image">
                                @if ($banner->image)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($banner->image) }}" class="img-thumbnail rounded shadow-sm" style="width: 150px; height: auto;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="btn_url" class="form-label fw-bold">Liên kết</label>
                                <input type="url" class="form-control" name="btn_url" value="{{ $banner->btn_url }}" placeholder="Nhập đường dẫn (VD: https://example.com)">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4">{{ $banner->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ $banner->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ $banner->status == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Cập nhật banner
                                </button>
                            </div>
                        </form><!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
