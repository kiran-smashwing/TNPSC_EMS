<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <p>Dear {{ $name }},</p>
    <p>Thank you for registering. Please verify your email address by clicking the link below:</p>
    <p>
        <a href="{{ $verification_link }}" target="_blank">Verify Email</a>
    </p>
    <p>If you did not register, please ignore this email.</p>
    <p>Best Regards,</p>
    <p>The Team</p>
</body>
</html>
