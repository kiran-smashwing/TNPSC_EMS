<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>உங்கள் கணக்கு வெற்றிகரமாக உருவாக்கப்பட்டது</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            line-height: 1.6;
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
            max-width: 200px;
            height: auto;
        }

        .content {
            padding: 20px;
            text-align: left;
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
            margin: 15px 0;
        }

        .credentials {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/assets/images/logo-dark.png') }}" alt="TNPSC Official Logo" title="TNPSC Logo">
        </div>

        <div class="content">
            <h2>வணக்கம் {{ htmlspecialchars($name) }},</h2>
            
            <p>உங்கள் கணக்கு வெற்றிகரமாக உருவாக்கப்பட்டது. தமிழ்நாடு அரசு பணியாளர் தேர்வாணையத்திற்கு உங்கள் சேவையை சிறந்த முறையில் வழங்க கேட்டுக்கொள்கிறோம்.</p>

            <div class="credentials">
                <p><strong>மின்னஞ்சல்:</strong> {{ htmlspecialchars($email) }}<br>
                <strong>கடவுச்சொல்:</strong> {{ htmlspecialchars($password) }}</p>
            </div>

            <p>தயவுசெய்து உங்கள் கணக்கு விவரங்களை சரிபார்க்கவும். ஏதேனும் தவறு இருந்தால், உடனடியாக புதுப்பிக்கவும்.</p>

            <a href="#" class="button">TNPSC EMS செயலி பதிவிறக்கம்</a>

            <h3>பொது அறிவுறுத்தல்கள்:</h3>
            <ul>
                <li>சரியான நேரத்தில் பரிசோதனையை தொடங்கவும்.</li>
                <li>பரிசோதனையின் அனைத்து சாகசங்களை முழுமையாக கண்காணிக்கவும்.</li>
                <li>எந்த தொழில்நுட்ป பிரச்சனைகளும் ஏற்படாதபடி உறுதிப்படுத்தவும்.</li>
            </ul>
        </div>

        <div class="content">
            <p>நன்றி,</p>
            <p>உங்கள் <strong>சேவை குழு</strong></p>
            <p>தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</p>
        </div>

        <div class="footer">
            <p>Copyright © {{ date('Y') }} TNPSC. Developed By Smashwing Technologies Pvt Ltd. All rights reserved.</p>
        </div>
    </div>
</body>
</html>