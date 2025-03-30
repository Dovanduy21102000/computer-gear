<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ mới từ khách hàng</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333; text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Thông tin liên hệ mới</h2>

        <p><strong>Họ tên:</strong> {{ $contactData['name'] }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $contactData['email'] }}" style="color: #007bff;">{{ $contactData['email'] }}</a></p>
        <p><strong>Số điện thoại:</strong> {{ $contactData['phone'] ?? 'Không có' }}</p>
        <p><strong>Chủ đề:</strong> {{ $contactData['subject'] ?? 'Không có' }}</p>
        <p><strong>Nội dung:</strong></p>
        <div style="background: #f9f9f9; padding: 10px; border-left: 4px solid #007bff; border-radius: 5px;">
            <p>{{ $contactData['message'] }}</p>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('home.index') }}" style="background: #007bff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;">Quay lại website</a>
        </div>
    </div>

</body>
</html>

