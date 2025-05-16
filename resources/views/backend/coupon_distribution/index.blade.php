<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý phân phối mã giảm giá</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý phân phối mã giảm giá</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên mã giảm giá</th>
                                        <th>Mã giảm giá</th>
                                        <th>Loại giảm giá</th>
                                        <th>Giá trị giảm</th>
                                        <th>Số lượng</th>
                                        <th>Đã sử dụng</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon->id }}</td>
                                            <td>{{ $coupon->name }}</td>
                                            <td>{{ $coupon->code }}</td>
                                            <td>{{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                                            <td>{{ $coupon->type == 'percent' ? $coupon->price . '%' : number_format($coupon->price) . 'đ' }}
                                            </td>
                                            <td>{{ $coupon->quantity }}</td>
                                            <td>{{ $coupon->used_count }}</td>
                                            <td>
                                                <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $coupon->status ? 'Hoạt động' : 'Ngừng' }}
                                                </span>
                                            </td>
                                            <td>{{ $coupon->expire_date ? date('d/m/Y', strtotime($coupon->expire_date)) : 'Không có' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('coupon-distribution.show', $coupon->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-people"></i> Phân phối
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
