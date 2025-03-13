<main id="main" class="main">
  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="pagetitle">
      <h1>Danh sách khuyến mại</h1>
      <nav>
        <ol class="breadcrumb">
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              
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
                  @foreach($coupons as $coupon)
                  <tr>
                    <td>{{ $coupon->id }}</td>
                    <td>{{ $coupon->name }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                    <td>{{ number_format($coupon->price, 2) }}</td>
                    <td>{{ number_format($coupon->maximum_amount, 2) }}</td>
                    <td>
                      <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $coupon->status ? 'Hoạt động' : 'Ngừng' }}
                      </span>
                    </td>
                    <td>{{ $coupon->expire_date ? date('d-m-Y', strtotime($coupon->expire_date)) : 'Không' }}</td>
                    <td>
                        <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-info btn-sm">Xem</a>
                      <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                      <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa khuyến mãi này?')">Xóa</button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Table -->
            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->
