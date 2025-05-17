<form id="resumeVnpayForm" action="{{ route('vnpay.create') }}" method="POST">
    @csrf
    @if (session('vnpay_selected_items'))
        @foreach (session('vnpay_selected_items') as $itemId)
            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
        @endforeach
    @endif
</form>
<script>
    document.getElementById('resumeVnpayForm').submit();
</script>
