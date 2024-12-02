<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>மறந்துபோன கடவுச்சொல்</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #eaf6f2;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #555555;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2ca87f;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('storage/assets/images/logo-dark.png') }}" alt="Header Image">
        </div>

        <!-- Content -->
        <div class="content">
            <h2>வணக்கம் {{ $name }},</h2>
            <p>உங்கள் கணக்கு வெற்றிகரமாக உருவாக்கப்பட்டது. தமிழ்நாடு அரசு பணியாளர் தேர்வாணையத்திற்கு உங்கள் சேவையை
                சிறந்த முறையில் வழங்க கேட்டுக்கொள்கிறோம்</p>
                <p>கீழே உங்கள் உள்ளீட்டு கணக்கு விவரங்கள்:</p>
                <ul>
                    <strong>மின்னஞ்சல்: </strong>{{ $email }}
                    <strong>கடவுச்சொல்:</strong>{{ $password }}
                </ul>
            <a href="{{ url('/login') }}" class="button">உள்நுழைக</a>
        </div>
        <div>
            <p>நன்றி,</p>
            <p>உங்கள் <strong>சேவை குழு</strong></p>
            <p>தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</p>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>Copyright © {{ date('Y') }} TNPSC. Developed By Smashwing Technologies Pvt Ltd. All rights reserved.</p>

        </div>
    </div>
</body>

</html>
