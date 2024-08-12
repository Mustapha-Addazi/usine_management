<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Hello, {{ $fullname }}</h1>
    <p>You have requested to reset your password. Please click the link below to reset your password:</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
