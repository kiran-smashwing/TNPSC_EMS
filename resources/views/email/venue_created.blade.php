<!DOCTYPE html>
<html>
<head>
    <title>Venue Account Created</title>
</head>
<body>
    <p>Dear {{ $venue_name }},</p>
    <p>Congratulations! Your venue account has been successfully created in our system.</p>
    <p><strong>Email:</strong> {{ $venue_email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please log in and change your password after your first login for security purposes.</p>
    <p>Thank you for joining us!</p>
    <p>Best Regards,</p>
    <p>The Venue Management Team</p>
</body>
</html>
