<!DOCTYPE html>
<html>
<head>
    <title>Department Official Account Created</title>
</head>
<body>
    <p>Dear {{ $official_name }},</p>
    <p>Congratulations! Your account as a department official has been successfully created in our system.</p>
    <p><strong>Role:</strong> {{ $role }}</p>
    <p><strong>Email:</strong> {{ $official_email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please log in and change your password after your first login for security purposes.</p>
    <p>Thank you for joining us!</p>
    <p>Best Regards,</p>
    <p>The Department Management Team</p>
</body>
</html>
