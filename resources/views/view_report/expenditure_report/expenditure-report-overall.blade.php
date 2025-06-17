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
            <h5>EXPENDITURE REPORT</h5>
        </div>
        <div class="content-section">
            <p><strong> Notification No:</strong> {{ $notification_no }} |

                <strong>Exam Name:</strong> {{ $exam_data->exam_main_name }} <br>
                <strong>Exam Service:</strong> {{ $exam_data->examservice->examservice_name }} <br>
            </p>
        </div>
        @php
            $grandTotalCIs = 0;
            $grandTotalReceived = 0;
            $grandTotalSpent = 0;
            $grandTotalBalance = 0;

            $grandReceivedCount = 0;
            $grandSpentCount = 0;
            $grandCIsWithUtility = 0;
        @endphp

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
                            <th>Center Name</th>
                            <th>Center Code</th>
                            <th>Hall <br> No</th>
                            <th>Venue Name</th>
                            <th>CI Name</th>
                            <th>CI E-mail</th>
                            <th>CI Phone</th>
                            <th>Amount Received</th>
                            <th>Total Amount Spent</th>
                            <th>Balance Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['ci_utility_records'] as $index => $record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $record->center->center_name ?? 'N/A' }}</td>
                                <td>{{ $record->center->center_code ?? 'N/A' }}</td>
                                <td>{{ $record->hall_code ?? 'N/A' }}</td>
                                <td width="450px">{{ optional($record->ci->venue)->venue_name ?? 'N/A' }}</td>
                                <td>{{ $record->ci->ci_name ?? 'N/A' }}</td>
                                <td style="max-width:300px">{{ $record->ci->ci_email ?? 'N/A' }}</td>
                                <td>{{ $record->ci->ci_phone ?? 'N/A' }}</td>
                                <td width="250px">
                                    {{ $record->utility_answer ? $record->utility_answer['amountReceived'] : 'N/A' }}
                                    <br>
                                </td>
                                <td width="250px">
                                    {{ $record->utility_answer ? $record->utility_answer['totalAmountSpent'] : 'N/A' }}
                                    <br>
                                </td>
                                <td width="250px">
                                    {{ $record->utility_answer ? $record->utility_answer['balanceAmount'] : 'N/A' }}
                                    <br>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            @php
                $totalCIs = count($data['ci_utility_records']);
                $totalReceived = 0;
                $totalSpent = 0;
                $totalBalance = 0;

                $cisWithUtility = 0; // Only if 'totalAmountSpent' > 0
                $cisWithoutUtility = 0;

                $receivedCount = 0;
                $spentCount = 0;

                foreach ($data['ci_utility_records'] as $record) {
                    $utility = $record->utility_answer ?? null;

                    if ($utility && is_array($utility)) {
                        $received = floatval($utility['amountReceived'] ?? 0);
                        $spent = floatval($utility['totalAmountSpent'] ?? 0);
                        $balance = floatval($utility['balanceAmount'] ?? 0);

                        $totalReceived += $received;
                        $totalSpent += $spent;
                        $totalBalance += $balance;

                        if ($received > 0) {
                            $receivedCount++;
                        }
                        if ($spent > 0) {
                            $spentCount++;
                        }

                        // ✅ Only count as "completed" if amount spent > 0
                        if ($spent > 0) {
                            $cisWithUtility++;
                        }
                    }
                }

                // Remaining are those without spent amount (or empty utility data)
                $cisWithoutUtility = $totalCIs - $cisWithUtility;

                $receivedPercentage = $totalCIs > 0 ? number_format(($receivedCount / $totalCIs) * 100, 2) . '%' : '0%';
                $spentPercentage = $totalCIs > 0 ? number_format(($spentCount / $totalCIs) * 100, 2) . '%' : '0%';
            @endphp


            <!-- 🧾 Amount Summary Table -->
            <table class="report-table" style="margin-top: 25px;">
                <thead>
                    <tr>
                        <th>Total Amount Received (₹)</th>
                        <th>Total Amount Spent (₹)</th>
                        <th>Balance Amount (₹)</th>
                        <th>CIs Submitted Received Amount</th>
                        <th>CIs Submitted Spent Amount</th>
                        <th>Received Submission %</th>
                        <th>Spent Submission %</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($totalReceived, 2) }}</td>
                        <td>{{ number_format($totalSpent, 2) }}</td>
                        <td>{{ number_format($totalBalance, 2) }}</td>
                        <td>{{ $receivedCount }}</td>
                        <td>{{ $spentCount }}</td>
                        <td>{{ $receivedPercentage }}</td>
                        <td>{{ $spentPercentage }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- 👥 CI Summary Table -->
            <table class="report-table" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Total CIs</th>
                        <th>Utility Details Submitted</th>
                        <th>Utility Details Not Submitted</th>
                        <th>Completion %</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalCIs }}</td>
                        <td>{{ $cisWithUtility }}</td>
                        <td>{{ $cisWithoutUtility }}</td>
                        <td>{{ $totalCIs > 0 ? number_format(($cisWithUtility / $totalCIs) * 100, 2) . '%' : '0%' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            @php
                $grandTotalCIs += $totalCIs;
                $grandTotalReceived += $totalReceived;
                $grandTotalSpent += $totalSpent;
                $grandTotalBalance += $totalBalance;

                $grandReceivedCount += $receivedCount;
                $grandSpentCount += $spentCount;
                $grandCIsWithUtility += $cisWithUtility;
            @endphp
        @endforeach
        @php
            $grandCIsWithoutUtility = $grandTotalCIs - $grandCIsWithUtility;

            $grandReceivedPercentage =
                $grandTotalCIs > 0 ? number_format(($grandReceivedCount / $grandTotalCIs) * 100, 2) . '%' : '0%';
            $grandSpentPercentage =
                $grandTotalCIs > 0 ? number_format(($grandSpentCount / $grandTotalCIs) * 100, 2) . '%' : '0%';
            $grandCompletionPercentage =
                $grandTotalCIs > 0 ? number_format(($grandCIsWithUtility / $grandTotalCIs) * 100, 2) . '%' : '0%';
        @endphp

        <!-- 📋 Overall Financial Summary -->
        <div class="meeting-title" style="margin: 50px 0 10px 0; padding-top: 20px;">
            <h5 style="margin-bottom: 10px;">
                <strong>Overall Summary for All Districts</strong>
            </h5>
        </div>

        <table class="report-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th>Total Amount Received (₹)</th>
                    <th>Total Amount Spent (₹)</th>
                    <th>Balance Amount (₹)</th>
                    <th>CIs Submitted Received Amount</th>
                    <th>CIs Submitted Spent Amount</th>
                    <th>Received Submission %</th>
                    <th>Spent Submission %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($grandTotalReceived, 2) }}</td>
                    <td>{{ number_format($grandTotalSpent, 2) }}</td>
                    <td>{{ number_format($grandTotalBalance, 2) }}</td>
                    <td>{{ $grandReceivedCount }}</td>
                    <td>{{ $grandSpentCount }}</td>
                    <td>{{ $grandReceivedPercentage }}</td>
                    <td>{{ $grandSpentPercentage }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 👥 Overall CI Summary -->
        <table class="report-table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Total CIs</th>
                    <th>Utility Details Submitted</th>
                    <th>Utility Details Not Submitted</th>
                    <th>Completion %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $grandTotalCIs }}</td>
                    <td>{{ $grandCIsWithUtility }}</td>
                    <td>{{ $grandCIsWithoutUtility }}</td>
                    <td>{{ $grandCompletionPercentage }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Final Summary Table --}}

    </div>
</body>

</html>
