<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Udin Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #8D5B4C;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #8D5B4C;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Udin Gallery</h1>
        </div>
        <div class="content">
            <h2>Reset Password</h2>
            <p>Halo {{ $user->nama_tampilan }},</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
            <p>Klik tombol di bawah ini untuk mereset password Anda:</p>
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>Link ini akan kedaluwarsa dalam 30 menit.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Udin Gallery. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
