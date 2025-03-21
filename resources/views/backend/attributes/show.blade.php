<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Quản lý thuộc tính</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Chi tiết thuộc tính có id là {{ $attribute->id }}</h5>

                        <!-- Name -->
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-3 col-form-label fw-bold text-nowrap">Tên thuộc tính:</label>
                            <div class="col-sm-9">
                                {{ $attribute->name }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-3 col-form-label fw-bold text-nowrap">Trạng thái:</label>
                            <div class="col-sm-9">
                                <span class="badge {{ $attribute->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $attribute->status == 1 ? 'Kích hoạt' : 'Không kích hoạt' }}
                                </span>
                            </div>
                        </div>

                        <!-- Created At -->
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-3 col-form-label fw-bold text-nowrap">Ngày tạo:</label>
                            <div class="col-sm-9">
                                {{ $attribute->created_at }}
                            </div>
                        </div>

                        <!-- Updated At -->
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-3 col-form-label fw-bold text-nowrap">Ngày cập nhật:</label>
                            <div class="col-sm-9">
                                {{ $attribute->updated_at }}
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
