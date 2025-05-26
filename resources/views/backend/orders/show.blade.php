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
                            <label class="col-sm-2 col-form-label">Mã đơn Hàng</label>
                            <div class="col-sm-10">{{ $order->code }}</div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Người mua</label>
                            <div class="col-sm-10">{{ $order->user->name ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <h5 class="card-title">Thông tin giao hàng</h5>
                                <div class="mb-3"><strong>Tên người nhận:</strong> {{ $order->shipping_user_name }}
                                </div>
                                <div class="mb-3"><strong>Email:</strong> {{ $order->shipping_email }}</div>
                                <div class="mb-3"><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</div>
                                <div class="mb-3"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</div>
                                <div class="mb-3"><strong>Tỉnh/Thành phố:</strong>
                                    {{ $provinceName ?? 'Không xác định' }}</div>
                                <div class="mb-3"><strong>Quận/Huyện:</strong> {{ $districtName ?? 'Không xác định' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Thanh toán</h5>
                                <div class="mb-3"><strong>Mã giảm giá:</strong>
                                    {{ $order->coupon_code ?? 'Không có' }}</div>
                                <div class="mb-3"><strong>Tổng giá trị đơn hàng:</strong>
                                    {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</div>
                                <div class="mb-3"><strong>Tổng tiền thanh toán:</strong>
                                    {{ number_format($order->final_price, 0, ',', '.') }} VNĐ</div>
                                <div class="mb-3"><strong>Trạng thái thanh toán:</strong>
                                    <span
                                        class="badge {{ $order->payment_status == '1' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $order->payment_status == '1' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                    </span>
                                </div>
                                <div class="mb-3"><strong>Trạng thái đơn hàng:</strong>
                                    @php
                                        $statuses = [
                                            'pending' => [
                                                'label' => 'Chờ xử lý',
                                                'class' => 'badge bg-warning text-dark',
                                            ],
                                            'processing' => ['label' => 'Đang xử lý', 'class' => 'badge bg-primary'],
                                            'delivered' => ['label' => 'Đang giao', 'class' => 'badge bg-info'],
                                            'completed' => ['label' => 'Đã giao', 'class' => 'badge bg-success'],
                                            'success' => ['label' => 'Hoàn thành', 'class' => 'badge bg-success'],
                                            'canceled' => ['label' => 'Đã hủy', 'class' => 'badge bg-danger'],
                                        ];
                                        $status = $statuses[$order->status] ?? [
                                            'label' => 'Không xác định',
                                            'class' => 'badge bg-secondary',
                                        ];
                                    @endphp
                                    <span class="{{ $status['class'] }}">{{ $status['label'] }}</span>
                                </div>
                                <div class="mb-3"><strong>Phương thức thanh toán:</strong>
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
                                <div class="mb-3"><strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có ghi chú' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách sản phẩm -->
        <div class="row mt-1">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách sản phẩm</h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Ảnh</th>
                                    <th>Thông số</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                alt="{{ $item->product->name }}" class="img-fluid"
                                                style="max-width: 100px;">
                                        </td>
                                        <td>
                                            @if ($item->productVariant)
                                                <strong>{{ $item->productVariant->sku }}</strong> <br>
                                                <span class="text-muted">
                                                    @foreach ($item->productVariant->attributeValues as $attr)
                                                        {{ $attr->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </span>
                                            @else
                                                {{ $item->product->name }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">
                                            @if ($item->price_sale)
                                                <span class="text-danger" style="text-decoration: line-through;">
                                                    {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                                </span>
                                                <br>
                                                <span class="text-success">
                                                    {{ number_format($item->price_sale, 0, ',', '.') }} VNĐ
                                                </span>
                                            @else
                                                {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>
</main>
