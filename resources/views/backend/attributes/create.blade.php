<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm thuộc tính</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nhập thông tin thuộc tính</h5>

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
                        <form action="{{ route('attributes.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên thuộc tính</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Không hoạt động
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Thêm thuộc tính</button>
                                    <a href="{{ route('attributes.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->