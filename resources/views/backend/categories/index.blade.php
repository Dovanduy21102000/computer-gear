<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="pagetitle">
        <h1>Quản lý danh mục sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Danh sách danh mục</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Danh sách danh mục</h5>
                            <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Thêm mới
                            </a>
                        </div>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">Tên danh mục</th>
                                        <th class="text-center">Slug</th>
                                        <th class="text-center">Ngày thêm</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        function renderCategoryRows($categories, $urlBase, $level = 0)
                                        {
                                            foreach ($categories as $item) {
                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<div style="margin-left: ' . $level * 24 . 'px;">';
                                                if ($level > 0) {
                                                    echo '<span style="font-size: 1.1em;">↳</span> ';
                                                }
                                                echo '<span' .
                                                    ($level == 0 ? ' class="fw-bold"' : '') .
                                                    '>' .
                                                    $item->name .
                                                    '</span>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td class="text-center"><span class="text-muted">' .
                                                    $item->slug .
                                                    '</span></td>';
                                                echo '<td class="text-center">' .
                                                    $item->created_at->format('d/m/Y') .
                                                    '</td>';
                                                echo '<td class="text-center"><span class="badge ' .
                                                    ($item->is_active ? 'bg-success' : 'bg-danger') .
                                                    '">' .
                                                    ($item->is_active ? 'Đã kích hoạt' : 'Chưa kích hoạt') .
                                                    '</span></td>';
                                                echo '<td class="text-center text-nowrap">';
                                                echo '<a href="' .
                                                    route($urlBase . 'show', $item) .
                                                    '" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a> ';
                                                echo '<a href="' .
                                                    route($urlBase . 'edit', $item) .
                                                    '" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a> ';
                                                echo '<form action="' .
                                                    route($urlBase . 'destroy', $item) .
                                                    '" method="post" style="display: inline-block;">';
                                                echo csrf_field();
                                                echo method_field('DELETE');
                                                echo '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc muốn xoá?\');"><i class="bi bi-trash"></i></button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                                if ($item->children && $item->children->count() > 0) {
                                                    renderCategoryRows($item->children, $urlBase, $level + 1);
                                                }
                                            }
                                        }
                                    @endphp
                                    @php
                                        renderCategoryRows($data->where('parent_id', null), $urlBase);
                                    @endphp
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                        <div class="d-flex justify-content-end mt-3">
                            {{ $data->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
