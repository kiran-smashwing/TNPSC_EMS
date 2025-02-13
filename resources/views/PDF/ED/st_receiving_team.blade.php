<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement Template</title>
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
            padding: 12px;
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

        .row-label {
            /* width: 20%; */
            font-weight: bold;
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
                <td class="row-label" colspan="1">Notification No: 10/2024</td>
                <td class="row-label" colspan="1">Exam Date: 01-12-2024</td>
                <td class="row-label" colspan="2">Exam Name: RASHTRIYA INDIAN MILITARY COLLEGE (JULY-2025 TERM)</td>
            </tr>
            <tr>
                <td class="row-label" colspan="2">Route No:</td>
                <td class="row-label" colspan="2">GPS Lock Number:</td>
                
            </tr>
            <tr>
                <td class="row-label" colspan="2">District:</td>
                <td class="row-label" colspan="1">Access Card Number:</td>
                
            </tr>
            <tr>
                <td class="row-label" colspan="4">Centre Code & Name:</td>
                
            </tr>
            <tr>
                <td class="row-label" colspan="4">Halls Numbers:</td>
            </tr>
            <tr>
                <td class="row-label" colspan="4">Memory Cards Received from Dist. Treasury:</td>
            </tr>
            <tr>
                <td class="row-label" colspan="4">
                    Particulars of One Time Lock For Chartered Vehicle: (I) Used OTL No. <br>
                    (II) Unused OTL (Spare Lock Number):
                </td>
            </tr>
            <tr>
                <td class="row-label" colspan="4">
                    Particulars of Unused One Time Lock for Metal Trunk Box Received (Spare Lock Number):
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
            <tr>
                <td></td>
                <td></td>
                <td rowspan="6" style="text-align: left; font-weight: bold; vertical-align: top;"></td>
                <td rowspan="6" style="text-align: left; font-weight: bold; vertical-align: top;"></td>
                <td></td>
                <td></td>
                <td rowspan="6" style="text-align: left; font-weight: bold; vertical-align: top;"></td>
                <td rowspan="6" style="text-align: left; font-weight: bold; vertical-align: top;"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
               
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
               
            </tr>
            
            
           
           
        </table>
        
        



    </div>
</body>

</html>
