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

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .attendance-table th {
            background-color: #E3F1EE;
            font-weight: bold;
            text-align: center;
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
        }
    </style>
</head>

<body>
    <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Watermark" class="watermark">
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ asset('storage/assets/images/watermark.png') }}" alt="Logo" class="logo-image">
            </div>
            <div class="header-content">
                <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
                <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
            </div>
        </div>

        <div class="meeting-title">
            <h5>CI MEETING ATTENDANCE REPORT</h5>
            <p><strong> Notification No:</strong> 10/2024 |
                <strong>Exam Name:</strong> RASHTRIYA INDIAN MILITARY COLLEGE(JULY-2025 TERM) |
                <strong>Meeting Date & Time:</strong> 01-12-2024 10:30 AM | 
                <strong>Date:</strong> 01-12-2024
            </p>
        </div>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>District Name</th>
                    <th>Center Code</th>
                    <th>Hall No</th>
                    <th>Venue Name</th>
                    <th>Attendance Date & Time</th>
                    <th>Adequacy Check Date & Time</th>
                    <th>CI Name</th>
                    <th>CI Phone</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 100; $i++)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>Chennai</td>
                        <td>0102</td>
                        <td>001</td>
                        <td>Chennai High School</td>
                        <td>01-12-2024 10:30 AM</td>
                        <td>01-12-2024 10:45 AM</td>
                        <td>Mr. John Doe</td>
                        <td>9999999999</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

</body>

</html>
