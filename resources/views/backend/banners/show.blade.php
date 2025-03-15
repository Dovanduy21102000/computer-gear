<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết Banner</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('banners.index') }}">Danh sách Banner</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Thông tin Banner</h5>

                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $banner->id }}</td>
                            </tr>
                            <tr>
                                <th>Tiêu đề</th>
                                <td>{{ $banner->title }}</td>
                            </tr>
                            <tr>
                                <th>Ảnh</th>
                                <td class="text-center">
                                    @if ($banner->image)
                                        <img src="{{ Storage::url($banner->image) }}" class="img-fluid rounded" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Đường dẫn</th>
                                <td>
                                    @if ($banner->btn_url)
                                        <a href="{{ $banner->btn_url }}" target="_blank" class="text-primary">{{ $banner->btn_url }}</a>
                                    @else
                                        <span class="text-muted">Chưa có đường dẫn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Mô tả</th>
                                <td><p class="mb-0">{{ $banner->description }}</p></td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <span class="badge {{ $banner->status ? 'badge bg-success' : 'badge bg-danger' }}">
                                        {{ $banner->status ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <a href="{{ route('banners.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left-circle"></i> Quay lại
                        </a>
                        <a href="{{ route('banners.edit', $banner->id) }}"
                            ><button type="button" class="btn btn-warning"><i class="bi bi-wrench"></i></button></a>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Xóa quảng cáo này?')"><i class="bi bi-trash-fill"></i></button>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
