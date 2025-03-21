<!DOCTYPE html>
<html>

<head>
    <base href="{{ config('app.url') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ComputerGear Admin | Đăng nhập</title>

    <link href="auth/css/bootstrap.min.css" rel="stylesheet">
    <link href="auth/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="auth/css/animate.css" rel="stylesheet">
    <link href="auth/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <h3>Chào mừng đến với Computer Gear</h3>
            <p>Đăng nhập để vào trang quản trị</p>

            <form class="m-t" role="form" action="{{ route('auth.login') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="Nhập email..." value="{{old('email')}}">
                    @if ($errors->has('email'))
                        <span class="error-message">!{{$errors->first('email')}}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu...">
                    @if ($errors->has('password'))
                        <span class="error-message">!{{$errors->first('password')}}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Đăng nhập</button>

                <a href="#"><small>Quên mật khẩu?</small></a>
                <p class="text-muted text-center"><small>Bạn chưa có tài khoản?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.html">Tạo một tài khoản</a>
            </form>
            <p class="m-t"> <small>Uy tín- Chất lượng- Hiện đại</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="auth/js/jquery-3.1.1.min.js"></script>
    <script src="auth/js/bootstrap.min.js"></script>

</body>

</html>
