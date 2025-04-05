<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intimation and Request for Consent – Venue Allocation for Upcoming TNPSC Examination</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Poppins', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0 !important;
            padding: 0 !important;
        }

        .email-wrapper {
            max-width: 650px;
            margin: 0 auto !important;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #2ca87f 0%, #26967c 100%);
            padding: 25px 30px;
            display: flex;
            align-items: center;
            text-align: center;
            /* Center everything by default */
            justify-content: space-between !important;
        }

        .logo-container {
            flex: 0 0 80px;
        }

        .logo-image {
            width: 100%;
            height: auto;
            max-width: 80px;
            border-radius: 10px;
            background: #ffffff;
        }

        .header-content {
            flex: 1;
            text-align: center;
            color: #ffffff;
        }

        .header-content h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .header-content h5 {
            font-size: 18px;
            font-weight: 500;
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
            border-left: 1px solid #dfdfdf;
            border-right: 1px solid #dfdfdf;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2a2a2a;
        }

        .message {
            font-size: 15px;
            color: #555;
            margin-bottom: 25px;
        }

        .credentials-container {
            background-color: #f8f9fc;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e5eaf2;
        }

        .credential-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .credential-item:last-child {
            margin-bottom: 0;
        }

        .credential-label {
            margin-right: 6px;
            /* min-width: 120px; */
            font-weight: 600;
            color: #2a2a2a;
        }

        .credential-value {
            flex: 1;
            color: #2ca87f;
            font-weight: 500;
            word-break: break-all;
        }

        .action-button {
            display: inline-block;
            background-color: #2ca87f;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(44, 168, 127, 0.2);
        }

        .action-button:hover {
            background-color: #238e6b;
            box-shadow: 0 6px 15px rgba(44, 168, 127, 0.3);
            transform: translateY(-2px);
        }

        .signature {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #eaeef3;
            color: #555;
        }

        .signature p {
            margin-bottom: 5px;
        }

        .bold {
            font-weight: 600;
        }

        .email-footer {
            background-color: #f4f7fa;
            padding: 20px;
            text-align: center;
            color: #8a8a8a;
            font-size: 12px;
            border-top: 1px solid #eaeef3;
        }

        @media (max-width: 600px) {
            .email-wrapper {
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0;
            }

            .email-header {
                padding: 20px;
            }

            .logo-container {
                flex: 0 0 60px;
            }

            .logo-image {
                max-width: 60px;
            }

            .header-content h3 {
                font-size: 18px;
            }

            .header-content h5 {
                font-size: 14px;
            }

            .email-body {
                padding: 25px 20px;
            }
        }

        @media (max-width: 480px) {
            .logo-container {
                flex: 0 0 auto;
            }

            .header-content {
                text-align: center;
                width: 100%;
            }

            .header-content h3 {
                font-size: 12px;
            }

            .header-content h5 {
                font-size: 11px;
            }

            .email-body {
                padding: 20px 15px;
            }

            .credentials-container {
                padding: 15px;
            }

            .credential-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .credential-label {
                margin-bottom: 5px;
            }

            .action-button {
                display: block;
                text-align: center;
                padding: 12px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <div style="width: 100%; max-width: 100%; margin: 0; padding: 0;">
        <div class="email-wrapper">
            <div class="email-header">
                <div class="logo-container">
                    <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Logo" class="logo-image">
                </div>
                <div class="header-content" style="margin: auto;">
                    <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                    <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
                </div>
            </div>

            <div class="email-body">
                <div class="greeting">வணக்கம் ,</div>

                <div class="message">
                    தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம் நடத்தவிருக்கும் {{ $examName }} தேர்வு {{ $examDate }}
                    அன்று மாநிலம் முழுவதும் நடைபெற உள்ளதாக இதன் மூலம் அறிவிக்கப்படுகின்றது.
                </div>
                <p class="message">இது தொடர்பாக, கீழ்வரும் கூடம்/விண்ணப்பத்துடன் கூடிய இடத்தை உங்களது மாவட்ட
                    நிர்வாகத்தின் கீழ் தேர்வுக்காக பயன்படுத்துவதற்கு நாம் முன்மொழைக்கின்றோம்:</p>
                <div class="credentials-container">
                    <div class="credential-item">
                        <div class="credential-label">கூடத்தின் பெயர்:</div>
                        <div class="credential-value">{{ $venueName }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">முகவரி:</div>
                        <div class="credential-value">{{ $venueAddress }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">பயன்படுத்த வேண்டிய தேதி/திகதிகள்:</div>
                        <div class="credential-value">{{ implode(', ', $examDates) }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">நோக்கம்:</div>
                        <div class="credential-value"> தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம் நடத்தும் {{ $examName }}
                            தேர்வுக்காக</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">எதிர்பார்க்கப்படும் தேர்வர்கள் எண்ணிக்கை:</div>
                        <div class="credential-value"> {{ $requiredSeats }}</div>
                    </div>
                </div>
                <p class="message">
                    மேற்கண்ட இடங்களை பயன்படுத்துவதற்கு உங்கள் ஒப்புதல் மற்றும் அனுமதியை வழங்குமாறு பணிவுடன்
                    கேட்டுக்கொள்கிறோம். பாதுகாப்பு, சுத்தம், மற்றும் தேர்வுக்குப் பிறகு கூடம் சீராக ஒப்படைக்கும்
                    நடவடிக்கைகள் ஆகியவை அனைத்தும் நாங்கள் உறுதி செய்கிறோம்.
                </p>

                <p class="message">மேலும் நடவடிக்கைகளுக்காக அனுமதி மற்றும் ஒப்புதலை விரைவில் வழங்குமாறு வேண்டுகிறோம்.
                </p>
                <p style="margin-top:12px">TNPSC EMS கணக்கிற்குள் உள்நுழைய:</p>
                <center>
                    <a href="{{ route('login') }}" class="action-button">உங்கள் கணக்கிற்குள் உள்நுழைக</a>
                    <p>👆 இங்கே கிளிக் செய்யவும்</p>
                </center>
                <div class="signature">
                    <p>நன்றி,</p>
                    <p class="bold">சேவை குழு</p>
                    <p>தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</p>
                </div>
            </div>

            <div class="email-footer">
                <p>Copyright © {{ date('Y') }} TNPSC. Developed By Smashwing Technologies Pvt Ltd. All rights
                    reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
