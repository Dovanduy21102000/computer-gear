<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>

<body onload="document.getElementById('redirect-form').submit();">
    <form id="redirect-form" action="{{ $url }}" method="POST">
        @csrf
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <p>Redirecting...</p>
</body>

</html>
