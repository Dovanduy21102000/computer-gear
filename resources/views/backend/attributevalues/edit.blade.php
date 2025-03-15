<main id="main" class="main">

    <div class="pagetitle">
        <h1>Form Elements</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Elements</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>

                        <!-- Edit Form -->
                        <form action="{{ route($urlBase . 'update', $attributeValue->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Attribute Category -->
                            <div class="row mb-3">
                                <label for="attribute_id" class="col-sm-2 col-form-label">Thuộc tính</label>
                                <div class="col-sm-10">
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
                                <label for="value" class="col-sm-2 col-form-label">Thông tin thuộc tính con</label>
                                <div class="col-sm-10">
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
                                <div class="col-sm-10 offset-sm-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-success">Cập nhật</button>
                                    <a href="{{ route($urlBase . 'index') }}" class="btn btn-success">Back</a>
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
