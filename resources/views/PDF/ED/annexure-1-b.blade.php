<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Annexure-1 B</title>
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

        h3 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }

        h5 {
            font-size: 18pt;
            margin: 5px 0 0 0;
        }

        .sub-header {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* margin-top: 10px; */
            font-size: 20px;
            /* Increased text size for table content */
        }

        th,
        td {
            border: 1px solid #dcdcdc;
            padding: 8px;
            /* Increased padding for better spacing */
            text-align: left;
            font-size: 14px;
            /* Match overall table font size */
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 15px;
            /* Slightly larger font size for headers */
            font-weight: bold;
        }

        .report-summary-table {
            width: 100%;
            /* margin-top: 20px; */
            border-collapse: collapse;
        }

        .report-summary-table td,
        .report-summary-table th {
            padding: 14px;
            /* Increased padding for summary table */
            text-align: center;
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

        @media print {
            .header-container {
                position: static;
            }

            body {
                zoom: 0.8;
            }

            .page-number:after {
                content: "Page " counter(page) " of " counter(pages);
            }
        }
    </style>
</head>

<body>
    <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Watermark" class="watermark" />
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Logo" class="logo-image" />
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h5>Annexure - IB Statement of Receiving Team (Mofussil) Venue: Receiving Cum Storage Hall (1st Floor)</h5>
        </div>
        <div class="content-section">

        </div>
        <table>
            <tr>
                <td colspan="1"><b>Notification No:</b> {{ $groupedExams['notifications'] ?? 'N/A' }}</td>
                <td colspan="1"><b>Exam Date:</b> {{ $groupedExams['exam_dates'] ?? 'N/A' }}</td>
                <td colspan="2"><b>Exam Name:</b> {{ $groupedExams['exam_names'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><b>Route No:</b> {{ $route->route_no ?? 'N/A' }}</td>
                <td colspan="3">
                    <b>GPS Lock Number:</b>
                    {{ collect($route->gps_locks)->filter()->implode(', ') ?: 'N/A' }}
                </td>


            </tr>
            <tr>
                <td colspan="2"><b>District:</b> {{ $route->district_codes ?? 'N/A' }}</td>
                <td colspan="1"><b>Access Card Number:</b></td>

            </tr>
            <tr>
                <td colspan="4"><b>Centre Code & Name:</b> {{ $centers ?? 'N/A' }}</td>

            </tr>
            <tr>
                <td colspan="4"><b>Halls Numbers:</b> {{ $halls ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Memory Cards Received from Dist. Treasury:</b></td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Particulars of One Time Lock For Chartered Vehicle:</b> (I) Used OTL No.
                    {{ implode(',', $cvUsedOtlLocks) ?? 'N/A' }} <br>
                    <b>(II) Unused OTL (Spare Lock Number):</b> {{ implode(',', $cvUnusedOtlLocks) ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Particulars of Unused One Time Lock for Metal Trunk Box Received (Spare Lock Number):</b>
                    {{ $unusedOtlString ?? 'NIL' }}
                </td>

            </tr>
        </table>

        <table class="report-summary-table">
            <tr>
                <th style="width: 20px;">Hall Code</th>
                <th>Hall Name</th>
                <th style="width: 20px;">Trunk Box Number</th>
                <th style="width: 20px;">Used OTL Numbers</th>
                <th style="width: 20px;">Cover-B3 Received (If Any in Separately)</th>
                <th style="width: 20px;">Cover-C Received (If Any in Separately)</th>
                <th style="width: 20px;">Remarks</th>
                <th style="width: 20px;">Name, Designation, Signature with Date and Time</th>
            </tr>
            @php
                $previousTrunk = null;
                $rowspanCounts = [];
                $trunkboxCounts = [];

                // Count occurrences of each trunk box to determine rowspans
                foreach ($examHalls as $box) {
                    $trunkboxCounts[$box->trunkbox_qr_code] = ($trunkboxCounts[$box->trunkbox_qr_code] ?? 0) + 1;
                }
            @endphp

            @foreach ($examHalls as $box)
                <tr>
                    <td>{{ $box->hall_code }}</td>
                    <td>{{ $box->venue_name }}</td>

                    {{-- Only show trunkbox if it's the first occurrence, otherwise rowspan --}}
                    @if ($box->trunkbox_qr_code !== $previousTrunk)
                        <td rowspan="{{ $trunkboxCounts[$box->trunkbox_qr_code] }}">{{ $box->trunkbox_qr_code }}</td>
                        <td rowspan="{{ $trunkboxCounts[$box->trunkbox_qr_code] }}">
                            {{ is_array(json_decode($box->used_otl_code, true)) ? implode(',', json_decode($box->used_otl_code, true)) : '' }}
                        </td>
                        @php $previousTrunk = $box->trunkbox_qr_code; @endphp
                    @endif

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>
