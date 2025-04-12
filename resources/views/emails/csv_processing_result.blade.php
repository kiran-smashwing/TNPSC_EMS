<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['status'] }} - {{ $data['taskType'] }} CSV Processing</title>
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

        p {
            font-size: 15px;
            color: #555;
            margin-bottom: 15px;
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
            min-width: 150px;
            font-weight: 600;
            color: #2a2a2a;
        }

        .credential-value {
            flex: 1;
            color: #2ca87f;
            font-weight: 500;
            word-break: break-all;
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
                align-items: flex-start;
            }

            .credential-label {
                margin-bottom: 5px;
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
                <div class="greeting">{{ $data['taskType'] }} CSV Processing Result</div>
                <div class="message">
                    Your {{ $data['taskType'] }} CSV file has been processed. Please find the processing results below:
                </div>

                <div class="credentials-container">
                    <div class="credential-item">
                        <div class="credential-label">Status:</div>
                        <div class="credential-value">{{ ucfirst($data['status']) }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">Successful Inserts:</div>
                        <div class="credential-value">{{ $data['successfulInserts'] }}</div>
                    </div>
                    <div class="credential-item">
                        <div class="credential-label">Failed Rows:</div>
                        <div class="credential-value">{{ $data['failedCount'] }}</div>
                    </div>
                </div>

                @if ($data['failedCsvLink'])
                    <p>Some rows could not be processed. You can download a CSV file containing these rows and their
                        error messages for review: <a href="{{ $data['failedCsvLink'] }}">Download Failed Rows</a></p>
                @endif

                @if ($data['errorMessage'])
                    <p>Error: {{ $data['errorMessage'] }}</p>
                @endif

                <p>Uploaded CSV: <a href="{{ $data['uploadedCsvLink'] }}">Download</a></p>
            </div>

            <div class="email-footer">
                <p>Copyright © {{ date('Y') }} TNPSC. Developed By Smashwing Technologies Pvt Ltd. All rights
                    reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
