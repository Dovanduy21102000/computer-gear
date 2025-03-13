<main id="main" class="main">
    <div class="pagetitle">
      <h1>Chi tiết khuyến mại</h1>
      <nav>
        <ol class="breadcrumb">
          
          <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">Danh sách khuyến mại</a></li>
          <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Thông tin khuyến mại</h5>

              <table class="table table-bordered">
                <tr>
                  <th>ID</th>
                  <td>{{ $coupon->id }}</td>
                </tr>
                <tr>
                  <th>Tên</th>
                  <td>{{ $coupon->name }}</td>
                </tr>
                <tr>
                  <th>Mã giảm giá</th>
                  <td>{{ $coupon->code }}</td>
                </tr>
                <tr>
                  <th>Loại giảm giá</th>
                  <td>{{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                </tr>
                <tr>
                  <th>Giá trị</th>
                  <td>{{ number_format($coupon->price, 2) }}</td>
                </tr>
                <tr>
                  <th>Giá trị tối đa</th>
                  <td>{{ number_format($coupon->maximum_amount, 2) }}</td>
                </tr>
                <tr>
                  <th>Tổng đơn tối thiểu</th>
                  <td>{{ number_format($coupon->min_order_total, 2) }}</td>
                </tr>
                <tr>
                  <th>Số lần sử dụng tối đa</th>
                  <td>{{ $coupon->max_uses }}</td>
                </tr>
                <tr>
                  <th>Đã sử dụng</th>
                  <td>{{ $coupon->used_count }}</td>
                </tr>
                <tr>
                  <th>Trạng thái</th>
                  <td>
                    <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                      {{ $coupon->status ? 'Hoạt động' : 'Ngừng' }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <th>Ngày hết hạn</th>
                  <td>{{ $coupon->expire_date ? date('d-m-Y', strtotime($coupon->expire_date)) : 'Không' }}</td>
                </tr>
              </table>

              <a href="{{ route('coupons.index') }}" class="btn btn-primary">Quay lại</a>
            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->
