<h2>Xin chào {{ $order->shipping_user_name }},</h2>

@if ($approved)
    <p>Yêu cầu huỷ đơn hàng <strong>#{{ $order->code }}</strong> của bạn đã được <strong>chấp thuận</strong>.</p>
    <p>Đơn hàng đã được huỷ vào lúc {{ $order->updated_at->format('d/m/Y H:i') }}.</p>
@else
    <p>Yêu cầu huỷ đơn hàng <strong>#{{ $order->code }}</strong> của bạn đã bị <strong>từ chối</strong>.</p>
    <p>Đơn hàng hiện vẫn đang được xử lý và chuẩn bị giao đến bạn.</p>
@endif

<p>Trân trọng,<br>Đội ngũ hỗ trợ khách hàng</p>
