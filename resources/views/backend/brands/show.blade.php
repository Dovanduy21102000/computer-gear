<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý thương hiệu</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($urlBase . 'index') }}">Quản lý thương hiệu</a></li>
                <li class="breadcrumb-item active">Chi tiết thương hiệu</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin thương hiệu có ID: {{ $brand->id }}</h5>

                        <!-- Brand Details -->
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="20%">Tên thương hiệu:</th>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug:</th>
                                    <td>{{ $brand->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Logo:</th>
                                    <td>
                                        <img src="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('backend/img/mvc_logo.png') }}"
                                            alt="Logo" width="120">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mô tả:</th>
                                    <td>{{ $brand->description }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày thêm:</th>
                                    <td>{{ $brand->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật:</th>
                                    <td>{{ $brand->updated_at }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <span class="badge {{ $brand->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $brand->status ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary me-2">Quay lại</a>
                            <a href="{{ route($urlBase . 'edit', $brand->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Chỉnh sửa
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
