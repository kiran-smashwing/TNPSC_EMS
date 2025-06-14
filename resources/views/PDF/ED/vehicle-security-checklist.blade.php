<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Form</title>
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

        .qr-code-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 20px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        .qr-code-label {
            font-weight: bold;
            font-size: 12pt;
        }

        .footer-instructions {
            background-color: #E3F1EE;
            padding: 15px;
            margin-top: 20px;
            border-top: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-qr-codes {
            display: flex;
            gap: 20px;
            margin-right: 20px;
        }

        .footer-qr-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-qr-image {
            width: 140px;
            height: 140px;
        }

        .footer-instructions-text {
            flex-grow: 1;
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dcdcdc;
            padding: 8px;
            text-align: left;
        }

        th {
            /* background-color: #E3F1EE; */
        }

        .certificate-heading {
            text-align: center;
            text-decoration: underline;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .signature-table th,
        .signature-table td {
            border: 1px solid #dcdcdc;
            padding: 10px;
            text-align: center;
        }

        .signature-table th {
            border: 1px solid #dcdcdc;
        }

        .signature-item {
            text-align: left;
        }

        .signature-item strong {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-item span {
            display: block;
            margin-bottom: 3px;
        }

        .row-label {
            width: 30%;
            font-weight: bold;
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

            .footer-instructions {
                break-inside: avoid;
                page-break-inside: avoid;
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                margin: 0;
                padding: 15px;
                box-sizing: border-box;
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
            <h5>Annexure - IA</h5>
        </div>

        <div class="form-container">
            <!-- Examination Info Table -->
            <table>
                <tr>
                    <th class="row-label" colspan="2">Name of the Examination</th>
                    <td>{{$exam_names_string}}</td>
                </tr>
                <tr>
                    <th class="row-label" colspan="2">Date of the Examination</th>
                    <td>{{$exam_dates_string}}</td>
                </tr>
                <tr>
                    <th class="row-label" colspan="2">Route No</th>
                    <td>{{$vehicle_no_details->route_no}}</td>
                </tr>
                <tr>
                    <th class="row-label" colspan="2">Districts Covered</th>
                    <td>{{$district_names_string}}</td>
                </tr>
                <tr>
                    <th class="row-label" colspan="2">Charted Vehicle No</th>
                    <td>{{$vehicle_no_details->charted_vehicle_no}}</td>
                </tr>
            </table>

            <!-- Checklist Table -->
            <table>
                <tr>
                    <td>1</td>
                    <td>Whether the GPS lock is intact?</td>
                    <td>{{ isset($charted_vehicle_verification['GPS_lock_intact']) ? ($charted_vehicle_verification['GPS_lock_intact'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Whether the seal on the Single One Time Lock is intact?</td>
                    <td>{{ isset($charted_vehicle_verification['one_GPS_lock_intact']) ? ($charted_vehicle_verification['one_GPS_lock_intact'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Whether the OTL is intact?</td>
                    <td>{{ isset($charted_vehicle_verification['one_GPS_lock_intact']) ? ($charted_vehicle_verification['one_GPS_lock_intact'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Whether the trunk boxes have been arranged in the pre-determined order?</td>
                    <td>{{ isset($charted_vehicle_verification['pre_determined_order']) ? ($charted_vehicle_verification['pre_determined_order'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Whether the One Time Locks of the trunk boxes are intact?</td>
                    <td>{{ isset($charted_vehicle_verification['one_time_lock_trunk_box']) ? ($charted_vehicle_verification['one_time_lock_trunk_box'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Whether the QR codes pasted on the trunk boxes have been verified</td>
                    <td>Not applicable</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Whether the numbers found on the One Time Locks have been verified with that available in the
                        app?</td>
                        <td>{{ isset($charted_vehicle_verification['number_one_lock_trunk_box']) ? ($charted_vehicle_verification['number_one_lock_trunk_box'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Whether the numbers found on the trunk boxes have been verified with that available in the app?
                    </td>
                    <td>{{ isset($charted_vehicle_verification['verified_availabe_app']) ? ($charted_vehicle_verification['verified_availabe_app'] ? 'Yes' : 'No') : 'No' }}</td>
                </tr>
            </table>

            <div class="certificate">
                <h4 class="certificate-heading">CERTIFICATE</h4>
                It is hereby certified that I have verified all the above aspects in person and while doing so I have
                followed all the procedures stipulated in the Office Orders concerned.
            </div>


            <!-- Signature Table -->
            <table class="signature-table">
                <thead>
                    <tr>
                        <th>ED-Section</th>
                        <th>Section Officer</th>
                        <th>Under Secretary</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- First Row for Signature, Name, Date, Time in ED-Section -->
                    <tr>
                        <td>
                            <div class="signature-item">
                                <strong>Signature</strong><br>
                            </div>
                        </td>
                        <td></td> <!-- Empty cell for Section Officer -->
                        <td></td> <!-- Empty cell for Under Secretary -->
                    </tr>
                    <tr>
                        <td>
                            <div class="signature-item">
                                <strong>Name</strong><br>
                            </div>
                        </td>
                        <td></td> <!-- Empty cell for Section Officer -->
                        <td></td> <!-- Empty cell for Under Secretary -->
                    </tr>
                    <tr>
                        <td>
                            <div class="signature-item">
                                <strong>Date</strong><br>
                            </div>
                        </td>
                        <td></td> <!-- Empty cell for Section Officer -->
                        <td></td> <!-- Empty cell for Under Secretary -->
                    </tr>
                    <tr>
                        <td>
                            <div class="signature-item">
                                <strong>Time</strong><br>
                            </div>
                        </td>
                        <td></td> <!-- Empty cell for Section Officer -->
                        <td></td> <!-- Empty cell for Under Secretary -->
                    </tr>
                </tbody>
            </table>



        </div>
    </div>
</body>

</html>
