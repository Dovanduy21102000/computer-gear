<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>

<body onload="document.getElementById('paymentForm').submit();">
    <form id="paymentForm" action="{{ $url }}" method="POST">
        @csrf
        @foreach ($data as $key => $value)
            @if (is_array($value))
                @foreach ($value as $subKey => $subValue)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        @if (session()->has('coupon'))
            <input type="hidden" name="coupon" value="{{ json_encode(session('coupon')) }}">
        @endif
    </form>
    <p>Redirecting...</p>
</body>

</html>
