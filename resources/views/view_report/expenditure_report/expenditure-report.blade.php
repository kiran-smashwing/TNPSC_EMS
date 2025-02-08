<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expenditure Statement</title>
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

        .table-header th {
            text-align: center;
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
            vertical-align: center;
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
            <h5>Expenditure Statement</h5>
        </div>
        <div class="content-section">
            <p><strong> Notification No:</strong> 10/2024 | <strong> Exam Date: </strong>13-07-2024  | <strong>Exam
                    Session:</strong> FN<br>
                <strong>Exam Name:</strong> Combined Civil Services Examination<br>
                <strong>Exam Service:</strong>  I (Group-I Services)<br>
                {{-- <strong>Center Name:</strong> Alandur (0102	) --}}
            </p>
        </div>
        <table class="report-table">
            <thead class="table-header">
                <tr>
                    <th>S.No</th>
                    <th>Hall Code</th>
                    <th>Center Code</th>
                    <th>Center Name</th>
                    <th>Amount Received(Rs.)</th>
                    <th>Amount Spent(Rs.)</th>
                    <th>Balance Returned(Rs.)</th>
                    {{-- <th>Remarks</th> --}}
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>1</td>
                    <td>001</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>17580.0</td>
                    <td>17580.0</td>
                    <td>0.0</td>
                    
                </tr>
                <tr>
                    <td>2</td>
                    <td>002</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>16800.0</td>
                    <td>16800.0</td>
                    <td>0.0</td>
                    
                </tr>
                <tr>
                    <td>3</td>
                    <td>003</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>16800.0</td>
                    <td>16800.0</td>
                    <td>0.0</td>
                    
                </tr>
                <tr>
                    <td>4</td>
                    <td>004</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>13180.0</td>
                    <td>13180.0</td>
                    <td>0.0</td>
                   
                </tr>
                <tr>
                    <td>5</td>
                    <td>005</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>17580.0</td>
                    <td>17580.0</td>
                    <td>0.0</td>
                   
                </tr>
                <tr>
                    <td>6</td>
                    <td>006</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>16300.0</td>
                    <td>16300.0</td>
                    <td>0.0</td>
                    
                </tr>
                <tr>
                    <td>7</td>
                    <td>007</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td>8</td>
                    <td>008</td>
                    <td>0101</td>
                    <td>Chennai</td>
                    <td>15300.0</td>
                    <td>14800.0</td>
                    <td>0.0</td>
                    
                </tr>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>113,540.0</td>
                    <td>113040.0</td>
                    <td>500.0</td>
                    
                </tr>
               
                <!-- Add more rows as needed -->
            </tbody>
        </table>

    </div>
    {{-- <table class="report-table">
        <thead class="table-header">
            <tr>
                <th>Overall</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Allotted</th>
                <th>Percentage(%)</th>
            </tr>
        </thead>
        <tbody class="table-body">
            <tr>
                <td>Total</td>
                <td>99</td>
                <td>11</td>
                <td>812</td>
                <td>12.19 %</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table> --}}
    



</body>

</html>
