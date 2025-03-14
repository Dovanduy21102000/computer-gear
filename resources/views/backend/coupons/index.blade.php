<main id="main" class="main">
  @if (session('success'))
    
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="pagetitle">
      <h1>Danh sách khuyến mại</h1>
      <a class="mt-5" href="{{ route('coupons.create') }}"><button type="button" class="btn btn-primary">Thêm mã khuyến mại</button></a>
      
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
                        <a href="{{ route('coupons.show', $coupon->id) }}" ><button type="button" class="btn btn-success"><i class="bi bi-eye"></i></button></a>
                      <a href="{{ route('coupons.edit', $coupon->id) }}"><button type="button" class="btn btn-warning"><i class="bi bi-wrench"></i></button></a>
                      <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger " onclick="return confirm('Xóa khuyến mãi này?')"><i class="bi bi-trash-fill"></i></button>
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
