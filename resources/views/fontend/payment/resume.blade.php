<div class="container mb-5 mt-4">
    <h3 class="mb-4 font-weight-bold text-dark">💳 Thanh toán đang chờ xử lý</h3>

    <!-- Tabs -->
    @php
        $resumeTabs = [
            'pending' => 'Đang chờ',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'expired' => 'Đã hết hạn',
        ];
    @endphp
    <ul class="nav nav-tabs nav-justified border-0 rounded shadow-sm mb-3 bg-white overflow-auto" id="resumeTabs"
        role="tablist" style="white-space: nowrap;">
        @foreach ($resumeTabs as $key => $label)
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
    <div class="tab-content bg-white shadow-sm p-4 rounded border" id="resumeTabsContent">
        @foreach ($resumeTabs as $statusKey => $statusLabel)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="{{ $statusKey }}"
                role="tabpanel">
                @php
                    if ($statusKey === 'expired') {
                        $paymentsByStatus = $allPayments->where('status', 'pending')->where('is_expired', true);
                    } elseif ($statusKey === 'pending') {
                        $paymentsByStatus = $allPayments->where('status', 'pending')->where(function ($item) {
                            return empty($item->is_expired) || !$item->is_expired;
                        });
                    } else {
                        $paymentsByStatus = $allPayments->where('status', $statusKey);
                    }
                @endphp
                @if ($paymentsByStatus->count())
                    <div class="table-responsive">
                        <table class="table table-hover text-center align-middle">
                            <thead class="thead-light">
                                <tr class="bg-light">
                                    <th class="text-uppercase small">Mã đơn hàng</th>
                                    <th class="text-uppercase small">Phương thức</th>
                                    <th class="text-uppercase small">Số tiền</th>
                                    <th class="text-uppercase small">Thời gian</th>
                                    <th class="text-uppercase small">Trạng thái</th>
                                    <th class="text-uppercase small"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentsByStatus as $payment)
                                    <tr>
                                        <td class="font-weight-bold align-middle">{{ $payment->order_code }}</td>
                                        <td class="align-middle">
                                            @if ($payment->payment_method === 'momo')
                                                <span class="badge bg-primary">MoMo</span>
                                            @else
                                                <span class="badge bg-success">VNPay</span>
                                            @endif
                                        </td>
                                        <td class="text-danger align-middle">{{ number_format($payment->amount) }} VNĐ
                                        </td>
                                        <td class="align-middle">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="align-middle">
                                            @if ($payment->status === 'pending')
                                                @if (!empty($payment->is_expired) && $payment->is_expired)
                                                    <span class="badge bg-danger text-white badge-lg">Đã hết
                                                        hạn</span>
                                                @else
                                                    <span class="badge badge-pending text-white badge-lg">Đang chờ</span>
                                                @endif
                                            @elseif ($payment->status === 'cancelled')
                                                <span class="badge bg-danger text-white badge-lg">Đã hủy</span>
                                            @elseif ($payment->status === 'completed')
                                                <span class="badge bg-success text-white badge-lg">Hoàn thành</span>
                                            @else
                                                <span
                                                    class="badge bg-light text-dark badge-lg">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if ($payment->status === 'pending')
                                                @if (!empty($payment->is_expired) && $payment->is_expired)
                                                    <span class="text-muted">-</span>
                                                @else
                                                    <a href="{{ route('payment.resume.process', $payment->id) }}"
                                                        class="btn btn-success btn-sm me-2 rounded-0">
                                                        Tiếp tục
                                                    </a>
                                                    <a href="{{ route('payment.resume.cancel', $payment->id) }}"
                                                        class="btn btn-outline-danger btn-sm rounded-0"
                                                        onclick="return confirm('Bạn có chắc muốn hủy thanh toán này?')">
                                                        Hủy
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                        <p class="mb-0">Không có thanh toán nào {{ strtolower($statusLabel) }}.</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
    .badge-lg {
        font-size: 0.92rem;
        padding: 0.32em 0.9em;
        border-radius: 0.6em;
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    .badge-pending {
        background: #4a90e2 !important;
    }
</style>
