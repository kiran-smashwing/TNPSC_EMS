<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expenditure Report</title>
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

        .intro-text {
            font-size: 14pt;
            margin: 20px 0;
            line-height: 1.6;
            text-align: justify;
        }

        .report-table {
            width: 99.9%;
            border-collapse: collapse;
            margin-bottom: 5px;
            /* background-color: #fff; */
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

        .signature-container {
            display: flex;
            flex-direction: column;
            width: 99.8%;
            /* border: 1px solid #ddd; */
        }

        .signature-row {
            display: flex;
            border: 1px solid #ddd;

        }

        .signature-column {
            flex: .6;
            padding: 5px 10px;
            vertical-align: top;
        }

        .signature-column-1 {
            flex: 1;
            padding: 5px 10px;
            vertical-align: top;
        }

        .signature-column:last-child {
            border-right: none;
        }

        .signature-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100px;
            /* Adjust as needed */
        }

        .signature-row-inline {
            display: flex;
            align-items: center;
        }

        .signature-top-bottom-inline {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .signature-label {
            font-weight: bold;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .empty-space {
            flex-grow: 1;
            height: 30px;
            border-bottom: 1px solid #ddd;
        }

        .underline {
            text-decoration: underline;
            text-decoration-color: #474545;
            /* Change the color of the underline */
            text-underline-offset: 3px;
            /* Adjust the offset to add space to the underline */
        }

        .name-space {
            flex-grow: 1;
            font-weight: 500;
            padding-left: 10px;
        }

        .seal-space {
            height: 80px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .flex-table {
            display: flex;
            flex-direction: column;
            width: 99.5%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            /* Add border to the table */
        }

        .flex-row {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            border-bottom: 1px solid #ddd;
            /* Add border to the rows */
        }

        .flex-cell {
            flex: 1;
            padding: 10px;
            border-right: 1px solid #ddd;
            /* Add border to the cells */
            vertical-align: top;
            text-align: left;
        }

        .flex-cell:last-child {
            border-right: none;
            /* Remove right border from the last cell */
        }



        .row-label {
            /* background-color: #e3f1ee; */
        }

        .flex-header {
            background-color: #e3f1ee;
            font-weight: bold;
            text-align: left;
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
            <h5>Expenditure Report</h5>
        </div>
        <p class="intro-text">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Certified that the advance amount of
            <span class="underline">&nbsp;&nbsp;{{ $amount ?? '_______________' }}&nbsp;&nbsp;</span>(Rupees <span
                class="underline">&nbsp;&nbsp;{{ $amount_in_words ?? '__________________________' }}&nbsp;&nbsp;</span>) received from the
            Secretary, TNPSC, Chennai, has been
            utilised towards the conduct of the written examination. The details of the examination and expenditure are
            outlined below:
        </p>
        <div class="flex-table">
            <div class="flex-row">
                <div class="flex-cell row-label" > <strong>Examination Name:</strong>
                    {{ $exam_data->exam_main_name ?? 'N/A' }} </div>
            </div>
            <div class="flex-row">
                <div class="flex-cell row-label-header" > <strong>Notification No:</strong>
                    {{ $exam_data->exam_main_notification ?? 'N/A' }} </div>
                <div class="flex-cell row-label-header" > <strong>Exam Service:</strong>
                    {{ $exam_data->examservice->examservice_name ?? 'N/A' }} </div>
            </div>
            <div class="flex-row">
                <div class="flex-cell row-label" style="width: 350px;"> <strong>Exam Date:</strong>
                    {{ $formattedDatesString ?? 'N/A' }} </div>
                <div class="flex-cell row-label"> <strong>District:</strong>
                    {{ $examDetails['district_name'] ? $examDetails['district_name'] : 'N/A' }} </div>
                <div class="flex-cell row-label"> <strong>Center:</strong>
                    {{ $examDetails['center_name'] ? $examDetails['center_name'] . ' (' . $examDetails['center_code'] . ')' : 'N/A' }}
                </div>
            </div>
            <div class="flex-row">
                <div class="flex-cell row-label"style="width: 150px; flex: 1.9;" > <strong>Hall Name:</strong>
                    {{$examDetails['venue_name'] ?? 'N/A' }} </div>
                <div class="flex-cell row-label"> <strong>Hall Code:</strong>
                    {{ $hall_code ?? 'N/A' }} </div>
            </div>
        </div>

        <p>The details of the expenditure incurred are as follows:</p>

        <table class="report-table">
            <tr>
                <th>S.No</th>
                <th>Description</th>
                <th>Amount (in Rs.)</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Remuneration (a) Chief Invigilators, Invigilators, Assisting Staff</td>
                <td>{{ $utility_answer['ciAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>1.1</td>
                <td>Remuneration (b) Sweeper, Sanitary Worker, Waterman</td>
                <td>{{ $utility_answer['assistantStaffAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>1.2</td>
                <td>Remuneration (c) Police Personnel</td>
                <td>{{ $utility_answer['policeAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>1.3</td>
                <td>Remuneration (d) Scribe(s)(if any)</td>
                <td>{{ $utility_answer['scribeAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>1.4</td>
                <td>Remuneration (e) Inspection staff deputed by DRO / District Collector</td>
                <td>{{ $utility_answer['inspectionStaffAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Stationery</td>
                <td>{{ $utility_answer['stationeryAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Venue (Hall) Rent</td>
                <td>{{ $utility_answer['hallRentAmount'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">TOTAL</td>
                <td>{{ $utility_answer['totalAmountSpent'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">Amount Received</td>
                <td>{{ $utility_answer['amountReceived'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">Amount Spent</td>
                <td>{{ $utility_answer['totalAmountSpent'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">Balance Amount</td>
                <td>{{ $utility_answer['balanceAmount'] ?? 'N/A' }}</td>
            </tr>
        </table>


        <div class="signature-container">
            <div class="signature-row">
                <div class="signature-column">
                    <div class="signature-label">School / Office Seal</div>
                    <div class="seal-space"></div>
                </div>
                <div class="signature-column-1">
                    <div class="signature-column signature-content">
                        <div class="signature-row-inline signature-top-bottom-inline">
                            <div class="signature-label">Signature with Date: </div>
                            <div class="empty-space"></div>
                        </div>
                        <div class="signature-row-inline signature-top-bottom-inline">
                            <div class="signature-label">Name and Designation: </div>
                            <div class="name-space">
                                {{ $examDetails['ci_name'] && $examDetails['ci_designation'] ? $examDetails['ci_name'] . ' - ' . $examDetails['ci_designation'] : 'N/A' }}
                            </div>
                        </div>
                        <div class="signature-row-inline signature-top-bottom-inline">
                            <div class="signature-label">Phone Number: </div>
                            <div class="name-space">{{ $examDetails['ci_phone'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
