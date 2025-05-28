<div class="container my-5">
    <div class="mb-4">
        <h2 class="font-weight-bold text-dark">Chi tiết đơn hàng
            <span class="text-primary">#{{ $order->code }}</span>
        </h2>
        <p class="text-muted">🕒 Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Thông tin đơn hàng -->
    <div class="row">
        <!-- Trạng thái và thanh toán -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3 text-secondary font-weight-bold">📦 Trạng thái đơn hàng</h5>
                    @php
                        $statusColor = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'delivered' => 'primary',
                            'completed' => 'success',
                            'success' => 'success',
                            'cancelled' => 'danger',
                            'pending_cancel' => 'warning',
                        ];
                        $paymentText = match ($order->payment_method) {
                            'cash' => 'Thanh toán khi nhận hàng',
                            'momo' => 'Thanh toán qua MoMo',
                            'vn_pay' => 'Thanh toán qua VN PAY',
                            default => 'Chưa rõ',
                        };
                    @endphp
                    <span class="badge badge-{{ $statusColor[$order->status] ?? 'secondary' }} px-3 py-2">
                        @switch($order->status)
                            @case('pending')
                                Chờ xác nhận
                            @break

                            @case('processing')
                                Đang xử lý
                            @break

                            @case('delivered')
                                Đang giao
                            @break

                            @case('completed')
                                Đã giao
                            @break

                            @case('success')
                                Đã nhận hàng
                            @break

                            @case('pending_cancel')
                                Chờ duyệt hủy
                            @break

                            @case('canceled')
                                Đã huỷ
                            @break

                            @default
                                Không xác định
                        @endswitch
                    </span>

                    <hr>

                    <h5 class="mb-3 text-secondary font-weight-bold">🧾 Thông tin thanh toán</h5>
                    <p><strong>Phương thức:</strong> {{ $paymentText }}</p>
                    <p><strong>Tổng tiền:</strong> <span
                            class="text-danger h5">{{ number_format($order->final_price, 0, ',', '.') }}₫</span></p>

                    @if (in_array($order->status, ['pending', 'processing']))
                        <div id="cancel-section" class="mt-3">
                            <button class="btn btn-outline-danger" onclick="toggleCancelForm()">❌ Huỷ đơn hàng</button>

                            <form id="cancel-form" action="{{ route('client.orders.cancel', $order->code) }}"
                                method="POST" class="mt-3 d-none">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="cancel_reason"><strong>Lý do huỷ đơn hàng:</strong></label>
                                    <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" required
                                        placeholder="Nhập lý do..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Xác nhận huỷ</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="toggleCancelForm()">Huỷ
                                    bỏ</button>
                            </form>
                        </div>

                        <script>
                            function toggleCancelForm() {
                                const form = document.getElementById('cancel-form');
                                form.classList.toggle('d-none');
                            }
                        </script>
                    @endif

                    @if ($order->status === 'completed')
                        <form action="{{ route('client.orders.confirmReceived', $order->code) }}" method="POST"
                            class="mt-3">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">✅ Tôi đã nhận được hàng</button>
                        </form>

                        <div id="refuse-section" class="mt-3">
                            <button class="btn btn-outline-danger" onclick="toggleRefuseForm()">❌ Không nhận
                                hàng</button>

                            <form id="refuse-form" action="{{ route('client.orders.cancel', $order->code) }}"
                                method="POST" class="mt-3 d-none">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="refuse_reason"><strong>Lý do không nhận:</strong></label>
                                    <textarea name="cancel_reason" id="refuse_reason" class="form-control" rows="3" required
                                        placeholder="Nhập lý do..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Xác nhận</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="toggleRefuseForm()">Huỷ
                                    bỏ</button>
                            </form>
                        </div>

                        <script>
                            function toggleRefuseForm() {
                                const form = document.getElementById('refuse-form');
                                form.classList.toggle('d-none');
                            }
                        </script>
                    @endif


                </div>
            </div>
        </div>

        <!-- Địa chỉ giao hàng -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3 text-secondary font-weight-bold">🚚 Địa chỉ giao hàng</h5>
                    <p><strong>Họ tên:</strong> {{ $order->shipping_user_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4 text-secondary font-weight-bold">🛒 Sản phẩm đã đặt</h5>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    @php
                        $hasVariant = $order->items->contains(fn($item) => $item->product_variant_id !== null);
                        $colspan = $hasVariant ? 4 : 3;
                        $subtotal = 0;
                        foreach ($order->items as $item) {
                            $subtotal += $item->price * $item->quantity;
                        }
                    @endphp

                    <thead class="thead-light">
                        <tr>
                            <th>Sản phẩm</th>

                            @if ($hasVariant)
                                <th>Thông số</th>
                            @endif

                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <img src="{{ $item->productVariant?->thumbnail
                                        ? asset('storage/' . $item->productVariant->thumbnail)
                                        : ($item->product?->thumbnail
                                            ? asset('storage/' . $item->product->thumbnail)
                                            : asset('/images/no-image.png')) }}"
                                        alt="product image" width="60" class="rounded shadow-sm mr-3">

                                    <div class="text-left ml-2">
                                        <strong>
                                            <a href="{{ route('client.products.detail', $item->product->slug) }}"
                                                class="text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </strong>
                                    </div>

                                </td>
                                @if ($hasVariant)
                                    <td class="text-left">
                                        @if ($item->productVariant && $item->productVariant->attributeValues->isNotEmpty())
                                            <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                                                @foreach ($item->productVariant->attributeValues as $value)
                                                    <li class="mb-1">
                                                        <i class="fas fa-check-circle text-primary mr-1"></i>
                                                        <strong>{{ $value->attribute->name ?? '' }}:</strong>
                                                        {{ $value->value }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif





                                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-danger">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <!-- Order summary card below the table -->
            <div class="row justify-content-end mt-3">
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                                </li>
                                @if (!empty($order->coupon_code) && !empty($order->coupon_discount))
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>
                                            Giảm giá:
                                            <span class="badge bg-info text-dark ml-1">{{ $order->coupon_code }}</span>
                                        </span>
                                        <span
                                            class="text-danger">-{{ number_format($order->coupon_discount, 0, ',', '.') }}₫</span>
                                    </li>
                                @endif
                                <li class="d-flex justify-content-between mt-3 border-top pt-3">
                                    <span class="font-weight-bold" style="font-size: 1.1rem;">Tổng tiền:</span>
                                    <span
                                        class="text-danger h4 mb-0 font-weight-bold">{{ number_format($order->final_price, 0, ',', '.') }}₫</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
