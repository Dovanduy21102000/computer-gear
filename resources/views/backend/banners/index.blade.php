<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="pagetitle">
        <h1>Quản lý banner</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý banners</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách banners</h5>
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-top">
                                <div>
                                    <a class="btn btn-primary" href="{{ route('banners.create') }}">Thêm mới</a>
                                </div>
                            </div>
                            <div class="datatable-container">
                                <!-- Table with stripped rows -->
                                <table class="table datatable table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                             <th class="text-center">Tiêu đề</th>
                                             <th class="text-center">Ảnh</th>

                                             <th class="text-center">Trạng thái</th>
                                             <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banners as $banner)
                                            <tr>
                                                <td class="text-center">{{ $banner->id }}</td>
                                                <td class="text-center">{{ $banner->title }}</td>
                                                <td class="text-center">
                                                    @if ($banner->image)
                                                        <img src="{{ Storage::url($banner->image) }}" width="100px">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $banner->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $banner->status ? 'Hoạt động' : 'Ngừng' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('banners.show', $banner->id) }}"><button
                                                            type="button" class="btn btn-success"><i
                                                                class="bi bi-eye"></i></button></a>
                                                    <a href="{{ route('banners.edit', $banner->id) }}"><button
                                                            type="button" class="btn btn-warning"><i
                                                                class="bi bi-wrench"></i></button></a>
                                                    <form action="{{ route('banners.destroy', $banner->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Xóa quảng cáo này?')"><i
                                                                class="bi bi-trash-fill"></i></button>
                                                    </form>

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- End Table -->
                            </div>
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
