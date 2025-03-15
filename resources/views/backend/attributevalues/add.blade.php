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

                        <!-- General Form Elements -->
                        <form action="{{ route($urlBase . 'store') }}" method="POST">
                            @csrf

                            <!-- attribute Category -->
                            <div class="row mb-3">
                                <label for="attribute_id" class="col-sm-2 col-form-label">Thuộc tính</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('attribute_id') is-invalid @enderror"
                                        id="attribute_id" name="attribute_id">
                                        <option value="">-- Chọn thuộc tính --</option>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}"
                                                {{ old('attribute_id') == $attribute->id ? 'selected' : '' }}>
                                                {{ $attribute->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('attribute_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Attribute value -->
                            <div class="row mb-3">
                                <label for="value" class="col-sm-2 col-form-label">Thông tin thuộc tính con</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('value') is-invalid @enderror"
                                        id="value" name="value" value="{{ old('value') }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
