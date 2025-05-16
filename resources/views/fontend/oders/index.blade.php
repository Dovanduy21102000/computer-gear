<div class="container mb-5 mt-4">
    <h3 class="mb-4 font-weight-bold text-dark">🧾 Đơn hàng của bạn</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-justified border-0 rounded shadow-sm mb-3 bg-white overflow-auto" id="orderTabs"
        role="tablist" style="white-space: nowrap;">
        @php
            $orderTabs = [
                'pending' => 'Chờ xác nhận',
                'processing' => 'Đang xử lý',
                'delivered' => 'Đang giao',
                'completed' => 'Đã giao',
                'success' => 'Đã nhận hàng',
                'canceled' => 'Đã huỷ',
                'pending_cancel' => 'Chờ duyệt',
            ];
        @endphp

        @foreach ($orderTabs as $key => $label)
            <li class="nav-item" role="presentation">
                <a class="nav-link px-4 py-2 @if ($loop->first) active @endif text-dark font-weight-medium"
                    id="{{ $key }}-tab" data-toggle="tab" href="#{{ $key }}" role="tab"
                    style="border-radius: 0.5rem;">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content bg-white shadow-sm p-4 rounded border" id="orderTabsContent">
        @foreach ($orderTabs as $statusKey => $statusLabel)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="{{ $statusKey }}"
                role="tabpanel">
                @php
                    $ordersByStatus = $orders->where('status', $statusKey);
                @endphp

                @if ($ordersByStatus->count())
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle">
                            <thead class="thead-light">
                                <tr class="bg-light">
                                    <th class="text-uppercase small">Mã đơn</th>
                                    <th class="text-uppercase small">Ngày đặt</th>
                                    <th class="text-uppercase small">Tổng tiền</th>
                                     @if ($statusKey === 'completed')
                                                <th class="text-uppercase small">Hàng động</th>
                                            
                                            @endif
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordersByStatus as $order)
                                    <tr>
                                        <td class=" font-weight-bold"><a
                                                href="{{ route('client.orders.show', $order->code) }}" class="text-dark">#{{ $order->code }}</a>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="text-danger">{{ number_format($order->final_price, 0, ',', '.') }}₫
                                        </td>
                                        <td>
                                            @if ($statusKey === 'completed')
                                                <form
                                                    action="{{ route('client.orders.confirmReceived', $order->code) }}"
                                                    method="POST" class="mt-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success">✅ Đã nhận
                                                        hàng</button>
                                                </form>
                                            
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p class="mb-0">Không có đơn hàng {{ strtolower($statusLabel) }}.</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
