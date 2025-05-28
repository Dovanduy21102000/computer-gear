<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý bài viết</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý bài viết</li>
                <li class="breadcrumb-item active">Danh sách bài viết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>

                        <!-- Filter Form -->
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                            <form method="GET" action="{{ route('posts.index') }}" class="flex-grow-1 me-2">
                                <div class="row">
                                    <!-- Category Filter -->
                                    <div class="col-md-8">
                                        @php
                                            if (!function_exists('renderCategoryOptionsFilter')) {
                                                function renderCategoryOptionsFilter(
                                                    $categories,
                                                    $parentId = null,
                                                    $parentName = null,
                                                    $selectedId = null,
                                                ) {
                                                    foreach ($categories as $category) {
                                                        if ($category['parent_id'] == $parentId) {
                                                            $hasChildren =
                                                                collect($categories)
                                                                    ->where('parent_id', $category['id'])
                                                                    ->count() > 0;
                                                            $displayName =
                                                                $parentName && $hasChildren
                                                                    ? "{$category['name']} ({$parentName})"
                                                                    : $category['name'];
                                                            if ($hasChildren) {
                                                                echo "<optgroup label=\"{$displayName}\">";
                                                                renderCategoryOptionsFilter(
                                                                    $categories,
                                                                    $category['id'],
                                                                    $category['name'],
                                                                    $selectedId,
                                                                );
                                                                echo '</optgroup>';
                                                            } else {
                                                                $selected =
                                                                    $selectedId == $category['id'] ? 'selected' : '';
                                                                echo "<option value=\"{$category['id']}\" {$selected}>{$displayName}</option>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <select name="category" id="category" class="form-control">
                                            <option value="">-- Danh mục --</option>
                                            @php renderCategoryOptionsFilter($category_post, null, null, request('category')); @endphp
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary ms-2">Thêm mới</a>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table datatable table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Tiêu đề</th>
                                        <th style="width: 10%;">Slug</th>
                                        <th style="width: 10%;">Thumbnail</th>
                                        <th style="width: 10%;">Danh mục</th>
                                        <th style="width: 20%;">Mô tả</th>
                                        <th style="width: 10%;">Lượt xem</th>
                                        <th style="width: 10%;">Trạng thái</th>
                                        <th style="width: 15%;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                <div class="text-truncate" title="{{ $item->title }}">
                                                    {{ Str::limit($item->title, 30, '...') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" title="{{ $item->slug }}">
                                                    {{ Str::limit($item->slug, 20, '...') }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Thumbnail"
                                                        style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                                                @else
                                                    <div
                                                        style="width:60px; height:60px; background:#f0f0f0; border-radius:4px;">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate"
                                                    title="{{ $item->category_post->name ?? 'Không có' }}">
                                                    {{ Str::limit($item->category_post->name ?? 'Không có', 20, '...') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" title="{{ $item->description }}">
                                                    {{ Str::limit($item->description, 50, '...') }}
                                                </div>
                                            </td>
                                            <td>{{ $item->views }}</td>
                                            <td>
                                                <span
                                                    class="badge status-toggle-badge {{ $item->status ? 'bg-success' : 'bg-danger' }}"
                                                    data-id="{{ $item->id }}" style="cursor:pointer;">
                                                    <span
                                                        class="status-text">{{ $item->status ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}</span>
                                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                                        aria-hidden="true"></span>
                                                </span>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route($urlBase . 'show', $item) }}"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route($urlBase . 'edit', $item) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route($urlBase . 'destroy', $item) }}" method="post"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
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
                        <!-- End Table -->
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<!-- Tooltip Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- CSS -->
<style>
    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
</style>

<!-- Add JS at the end of the file -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-toggle-badge').forEach(function(badge) {
            badge.addEventListener('click', function() {
                var postId = this.getAttribute('data-id');
                var badgeEl = this;
                var spinner = badgeEl.querySelector('.spinner-border');
                var statusText = badgeEl.querySelector('.status-text');
                spinner.classList.remove('d-none');
                fetch('/admin/posts/toggle-status/' + postId, {
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
                        if (data.status === 1) {
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
