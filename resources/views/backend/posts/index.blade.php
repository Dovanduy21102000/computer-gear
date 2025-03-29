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
                        <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary">Thêm mới</a>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table datatable table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Tiêu đề</th>
                                        <th style="width: 10%;">Slug</th>
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
                                                <div class="text-truncate"
                                                    title="{{ $category_post->firstWhere('id', $item->category_id)->name ?? 'Không có' }}">
                                                    {{ Str::limit($category_post->firstWhere('id', $item->category_id)->name ?? 'Không có', 20, '...') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" title="{{ $item->description }}">
                                                    {{ Str::limit($item->description, 50, '...') }}
                                                </div>
                                            </td>
                                            <td>{{ $item->view }}</td>
                                            <td>
                                                <span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->status ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
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
