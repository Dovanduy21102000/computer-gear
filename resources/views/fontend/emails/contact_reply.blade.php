<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi liên hệ</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer strong {
            color: #4CAF50;
        }
        .email-body p {
            margin: 10px 0;
        }
        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>Cảm ơn bạn đã liên hệ với Computer Gear Shop</h1>
        </div>
        <div class="email-body">
            <p>Xin chào {{ $name }},</p>
            <p>Cảm ơn bạn đã liên hệ với <strong>Computer Gear Shop</strong>. Chúng tôi đã nhận được yêu cầu của bạn và sẽ phản hồi lại sớm nhất có thể.</p>
            <p>Chúc bạn một ngày tốt lành!</p>
        </div>
        <div class="email-footer">
            <p>Trân trọng,</p>
            <p><strong>Computer Gear Shop</strong></p>
            <p><a href="mailto:hiencoi250404@gmail.com">Liên hệ với chúng tôi</a></p>
        </div>
    </div>
</body>
</html>
