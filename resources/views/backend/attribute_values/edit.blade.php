<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chỉnh sửa giá trị thuộc tính</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cập nhật giá trị thuộc tính</h5>

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
                        <form action="{{ route('attribute-values.update', $attributeValue->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Chọn Thuộc Tính -->
                            <div class="row mb-3">
                                <label for="attribute_id" class="col-sm-2 col-form-label">Thuộc tính</label>
                                <div class="col-sm-10">
                                    <select name="attribute_id" class="form-select" required>
                                        @foreach($attributes as $attribute)
                                            <option value="{{ $attribute->id }}" 
                                                {{ old('attribute_id', $attributeValue->attribute_id) == $attribute->id ? 'selected' : '' }}>
                                                {{ $attribute->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Nhập Giá Trị -->
                            <div class="row mb-3">
                                <label for="value" class="col-sm-2 col-form-label">Giá trị</label>
                                <div class="col-sm-10">
                                    <input type="text" name="value" class="form-control" 
                                        value="{{ old('value', $attributeValue->value) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Cập nhật giá trị</button>
                                    <a href="{{ route('attribute-values.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
