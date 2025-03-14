<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết danh mục</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin danh mục</h5>

                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $categories->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên danh mục</th>
                                <td>{{ $categories->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td>{{ $categories->slug }}</td>
                            </tr>
                            <tr>
                                <th>Danh mục cha</th>
                                <td>{{ $categories->parent ? $categories->parent->name : 'Không có' }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <span class="badge {{ $categories->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $categories->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo</th>
                                <td>{{ $categories->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Ngày cập nhật</th>
                                <td>{{ $categories->updated_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

