<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background: linear-gradient(45deg, #2575fc, #6a11cb);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset</h2>
        <p>You have requested to reset your password. Your verification code is:</p>
        <p><strong>{{ $verificationCode }}</strong></p>
        <p>Click the button below to reset your password:</p>
        <p>
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you,<br>The App Team</p>
    </div>
</body>
</html>
