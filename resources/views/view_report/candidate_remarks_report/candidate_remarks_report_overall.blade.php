<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Candidate Remarks</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
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

        .content-section {
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
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
            background-color: #e3f1ee;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .table-body td {
            text-align: center;
        }

        .table-body td.left-align {
            text-align: left;
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

        .report-table {
            width: 99.9%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        .report-table th {
            background-color: #e3f1ee;
            font-weight: bold;
        }

        .sno-column {
            width: 5%;
            text-align: center;
        }

        .certificate-section {
            margin-top: 30px;
        }

        .certificate-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #333;
            border-radius: 4px;
            display: inline-block;
            position: relative;
        }

        .exam-info {
            text-align: center;
            margin: 20px 0;
            line-height: 1.5;
        }

        .certificate-checkbox.checked:before {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .signature-item {
            margin-top: 10px;
        }

        .signature-section {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            align-items: flex-start;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* New styles for second table */
        .report-summary-table {
            width: 644px;
            cellpadding: 1px;
            cellspacing: 0;
        }

        .report-summary-table td,
        .report-summary-table th {
            border: 1px solid #dcdcdc;
            padding: 0in 0in;
        }

        .report-summary-table .bg-gray {
            background-color: #f1f1f1;
        }

        .report-summary-table .centered-text {
            text-align: center;
        }

        .report-summary-table .font-small {
            font-size: 10pt;
        }

        .report-summary-table .font-medium {
            font-size: 11pt;
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

            .page-number:after {
                content: "Page " counter(page) " of " counter(pages);
            }

            #certificate {
                page-break-before: always;
            }

        }
    </style>
</head>

<body>
    <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Watermark" class="watermark" />
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Logo" class="logo-image" />
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h5>Candidate Remarks</h5>
        </div>
        <div class="content-section">
            <p><strong> Notification No:</strong> {{ $notification_no }} | <strong> Exam Date:
                </strong>{{ $exam_date }} | <strong>Exam
                    Session:</strong> {{ $session }}<br>
                <strong>Exam Name:</strong> {{ $exam_data->exam_main_name }} <br>
                <strong>Exam Service:</strong> {{ $exam_data->examservice->examservice_name }} <br>
            </p>
        </div>
        <table class="report-table">
            <thead class="table-header">
                <tr>
                    <th>S.No</th>
                    <th>District</th>
                    <th>Center Code</th>
                    <th>Center Name</th>
                    <th>Hall Code</th>
                    <th style="text-align: left">Venue Name</th>
                    <th>Registration Number</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @forelse($candidate_details as $index => $candidate)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $candidate['district'] }}</td>
                        <td>{{ $candidate['center_code'] ?? 'N/A' }}</td>
                        <td>{{ $candidate['center'] }}</td>
                        <td>{{ $candidate['hall_code'] ?? 'N/A' }}</td>
                        <td class="left-align">{{ $candidate['venue_name'] }}</td>
                        <td>{{ $candidate['reg_no'] }}</td>
                        <td>{{ $candidate['remark'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No candidate remarks available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </div>
    {{-- <table class="report-table">
        <thead>
            <tr>
                <th>Overall</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Allotted</th>
                <th>Percentage(%)</th>
            </tr>
        </thead>
        <tbody class="table-body">
            @php
                $totalPresent = 0;
                $totalAbsent = 0;
                $totalCandidates = 0;
            @endphp

            <!-- Loop through the session data and display individual rows -->
            @foreach ($session_data as $session)
                @php
                    $totalPresent += $session['present'];
                    $totalAbsent += $session['absent'];
                    $totalCandidates += $session['total_candidates'];
                @endphp
            @endforeach

            <!-- Total Row -->
            <tr>
                <td>Total</td>
                <td>{{ $totalPresent }}</td>
                <td>{{ $totalAbsent }}</td>
                <td>{{ $totalCandidates }}</td>
                <td>
                    @php
                        $totalPercentage =
                            $totalCandidates > 0
                                ? number_format(($totalPresent / $totalCandidates) * 100, 2) . '%'
                                : '0%';
                    @endphp
                    {{ $totalPercentage }}
                </td>
            </tr>
        </tbody>
    </table> --}}




</body>

</html>
