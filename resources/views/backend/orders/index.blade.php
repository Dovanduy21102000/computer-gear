<main id="main" class="main">
    @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif
      <div class="pagetitle">
        <h1>Danh sách đơn hàng</h1>
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
                      <th>ID đơn</th>
                      <th>Tên người nhận</th>
                      <th>Địa chỉ giao hàng</th>
                      <th>Tổng giá trị</th>
                      <th>Giảm giá</th>
                      <th>Giá trị cuối cùng</th>
                      <th>Trạng thái</th>
                      <th>Trạng thái thanh toán</th>
                      <th>Hết hạn</th>
                      <th>Ngày tạo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orders as $order)
                    <tr>
                      <td>{{ $order->id }}</td>
                      <td>{{ $order->shipping_user_name }}</td>
                      <td>{{ $order->shipping_address }}</td>
                      <td>{{ $order->total_price}}</td>
                      <td>{{ number_format( $order->coupon_discount, 2) }}</td>
                      <td>{{ number_format( $order->final_price, 2) }}</td>
                      <td>
                        <span class="badge 
                          {{ $order->status === 'pending' ? 'bg-warning' : 
                            ($order->status === 'processing' ? 'bg-primary' : 
                              ($order->status === 'delivered' ? 'bg-success' : 
                                ($order->status === 'completed' ? 'bg-info' : 
                                  ($order->status === 'canceled' ? 'bg-danger' : ''))
                              ))
                          }}">
                          {{ 
                            $order->status === 'pending' ? 'Đang chờ xử lý' : 
                            ($order->status === 'processing' ? 'Đang xử lý' : 
                              ($order->status === 'delivered' ? 'Đang giao hàng' : 
                                ($order->status === 'completed' ? 'Hoàn thành' : 
                                  ($order->status === 'canceled' ? 'Hủy đơn' : ''))
                              ))
                          }}
                        </span>
                      </td>
                      <td>
                        <span class="badge 
                          {{ $order->payment_method === 'momo' ? 'bg-success' : 
                            ($order->payment_method === 'cash' ? 'bg-danger' : 
                              ($order->payment_method === 'vn_pay' ? 'bg-info' : ''))
                          }}">
                          {{ $order->payment_method === 'momo' ? 'Momo' : 
                            ($order->payment_method === 'cash' ? 'Thanh toán khi nhận hàng' : 
                              ($order->payment_method === 'vn_pay' ? 'VN Pay' : ''))
                          }}
                        </span>
                      </td>
                      <td>{{ $order->created_at ? $order->created_at->format('d-m-Y') : 'Không' }}</td>
                      <td>
                          <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
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