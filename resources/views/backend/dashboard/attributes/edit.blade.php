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
                        <form action="{{ route($urlBase . 'update', $attribute->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên thuộc tính</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $attribute->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active Checkbox -->
                            <div class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Kích hoạt</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input type="hidden" name="status" value="0">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="1"
                                            {{ old('status', $attribute->status) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Có</label>
                                    </div>
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Cập nhật</button>
                                </div>
                            </div>

                        </form><!-- End Edit Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
