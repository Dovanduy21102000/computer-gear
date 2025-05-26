<form id="resumeMomoForm" action="{{ route('momo.create') }}" method="POST">
    @csrf
    <input type="hidden" name="order_code" value="{{ $paymentAttempt->order_code }}">
    @if (session('momo_selected_items'))
        @foreach (session('momo_selected_items') as $itemId)
            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
        @endforeach
    @endif
    @if (session('shipping_info'))
        <input type="hidden" name="shipping_user_name" value="{{ session('shipping_info.shipping_user_name') }}">
        <input type="hidden" name="shipping_email" value="{{ session('shipping_info.shipping_email') }}">
        <input type="hidden" name="shipping_phone" value="{{ session('shipping_info.shipping_phone') }}">
        <input type="hidden" name="shipping_address" value="{{ session('shipping_info.shipping_address') }}">
        <input type="hidden" name="province_id" value="{{ session('shipping_info.province_id') }}">
        <input type="hidden" name="district_id" value="{{ session('shipping_info.district_id') }}">
        <input type="hidden" name="notes" value="{{ session('shipping_info.notes') }}">
    @endif
</form>
<script>
    document.getElementById('resumeMomoForm').submit();
</script>
