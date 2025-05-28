<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý danh mục bài viết</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Quản lý danh mục bài viết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>
                        @php
                            if (!function_exists('renderCategoryOptions')) {
                                function renderCategoryOptions(
                                    $categories,
                                    $parentId = null,
                                    $parentName = null,
                                    $selectedId = null,
                                ) {
                                    foreach ($categories as $category) {
                                        if ($category['parent_id'] == $parentId) {
                                            $hasChildren =
                                                collect($categories)->where('parent_id', $category['id'])->count() > 0;
                                            $displayName =
                                                $parentName && $hasChildren
                                                    ? "{$category['name']} ({$parentName})"
                                                    : $category['name'];
                                            if ($hasChildren) {
                                                echo "<optgroup label=\"{$displayName}\">";
                                                renderCategoryOptions(
                                                    $categories,
                                                    $category['id'],
                                                    $category['name'],
                                                    $selectedId,
                                                );
                                                echo '</optgroup>';
                                            } else {
                                                $selected = $selectedId == $category['id'] ? 'selected' : '';
                                                echo "<option value=\"{$category['id']}\" {$selected}>{$displayName}</option>";
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <form method="GET" action="{{ route($urlBase . 'index') }}"
                                class="d-flex align-items-center flex-grow-1">
                                <select name="parent" id="parent" class="form-control me-2"
                                    style="max-width:300px;">
                                    <option value="">-- Lọc theo danh mục cha --</option>
                                    @php renderCategoryOptions($category_post->toArray(), null, null, request('parent')); @endphp
                                </select>
                                <button type="submit" class="btn btn-primary ms-2">Lọc</button>
                                <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary ms-2">Reset</a>
                            </form>
                            <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary ms-2">Thêm mới</a>
                        </div>

                        <!-- Table with stripped rows -->
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-top">
                                <div class="datatable-dropdown">
                                    <label>
                                        <select class="datatable-selector" name="per-page">
                                            <option value="5">5</option>
                                            <option value="10" selected="">10</option>
                                            <option value="15">15</option>
                                            <option value="-1">All</option>
                                        </select> entries per page
                                    </label>
                                </div>
                                <div class="datatable-search">
                                    <input class="datatable-input" placeholder="Search..." type="search" name="search"
                                        title="Search within table">
                                </div>
                            </div>
                            <div class="datatable-container">
                                <table class="table datatable datatable-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                Tên danh mục
                                                </td>
                                            <th class="text-center">
                                                Slug
                                                </td>
                                            <th class="text-center">
                                                Danh mục cha
                                                </td>
                                            <th class="text-center">
                                                Ngày thêm
                                                </td>
                                            <th class="text-center">
                                                Trạng thái
                                                </td>
                                            <th class="text-center">
                                                Hành động
                                                </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="name"><span
                                                            class="maintitle">{{ $item->name }}</span></div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="slug">{{ $item->slug }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="parent_id">{{ $item->parent->name ?? 'Không có' }}</div>
                                                </td>

                                                <td class="text-center">
                                                    <div class="created_at">{{ $item->created_at }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge status-toggle-badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}"
                                                        data-id="{{ $item->id }}" style="cursor:pointer;">
                                                        <span
                                                            class="status-text">{{ $item->is_active ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}</span>
                                                        <span class="spinner-border spinner-border-sm d-none"
                                                            role="status" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td class="text-center text-nowrap" style="width: 1px;">
                                                    <a href="{{ route($urlBase . 'show', $item) }}"
                                                        class="btn btn-success">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route($urlBase . 'edit', $item) }}"
                                                        class="btn btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route($urlBase . 'destroy', $item) }}"
                                                        method="post" id="item-{{ $item->id }}"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc muốn xoá?');">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Table with stripped rows -->
                        <div class="d-flex justify-content-center mt-2">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-toggle-badge').forEach(function(badge) {
            badge.addEventListener('click', function() {
                var catId = this.getAttribute('data-id');
                var badgeEl = this;
                var spinner = badgeEl.querySelector('.spinner-border');
                var statusText = badgeEl.querySelector('.status-text');
                spinner.classList.remove('d-none');
                fetch('/admin/category_post/toggle-status/' + catId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        spinner.classList.add('d-none');
                        if (data.is_active === 1) {
                            badgeEl.classList.remove('bg-danger');
                            badgeEl.classList.add('bg-success');
                            statusText.textContent = 'Đã kích hoạt';
                        } else {
                            badgeEl.classList.remove('bg-success');
                            badgeEl.classList.add('bg-danger');
                            statusText.textContent = 'Chưa kích hoạt';
                        }
                    })
                    .catch(() => {
                        spinner.classList.add('d-none');
                        alert('Đã xảy ra lỗi!');
                    });
            });
        });
    });
</script>
