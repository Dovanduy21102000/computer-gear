<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý giá trị thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Quản lý giá trị thuộc tính</li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6"><!-- Căn chỉnh form nhỏ gọn hơn -->

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>

                        <!-- Edit Form -->
                        <form action="{{ route($urlBase . 'update', $attributeValue->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Attribute Category -->
                            <div class="row mb-3">
                                <label for="attribute_id" class="col-sm-4 col-form-label fw-bold">Thuộc tính</label>
                                <div class="col-sm-8">
                                    <select class="form-control @error('attribute_id') is-invalid @enderror"
                                        id="attribute_id" name="attribute_id">
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
                                <label for="value" class="col-sm-4 col-form-label fw-bold">Giá trị thuộc tính</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('value') is-invalid @enderror"
                                        id="value" name="value" value="{{ old('value', $attributeValue->value) }}"
                                        required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-8 offset-sm-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                                    <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </div>

                        </form>
                        <!-- End Edit Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
