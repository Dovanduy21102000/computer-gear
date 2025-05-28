<div class="container mb-5 mt-4">
    <h3 class="mb-4 font-weight-bold text-dark">Đơn hàng của bạn</h3>
    <!-- Tabs trạng thái -->
    <ul class="nav nav-tabs mb-3 order-tabs-custom" id="orderTabs" role="tablist">
        @foreach ($orderTabs as $key => $label)
            <li class="nav-item" role="presentation">
                <a class="nav-link @if ($loop->first) active @endif" id="{{ $key }}-tab" data-toggle="tab" href="#{{ $key }}" role="tab">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
    <!-- Ô tìm kiếm -->
    <form method="get" class="mb-4">
        <input type="text" class="form-control" name="q" value="{{ $search ?? '' }}" placeholder="Tìm kiếm theo tên sản phẩm, mã đơn...">
    </form>
    <!-- Danh sách đơn hàng -->
    <div class="tab-content" id="orderTabsContent">
        @foreach ($orderTabs as $statusKey => $statusLabel)
            @if(isset($ordersByStatus[$statusKey]) && $ordersByStatus[$statusKey])
                <div class="tab-pane fade @if ($loop->first) show active @endif" id="{{ $statusKey }}" role="tabpanel">
                    @if ($ordersByStatus[$statusKey]->count())
                        @foreach ($ordersByStatus[$statusKey] as $order)
                            <div class="order-card card mb-4 shadow-sm rounded-lg">
                                <div class="card-body">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <span class="font-weight-bold">Ngày mua: </span>{{ $order->created_at->format('d/m/Y') }}
                                            <span class="ml-3 font-weight-bold">Mã đơn hàng: </span><span class="text-primary">{{ $order->code }}</span>
                                            <span class="ml-3 text-muted">{{ $order->shipping_user_name }} | {{ $order->shipping_address }}</span>
                                        </div>
                                        <div class="order-status">
                                            @php
                                                if ($order->status == 'completed') {
                                                    $badge = 'secondary';
                                                } elseif ($order->status == 'pending') {
                                                    $badge = 'warning';
                                                } elseif ($order->status == 'canceled') {
                                                    $badge = 'danger';
                                                } else {
                                                    $badge = 'secondary';
                                                }
                @endphp
                                            <span class="badge badge-{{ $badge }} px-3 py-2" style="font-size:1rem;">
                                                {{ $orderTabs[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="order-products mb-3">
                                        @foreach ($order->items ?? [] as $item)
                                            <div class="order-product-item">
                                                <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/56' }}" alt="{{ $item->product->name }}" class="order-product-img">
                                                <div class="flex-grow-1">
                                                    <div class="order-product-name">{{ $item->product->name }}</div>
                                                    <div class="order-product-qty">Số lượng: {{ $item->quantity }} @if(isset($item->variant)) | {{ $item->variant }} @endif</div>
                                                </div>
                                                <div class="order-product-price">{{ number_format($item->price, 0, ',', '.') }} ₫</div>
                                            </div>
                                @endforeach
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
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="order-actions mb-2 mb-md-0">
                                            
                                            
        
                                            <a href="{{ route('client.orders.show', $order->code) }}" class="btn btn-primary btn-sm"> Xem chi tiết</a>
                                        </div>
                                        <div class="order-total">Tổng: {{ number_format($order->final_price, 0, ',', '.') }} ₫</div>
                                    </div>
                                </div>
                    </div>
                        @endforeach
                @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p class="mb-0 h5">Không có đơn hàng {{ strtolower($statusLabel) }}.</p>
                    </div>
                @endif
            </div>
            @endif
        @endforeach
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
<style>
    .order-tabs-custom {
        border: none;
        background: #f8f9fa;
        border-radius: 12px;
        overflow-x: auto;
        white-space: nowrap;
        padding: 4px 0 4px 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .order-tabs-custom .nav-link {
        border: none;
        border-radius: 8px 8px 0 0;
        margin-right: 8px;
        color: #333;
        font-weight: 500;
        background: transparent;
        padding: 10px 24px;
        transition: background 0.2s, color 0.2s;
    }
    .order-tabs-custom .nav-link.active {
        background: #007bff;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.08);
    }
    .order-tabs-custom .nav-link:hover:not(.active) {
        background: #e9ecef;
        color: #007bff;
    }
    @media (max-width: 600px) {
        .order-tabs-custom .nav-link {
            padding: 8px 12px;
            font-size: 0.95rem;
        }
    }
    .order-card.card {
        border: 1.5px solid #e9ecef;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s, transform 0.2s;
        margin-bottom: 2rem;
        background: #fff;
    }
    .order-card.card:hover {
        box-shadow: 0 8px 24px rgba(0,123,255,0.10);
        transform: translateY(-2px) scale(1.01);
    }
    .order-card .card-body {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    .order-header .order-info {
        font-size: 1.05rem;
        color: #222;
    }
    .order-header .order-status {
        margin-top: 0.5rem;
    }
    .order-products {
        border-radius: 10px;
        background: #f8f9fa;
        padding: 1rem 1rem 0.5rem 1rem;
        margin-bottom: 1.2rem;
    }
    .order-product-item {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.7rem;
        margin-bottom: 0.7rem;
    }
    .order-product-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .order-product-img {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        object-fit: cover;
        border: 1.5px solid #e9ecef;
        margin-right: 1rem;
    }
    .order-product-name {
        font-weight: 600;
        color: #222;
    }
    .order-product-qty {
        font-size: 0.95rem;
        color: #888;
    }
    .order-product-price {
        font-weight: 600;
        color: #e74c3c;
        min-width: 110px;
        text-align: right;
    }
    .order-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .order-actions .btn {
        border-radius: 18px;
        font-size: 0.97rem;
        padding: 0.45rem 1.2rem;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    .order-actions .btn-outline-dark:hover {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .order-actions .btn-light:hover {
        background: #f8f9fa;
        color: #007bff;
    }
    .order-total {
        font-weight: bold;
        font-size: 1.25rem;
        color: #007bff;
        text-align: right;
    }
    .badge-success {background: #21aa48; color: #219653;}
    .badge-warning {background: #fff8e1; color: #ad9401;}
    .badge-danger {background: #fdecea; color: #eb5757;}
    .badge-secondary {background: #f2f2f2; color: #377eef;}
    .btn-outline-dark, .btn-light {border-radius: 20px;}
    @media (max-width: 600px) {
        .order-card .d-flex {flex-direction: column !important; align-items: flex-start !important;}
        .order-card .d-flex .mb-2 {margin-bottom: 0.5rem !important;}
        .order-card .d-flex .ml-3 {margin-left: 0 !important;}
    }
</style>


