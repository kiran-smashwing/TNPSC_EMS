<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI Meeting QR Code</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 60%;
            max-width: 500px;
            pointer-events: none;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-container {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo-container {
            flex: 0 0 90px;
        }

        .logo-image {
            max-width: 100%;
            max-height: 90px;
        }

        .header-content {
            flex: 1;
            text-align: center;
        }

        .meeting-title {
            background-color: #E3F1EE;
            /* background-color: #f0f0f0; */
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        h3 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }

        h5 {
            font-size: 18pt;
            margin: 5px 0 0 0;
        }

        .content-section {
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
        }

        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 20px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        .qr-code-label {
            font-weight: bold;
            font-size: 12pt;
        }

        .footer-instructions {
            background-color: #E3F1EE;
            /* background-color: #d7ffde; */
            padding: 15px;
            margin-top: 20px;
            border-top: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-qr-codes {
            display: flex;
            gap: 20px;
            margin-right: 20px;
        }

        .footer-qr-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-qr-image {
            width: 140px;
            height: 140px;
        }

        .footer-instructions-text {
            flex-grow: 1;
            text-align: center;
        }

        @media print {
            .header-container {
                position: static;
            }

            .container {
                max-width: 100% !important;
                margin: 0;
                padding: 0;
            }

            body {
                zoom: 0.8;
            }

            .footer-instructions {
                break-inside: avoid;
                page-break-inside: avoid;
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                margin: 0;
                padding: 15px;
                box-sizing: border-box;
            }
        }
    </style>
</head>

<body>
    <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Watermark" class="watermark">
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Logo" class="logo-image">
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h2>Verify Handover of Materials</h2>
        </div>
        @foreach ($exams as $exam)
            <div class="content-section">
                <p>
                    <strong>Notification No:</strong> {{ $exam->exam_main_notification }} |
                    <strong>Start Date:</strong> {{ date('d-m-Y', strtotime($exam->exam_main_startdate)) }} <br>
                    <strong>Exam Name:</strong> {{ $exam->exam_main_name }} <br>
                    <strong>Exam Service:</strong> {{ $exam->examservice->examservice_name }}
                </p>
            </div>
        @endforeach
        @php
            $districts = $vehicles->escortstaffs->pluck('district.district_name')->unique()->toArray();
            $districtNames = implode(', ', $districts);
        @endphp
        <div class="content-section">
            <p><strong>Districts:</strong> {{ $districtNames }}</p>
        </div>
        <div class="center-container">
            <table border="1" cellspacing="0" cellpadding="8" width="100%" style="border-collapse: collapse; text-align: left;">
                <thead style="background-color: #E3F1EE;">
                    <tr>
                        <th>#</th>
                        <th>Escort Staff Name</th>
                        <th>District</th>
                        <th>Final Remarks</th>
                        <th>Camera Handovered</th>
                        <th>GPS Lock Handovered</th>
                        <th>Memory Card Handovered</th>
                        <th>Confidential Material Offloaded</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $verificationDetails = json_decode($vehicles->handover_verification_details, true);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $staff->district->district_name ?? 'N/A' }}</td>
                            <td>{{ $verificationDetails['final_remarks'] ?? 'N/A' }}</td>
                            <td>{{ $verificationDetails['camera_handovered'] ? 'Yes' : 'No' }}</td>
                            <td>{{ $verificationDetails['gps_lock_handovered'] ? 'Yes' : 'No' }}</td>
                            <td>{{ $verificationDetails['memory_card_handovered'] ? 'Yes' : 'No' }}</td>
                            <td>{{ $verificationDetails['confidential_material_offloaded'] ? 'Yes' : 'No' }}</td>
                        </tr>
                </tbody>
            </table>
        </div>        

    </div>
</body>

</html>
