@extends('fontend.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Thanh toán đang chờ xử lý</h4>
                    </div>
                    <div class="card-body">
                        @if ($pendingPayments->isEmpty())
                            <div class="text-center py-4">
                                <p class="mb-0">Không có thanh toán nào đang chờ xử lý.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Phương thức</th>
                                            <th>Số tiền</th>
                                            <th>Thời gian</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->order_code }}</td>
                                                <td>
                                                    @if ($payment->payment_method === 'momo')
                                                        <span class="badge bg-primary">MoMo</span>
                                                    @else
                                                        <span class="badge bg-success">VNPay</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($payment->amount) }} VNĐ</td>
                                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('payment.resume.process', $payment->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            Tiếp tục
                                                        </a>
                                                        <a href="{{ route('payment.resume.cancel', $payment->id) }}"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Bạn có chắc muốn hủy thanh toán này?')">
                                                            Hủy
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
