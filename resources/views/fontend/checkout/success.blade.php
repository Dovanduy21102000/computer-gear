<!-- Order Success Page -->
<main id="content" role="main" class="checkout-success">
    <div class="container">
        <div class="mb-5 text-center">
            <h1 class="text-success"><i class="fas fa-check-circle"></i> Đặt hàng thành công!</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 box-shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="h4 mb-3">Cảm ơn bạn đã đặt hàng!</h2>
                            <p class="text-muted">Mã đơn hàng của bạn là: <strong>{{ $order->code }}</strong></p>
                        </div>

                        <div class="order-details mb-4">
                            <h3 class="h5 mb-3 border-bottom pb-2">Chi tiết đơn hàng</h3>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Trạng thái thanh toán:</strong>
                                </div>
                                <div class="col-sm-8">
                                    @if ($order->payment_status)
                                        <span class="badge badge-success">Đã thanh toán</span>
                                    @else
                                        <span class="badge badge-warning">Chưa thanh toán</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Phương thức thanh toán:</strong>
                                </div>
                                <div class="col-sm-8">
                                    @switch($order->payment_method)
                                        @case('momo')
                                            MoMo
                                        @break

                                        @case('vnpay')
                                            VNPay
                                        @break

                                        @default
                                            Thanh toán khi nhận hàng
                                    @endswitch
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Địa chỉ giao hàng:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $order->shipping_address }}
                                </div>
                            </div>
                        </div>

                        <div class="order-items mb-4">
                            <h3 class="h5 mb-3 border-bottom pb-2">Sản phẩm đã đặt</h3>

                            @foreach ($order->orderItems as $item)
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-8">
                                        <h4 class="h6 mb-0">{{ $item->product->name }}</h4>
                                        @if ($item->productVariant)
                                            <small class="text-muted">
                                                @php
                                                    $attributes = [];
                                                    foreach ($item->productVariant->attributeValues as $value) {
                                                        if (isset($value->attribute)) {
                                                            $attributes[] =
                                                                $value->attribute->name . ': ' . $value->value;
                                                        }
                                                    }
                                                    echo implode(' | ', $attributes);
                                                @endphp
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        × {{ $item->quantity }}
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-top pt-3 mt-3">
                                <div class="row justify-content-end">
                                    <div class="col-sm-6">
                                        <div class="row mb-2">
                                            <div class="col-sm-6 text-right">
                                                <strong>Tổng cộng:</strong>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                {{ number_format($order->total_price, 0, ',', '.') }}₫
                                            </div>
                                        </div>

                                        @if ($order->coupon_discount > 0)
                                            <div class="row mb-2">
                                                <div class="col-sm-6 text-right">
                                                    <strong>Giảm giá:</strong>
                                                </div>
                                                <div class="col-sm-6 text-right text-danger">
                                                    -{{ number_format($order->coupon_discount, 0, ',', '.') }}₫
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-sm-6 text-right">
                                                <strong>Thành tiền:</strong>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <strong
                                                    class="text-primary">{{ number_format($order->final_price, 0, ',', '.') }}₫</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('home.index') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
                            <a href="{{ route('order.track') }}" class="btn btn-outline-primary ml-2">Theo dõi đơn
                                hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
