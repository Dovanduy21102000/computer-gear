<form id="resumeMomoForm" action="{{ route('momo.create') }}" method="POST">
    @csrf
    @if (session('momo_selected_items'))
        @foreach (session('momo_selected_items') as $itemId)
            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
        @endforeach
    @endif
</form>
<script>
    document.getElementById('resumeMomoForm').submit();
</script>
