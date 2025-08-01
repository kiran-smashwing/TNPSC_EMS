<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI Meeting Attendance Report</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .content-section {
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
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
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
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

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .attendance-table th {
            background-color: #E3F1EE;
            font-weight: bold;
            text-align: center;
        }

        .district-section:first-child {
            page-break-before: avoid;
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
                <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Logo"
                    class="logo-image">
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h5>CHIEF INVIGILATORS MEETING ATTENDANCE</h5>
        </div>

        <div class="content-section">
            <p>
                <strong>Notification No:</strong> {{ $notification_no }} |
                <strong>Exam Name:</strong> {{ $exam_name }} <br>
                <strong>Exam Service:</strong> {{ $exam_services }}
            </p>
        </div>

        @foreach ($grouped_data as $district_name => $data)
            @php
                $firstCenter = $data['ci_meeting_records']->first()->center ?? null;
                $centerCode = $firstCenter->center_code ?? 'N/A';
            @endphp

            <div class="district-section" style="{{ !$loop->first ? 'page-break-before: always;' : '' }}">
                <div class="meeting-title" style="margin: 40px 0 20px 0; padding: 10px 0;">
                    <h5 style="margin-bottom: 10px;">
                        <strong>District:</strong> {{ $district_name }} |
                        <strong>Center:</strong>{{ $centerName }} - {{ $centerCode }} |
                        <strong>Meeting Date & Time:</strong>
                        {{ $data['meeting_time']['meeting_date'] ?? 'N/A' }}
                        {{ $data['meeting_time']['meeting_time'] ?? '' }}
                    </h5>
                </div>

                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Hall No</th>
                            <th>Venue Name</th>
                            <th>Attendance <br> Date & Time</th>
                            <th>Adequacy Check <br> Date & Time</th>
                            <th>CI Name</th>
                            <th>CI E-mail</th>
                            <th>CI Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['ci_meeting_records'] as $index =>$record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $record->hall_code ?? 'N/A' }}</td>
                                <td width="450px">{{ optional($record->ci->venue)->venue_name ?? 'N/A' }}</td>
                                <td width="250px">
                                    {{ $record->created_at ? $record->created_at->format('d-m-Y h:i:s A') : 'N/A' }}
                                </td>
                                <td width="250px">
                                    {{ $record->updated_at ? $record->updated_at->format('d-m-Y h:i:s A') : 'N/A' }}
                                </td>
                                <td>{{ $record->ci->ci_name ?? 'N/A' }}</td>
                                <td style="max-width:300px">{{ $record->ci->ci_email ?? 'N/A' }}</td>
                                <td>{{ $record->ci->ci_phone ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @php
                    $totalCIs = count($data['ci_meeting_records']);
                    $scannedCIs = collect($data['ci_meeting_records'])
                        ->filter(fn($record) => $record->updated_at !== null)
                        ->count();
                    $attendancePercentage = $totalCIs > 0 ? round(($scannedCIs / $totalCIs) * 100, 2) : 0;
                    $absentCount = $totalCIs - $scannedCIs;
                @endphp

                <table class="attendance-table" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Total CIs</th>
                            <th>CI Scanned</th>
                            <th>Attendance %</th>
                            <th>Absent Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $totalCIs }}</td>
                            <td>{{ $scannedCIs }}</td>
                            <td>{{ $attendancePercentage }}%</td>
                            <td>{{ $absentCount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>

</html>
