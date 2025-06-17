<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery REPORT</title>
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
                <img src="{{ storage_path('app/public/assets/images/watermark.png') }}" alt="Logo"
                    class="logo-image" />
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h5>Delivery Report</h5>
        </div>
        <div class="content-section">
            <p><strong> Notification No:</strong> {{ $notification_no }} | <strong> Exam Date:</strong>
                {{ $exam_date }} | <br>
                <strong>Exam Name:</strong> {{ $exam_data->exam_main_name }} <br>
                <strong>Exam Service:</strong> {{ $exam_data->examservice->examservice_name }} <br>
            </p>
        </div>

        @foreach ($grouped_data as $district_name => $data)
            <div class="district-section" style="{{ !$loop->first ? 'page-break-before: always;' : '' }}">
                <div class="meeting-title" style="margin: 40px 0 20px 0; padding: 10px 0;">
                    <h5 style="margin-bottom: 10px;">
                        <strong>District:</strong> {{ $district_name }}
                    </h5>
                </div>

                <table class="report-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>District</th>
                            <th>Center</th>
                            <th>Hall</th>
                            <th>QR Code</th>
                            <th>Category</th>
                            <th>District Scanned At</th>
                            @if($exam_data->exam_main_model == 'Major')
                            <th>Center Scanned At</th>
                            @endif
                            <th>Mobile Team Scanned At</th>
                            <th>CI Scanned At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['materials'] as $index => $entry)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $entry['district_code'] }} <br> {{ $entry['district_name'] }}</td>
                                <td>{{ $entry['center_code'] }} <br> {{ $entry['center_name'] }}</td>
                                <td>{{ $entry['hall_code'] }} - {{ $entry['venue_name'] }}</td>
                                <td>{{ $entry['qr_code'] }}</td>
                                <td>{{ $entry['category'] }}</td>
                                <td width="200px">{{ $entry['district_scanned_at'] }}</td>
                                @if($exam_data->exam_main_model == 'Major')
                                <td width="200px">{{ $entry['center_scanned_at'] }}</td>
                                @endif
                                <td width="200px">{{ $entry['mobile_team_scanned_at'] }}</td>
                                <td width="200px">{{ $entry['ci_scanned_at'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Summary Table -->
                <table class="report-table" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Total Materials</th>
                            <th>District Scanned</th>
                            <th>District %</th>
                            @if($exam_data->exam_main_model == 'Major')
                            <th>Center Scanned</th>
                            <th>Center %</th>
                            @endif
                            <th>Mobile Team Scanned</th>
                            <th>Mobile Team %</th>
                            <th>CI Scanned</th>
                            <th>CI %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $data['totals']['total_materials'] }}</td>
                            <td>{{ $data['totals']['district_scanned_count'] }}</td>
                            <td>{{ $data['totals']['district_percentage'] }}</td>
                            @if($exam_data->exam_main_model == 'Major')
                            <td>{{ $data['totals']['center_scanned_count'] }}</td>
                            <td>{{ $data['totals']['center_percentage'] }}</td>
                            @endif
                            <td>{{ $data['totals']['mobile_team_scanned_count'] }}</td>
                            <td>{{ $data['totals']['mobile_team_percentage'] }}</td>
                            <td>{{ $data['totals']['ci_scanned_count'] }}</td>
                            <td>{{ $data['totals']['ci_percentage'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="district-section" style="page-break-before: always;">
            <div class="meeting-title" style="margin: 40px 0 20px 0; padding: 10px 0;">
                <h5 style="margin-bottom: 10px;">
                    <strong>Overall Summary for All Districts</strong>
                </h5>
            </div>
        <table class="report-table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Total Materials</th>
                    <th>District Scanned</th>
                    <th>District %</th>
                    @if($exam_data->exam_main_model == 'Major')
                    <th>Center Scanned</th>
                    <th>Center %</th>
                    @endif
                    <th>Mobile Team Scanned</th>
                    <th>Mobile Team %</th>
                    <th>CI Scanned</th>
                    <th>CI %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $grand_total['total_materials'] }}</td>
                    <td>{{ $grand_total['district_scanned_count'] }}</td>
                    <td>{{ $grand_total['district_percentage'] }}</td>
                    @if($exam_data->exam_main_model == 'Major')
                    <td>{{ $grand_total['center_scanned_count'] }}</td>
                    <td>{{ $grand_total['center_percentage'] }}</td>
                    @endif
                    <td>{{ $grand_total['mobile_team_scanned_count'] }}</td>
                    <td>{{ $grand_total['mobile_team_percentage'] }}</td>
                    <td>{{ $grand_total['ci_scanned_count'] }}</td>
                    <td>{{ $grand_total['ci_percentage'] }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    </div>
</body>

</html>
