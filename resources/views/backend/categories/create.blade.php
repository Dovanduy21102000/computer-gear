<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm danh mục</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nhập thông tin danh mục</h5>

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
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên danh mục</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="parent_id" class="col-sm-2 col-form-label">Danh mục cha</label>
                                <div class="col-sm-10">
                                    <select name="parent_id" class="form-select">
                                        <option value="">--- Chọn danh mục cha ---</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="is_active" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
