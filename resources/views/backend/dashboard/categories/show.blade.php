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
                        <h5 class="card-title">Thông tin danh mục có id: {{ $category->id }}</h5>

                        <!-- Edit Form -->
                        <form action="{{ route($urlBase . 'update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên danh mục</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $category->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Parent Category -->
                            <div class="row mb-3">
                                <label for="parent_id" class="col-sm-2 col-form-label">Danh mục cha</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id"
                                        name="parent_id">
                                        <option value="">-- Chọn danh mục cha --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active Checkbox -->
                            <div class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Kích hoạt</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1"
                                            {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Có</label>
                                    </div>
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <a href="{{ route($urlBase . 'index') }}" class="btn btn-success">Back</a>
                                </div>
                            </div>

                        </form><!-- End Edit Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
