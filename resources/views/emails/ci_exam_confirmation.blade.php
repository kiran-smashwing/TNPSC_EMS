<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>தலைமை கண்காணிப்பாளர் தேர்வு பணியிடம் உறுதிப்படுத்தல் மற்றும் கூட்ட விவரங்கள்.</title>
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

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 25px 0 10px;
            color: #2a2a2a;
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

        p {
            font-size: 15px;
            color: #555;
            margin-bottom: 15px;
        }

        .email-body ul {
            list-style-type: disc;
            /* Use disc bullets */
            padding-left: 30px;
            /* Add some left padding for the bullets */
            margin-bottom: 20px;
        }

        .email-body li {
            font-size: 15px;
            color: #555;
            margin-bottom: 10px;
            /* Add spacing between list items */
            line-height: 1.6;
            /* Improve line spacing within list items */
        }

        .email-body li::marker {
            color: #2ca87f;
            /* Change bullet color to match the theme */
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
            padding: 14px 30px;
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

            .credential-item:last-child {
                margin-bottom: 0;
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
                <div class="greeting">வணக்கம் {{ $CI->chiefInvigilator->ci_name ?? '' }},</div>
                <div class="message">தேர்வு மைய <strong>தலைமை கண்காணிப்பாளராக</strong> நீங்கள் நியமிக்கப்பட்டுள்ளீர்கள்
                    என்பதை உறுதிப்படுத்துகிறோம்.</div>

                <div class="message">தங்களின் தேர்வு ஒதுக்கீடு மற்றும் கூட்ட விவரங்கள் கீழே கொடுக்கப்பட்டுள்ளது:</div>
                <div class="credentials-container">
                    <div class="credential-item">
                        <div class="credential-label">தேர்வின் பெயர்:</div>
                        <div class="credential-value">{{ $exam->exam_main_nametamil ?? '' }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">தேர்வு தேதி:</div>
                        <div class="credential-value">
                            @php
                                $dates = explode(',', $CI->exam_dates);
                            @endphp
                            {{ implode(', ', array_map(fn($date) => \Carbon\Carbon::parse($date)->format('d-m-Y'), $dates)) }}
                        </div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">ஹால் எண்:</div>
                        <div class="credential-value">{{ $CI->hall_codes }}</div>
                    </div>
                </div>
                <div class="message">
                    தேர்வு மைய தலைமை கண்காணிப்பாளர் பொது வழிகாட்டு அறிவுரை வழங்கும் கூட்டம்:
                </div>
                <div class="credentials-container">
                    <div class="credential-item">
                        <div class="credential-label">கூட்டம் நடக்கவிருக்கும் தேதி மற்றும் நேரம்: </div>
                        <div class="credential-value">
                            {{ \Carbon\Carbon::parse($meetingDetails->meeting_date_time)->format('d-m-y h:i A') }}</div>
                    </div>
                </div>

                <p style="margin-top:12px">TNPSC EMS கணக்கிற்குள் உள்நுழைய:</p>
                <center>
                    <a href="{{ route('login') }}" class="action-button">உங்கள் கணக்கிற்குள் உள்நுழைக</a>
                    <p>👆 இங்கே கிளிக் செய்யவும்</p>
                </center>

                <h3 class="section-title">முக்கிய குறிப்பு:</h3>
                <div class="message">
                    இந்த மின்னஞ்சல் மூலம் வழங்கப்பட்ட தகவல்கள் உங்களுக்கு ஒதுக்கப்பட்டுள்ள தேர்வு பொறுப்புகளை
                    உறுதிப்படுத்துகிறது. மேலும் உதவிக்கு, தயவுசெய்து உங்கள் மண்டல அலுவலருடன் தொடர்பு கொள்ளவும்.</div>

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
