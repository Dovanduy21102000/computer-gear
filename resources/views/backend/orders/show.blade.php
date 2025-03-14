<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết đơn hàng</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin đơn hàng</h5>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">ID Đơn Hàng</label>
                            <div class="col-sm-10">{{ $order->id }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Người mua</label>
                            <div class="col-sm-10">{{ $order->user->name ?? 'N/A' }}</div>
                        </div>

                        <h5 class="card-title">Thông tin giao hàng</h5>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tên người nhận</label>
                            <div class="col-sm-10">{{ $order->shipping_user_name }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">{{ $order->shipping_email }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Số điện thoại</label>
                            <div class="col-sm-10">{{ $order->shipping_phone }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Địa chỉ</label>
                            <div class="col-sm-10">{{ $order->shipping_address }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tỉnh/Thành phố</label>
                            <div class="col-sm-10">{{ $order->shipping_city }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Quận/Huyện</label>
                            <div class="col-sm-10">{{ $order->shipping_province }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Địa chỉ cụ thể</label>
                            <div class="col-sm-10">{{ $order->specific_address }}</div>
                        </div>

                        <h5 class="card-title">Thanh toán</h5>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Mã giảm giá</label>
                            <div class="col-sm-10">{{ $order->coupon_code ?? 'Không có' }}</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tổng giá trị đơn hàng</label>
                            <div class="col-sm-10">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tổng tiền thanh toán</label>
                            <div class="col-sm-10">{{ number_format($order->final_price, 0, ',', '.') }} VNĐ</div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Trạng thái thanh toán</label>
                            <div class="col-sm-10">
                                <span
                                    class="badge {{ $order->payment_status == '1' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $order->payment_status == '1' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Trạng thái đơn hàng</label>
                            <div class="col-sm-10">
                                @php
                                    $statuses = [
                                        'pending' => ['label' => 'Chờ xử lý', 'class' => 'badge bg-warning text-dark'],
                                        'processing' => ['label' => 'Đang xử lý', 'class' => 'badge bg-primary'],
                                        'completed' => ['label' => 'Hoàn thành', 'class' => 'badge bg-success'],
                                        'canceled' => ['label' => 'Đã hủy', 'class' => 'badge bg-danger'],
                                    ];
                                    $status = $statuses[$order->status] ?? [
                                        'label' => 'Không xác định',
                                        'class' => 'badge bg-secondary',
                                    ];
                                @endphp
                                <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Phương thức thanh toán</label>
                            <div class="col-sm-10">
                                @php
                                    $paymentMethods = [
                                        'cash' => ['label' => 'Tiền mặt', 'class' => 'badge bg-secondary'],
                                        'vn_pay' => ['label' => 'VN Pay', 'class' => 'badge bg-primary'],
                                        'momo' => ['label' => 'Momo', 'class' => 'badge bg-danger'],
                                    ];
                                    $payment = $paymentMethods[$order->payment_method] ?? [
                                        'label' => 'Không xác định',
                                        'class' => 'badge bg-dark',
                                    ];
                                @endphp
                                <span class="{{ $payment['class'] }}">{{ $payment['label'] }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Ghi chú</label>
                            <div class="col-sm-10">{{ $order->notes ?? 'Không có ghi chú' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
