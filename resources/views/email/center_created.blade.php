<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the Center Management Platform</title>
</head>
<body>
    <p>Dear {{ $name }},</p>
    <p>Your center account has been successfully created. Below are your login details:</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please log in to your account and change your password as soon as possible.</p>
    <p>Thank you,</p>
    <p>The Team</p>
</body>
</html>