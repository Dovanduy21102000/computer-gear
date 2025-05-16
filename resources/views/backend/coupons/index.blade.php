<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="pagetitle">
        <h1>Quản lý khuyến mãi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý khuyến mại</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách khuyến mại</h5>
                        <div class="d-flex gap-2">
                            <a class="btn btn-primary" href="{{ route('coupons.create') }}">Thêm mới</a>
                            <a class="btn btn-info" href="{{ route('coupon-distribution.index') }}">
                                <i class="bi bi-people"></i> Phân phối mã giảm giá
                            </a>
                        </div>
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            {{-- <div class="datatable-top">
                                <div>
                                    <a class="btn btn-primary" href="{{ route('coupons.create') }}">Thêm mới</a>
                                </div>
                            </div> --}}
                            <div class="datatable-container">
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Mã</th>
                                            <th>Loại</th>
                                            <th>Giá trị</th>
                                            <th>Giá trị tối đa</th>
                                            <th>Trạng thái</th>
                                            <th>Hết hạn</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td>{{ $coupon->id }}</td>
                                                <td>{{ $coupon->name }}</td>
                                                <td>{{ $coupon->code }}</td>
                                                <td>{{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                                                <td>{{ number_format($coupon->price, 2) }}</td>
                                                <td>{{ number_format($coupon->maximum_amount, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $coupon->status ? 'Hoạt động' : 'Ngừng' }}
                                                    </span>
                                                </td>
                                                <td>{{ $coupon->expire_date ? date('d-m-Y', strtotime($coupon->expire_date)) : 'Không' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('coupons.show', $coupon->id) }}"><button
                                                            type="button" class="btn btn-success"><i
                                                                class="bi bi-eye"></i></button></a>
                                                    <a href="{{ route('coupons.edit', $coupon->id) }}"><button
                                                            type="button" class="btn btn-warning"><i
                                                                class="bi bi-wrench"></i></button></a>
                                                    <form action="{{ route('coupons.destroy', $coupon->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger "
                                                            onclick="return confirm('Xóa khuyến mãi này?')"><i
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
