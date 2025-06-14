<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Handover of Exam Materials</title>
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

        .report-table {
            width: 99.9%;
            /* Set table width to 100% to ensure full width */
            border-collapse: collapse;
            margin-bottom: 20px;
            box-sizing: border-box;
            /* Ensure borders are considered in width */
        }

        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        .report-table th {
            background-color: #e3f1ee;
            text-align: left;
            font-weight: bold;
        }

        .sno-column {
            width: 5%;
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
        }
    </style>
</head>

<body>
    <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Watermark" class="watermark">
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Logo" class="logo-image">
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h2>Verify Handover of Exam Materials | Route No - {{ $vehicles->route_no }}</h2>
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
            <p><strong>Districts:</strong> {{ $districtNames }}<br>
                <strong>Vehicle Number:</strong> {{ $vehicles->charted_vehicle_no }}<br>
                <strong>Driver Name:</strong> {{  $vehicles->driver_details['name'] }} |
                <strong>Driver Contact:</strong> {{  $vehicles->driver_details['phone'] }}|
                <strong>Driver License:</strong> {{  $vehicles->driver_details['licence_no'] }} <br>
                <strong>Police Name:</strong> {{ $vehicles->pc_details['name'] }} |
                <strong>Police Contact:</strong> {{ $vehicles->pc_details['phone'] }} |
                <strong>Police IFHRMS No :</strong> {{ $vehicles->pc_details['ifhrms_no'] }} <br>
            </p>
        </div>
        <div class="center-container">
            @php
                $verificationDetails = json_decode($vehicles->handover_verification_details, true);
            @endphp
            <table class="report-table">
                <tr>
                    <th class="sno-column">S.No.</th>
                    <th width="40%">Details</th>
                    <th>Information</th>
                </tr>
                <tr>
                    <td class="sno-column">1</td>
                    <td>Has the camera been handed over from the vehicle?</td>
                    <td>{{ $verificationDetails['camera_handovered'] ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td class="sno-column">2</td>
                    <td>Has the GPS lock been handed over from the vehicle?</td>
                    <td>{{ $verificationDetails['gps_lock_handovered'] ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td class="sno-column">3</td>
                    <td>Has the memory card been handed over from the vehicle?</td>
                    <td>{{ $verificationDetails['memory_card_handovered'] ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td class="sno-column">4</td>
                    <td>Has the confidential material been offloaded from the vehicle?</td>
                    <td>{{ $verificationDetails['confidential_material_offloaded'] ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td class="sno-column">5</td>
                    <td>Final remarks regarding the handover process</td>
                    <td>{{ $verificationDetails['final_remarks'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
