<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UTILISATION CERTIFICATE</title>
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
            <h5>UTILISATION CERTIFICATE</h5>
        </div>
        <p class="intro-text">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Certified that the advance amount of
            Rs. _____________________________________________ <br> (Rupees
            ___________________________________________________) received from the Secretary, TNPSC, Chennai, has been
            utilised towards the conduct of the written examination. The details of the examination and expenditure are
            outlined below:
        </p>
        <table class="report-table">
            <tr>
                <th>1</th>
                <td>Name of the Examination</td>
                <td>Combined Civil Services Examination - I (Group-I Services) (04/2024)</td>
            </tr>
            <tr>
                <th>2</th>
                <td>Date of Examination</td>
                <td>13-07-2024 (FN)</td>
            </tr>
            <tr>
                <th>3</th>
                <td>Name of the centre</td>
                <td>Ranipet - 3501</td>
            </tr>
            <tr>
                <th>4</th>
                <td>Venue Name and Number</td>
                <td>S.S.S. College of Arts, Science and Management - 006</td>
            </tr>
        </table>

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
                <td>13000.00</td>
            </tr>
            <tr>
                <td>1.1</td>
                <td>Remuneration (b) Sweeper, Sanitary Worker, Waterman</td>
                <td>750.00</td>
            </tr>
            <tr>
                <td>1.2</td>
                <td>Remuneration (c) Police Personnel</td>
                <td>300.00</td>
            </tr>
            <tr>
                <td>1.3</td>
                <td>Remuneration (d) Scribe(s)(if any)</td>
                <td>500.00</td>
            </tr>
            <tr>
                <td>1.4</td>
                <td>Remuneration (e) Inspection staff deputed by DRO / District Collector</td>
                <td>500.00</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Stationery</td>
                <td>600.00</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Venue (Hall) Rent</td>
                <td>1500.00</td>
            </tr>
            <tr>
                <td colspan="2">TOTAL</td>
                <td>17150.00</td>
            </tr>
            <tr>
                <td colspan="2">Amount Received</td>
                <td>17150.00</td>
            </tr>
            <tr>
                <td colspan="2">Amount Spent</td>
                <td>17150.00</td>
            </tr>
            <tr>
                <td colspan="2">Balance Amount</td>
                <td>0.00</td>
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
                            <div class="signature-label">Signature with Date : </div>
                            <div class="empty-space"></div>
                        </div>
                        <div class="signature-row-inline signature-top-bottom-inline">
                            <div class="signature-label">Name and Designation : </div>
                            <div class="name-space">Dr. Sivakumar .K.V. & HOD</div>
                        </div>
                        <div class="signature-row-inline signature-top-bottom-inline">
                            <div class="signature-label">Phone Number : </div>
                            <div class="name-space">+91 9591234567</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
