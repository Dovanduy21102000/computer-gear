<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý giá trị thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Quản lý giá trị thuộc tính</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row"> <!-- Xóa justify-content-center -->
            <div class="col-lg-8"> <!-- Mở rộng khối để không quá nhỏ -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin giá trị thuộc tính có ID: {{ $attributeValue->id }}</h5>

                        <!-- Edit Form -->
                        <form action="{{ route($urlBase . 'update', $attributeValue->id) }}" method="POST">
                            @csrf

                            <!-- Attribute Category -->
                            <div class="row mb-3">
                                <div class="col-md-3 d-flex align-items-center">
                                    <label for="attribute_id" class="fw-bold text-nowrap mb-0">Thuộc tính:</label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control @error('attribute_id') is-invalid @enderror"
                                        id="attribute_id" name="attribute_id" disabled>
                                        <option value="">-- Chọn thuộc tính --</option>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}"
                                                {{ old('attribute_id', $attributeValue->attribute_id) == $attribute->id ? 'selected' : '' }}>
                                                {{ $attribute->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('attribute_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Attribute Value -->
                            <div class="row mb-3">
                                <div class="col-md-3 d-flex align-items-center">
                                    <label for="value" class="fw-bold text-nowrap mb-0">Giá trị:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control @error('value') is-invalid @enderror"
                                        id="value" name="value" value="{{ old('value', $attributeValue->value) }}"
                                        required disabled>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Created At -->
                            <div class="row mb-2">
                                <div class="col-md-3 d-flex align-items-center">
                                    <label class="fw-bold text-nowrap mb-0">Ngày tạo:</label>
                                </div>
                                <div class="col-md-9">
                                    {{ $attributeValue->created_at }}
                                </div>
                            </div>

                            <!-- Updated At -->
                            <div class="row mb-2">
                                <div class="col-md-3 d-flex align-items-center">
                                    <label class="fw-bold text-nowrap mb-0">Ngày cập nhật:</label>
                                </div>
                                <div class="col-md-9">
                                    {{ $attributeValue->updated_at }}
                                </div>
                            </div>

                        </form><!-- End Edit Form -->

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-9 offset-md-3">
                                <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>

                    </div>
                </div><!-- End Card -->
            </div>
        </div>
    </section>
</main>
