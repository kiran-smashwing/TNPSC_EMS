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
            margin-bottom: 10px;
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
            width: 99.9%;
            border-collapse: collapse;
            margin-bottom: 5px;
            /* background-color: #fff; */
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: center;

        }

        .attendance-table th {
            background-color: #e3f1ee;
            text-align: left;
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
            <h5>TENTATIVE PROGAMME ROUTE NO - {{ $route->route_no }} ({{ $route->district->district_name }})</h5>
            <p><strong> Notification No:</strong> {{ $session->currentexam->exam_main_notification }} |
                <strong>Exam Name:</strong> {{ $session->currentexam->exam_main_name }} |
                <strong>Exam Date:</strong> {{ $session->exam_sess_date }}
            </p>
        </div>
        <table class="attendance-table" style="margin-bottom: 0px;">
            <tr>
                <td><strong>Route No:</strong> {{ $route->route_no }}</td>
                <td><strong>District:</strong> {{ $route->district->district_code }} -
                    {{ $route->district->district_name }} </td>
                <td colspan="2"><strong>Vehicle No:</strong> {{ $route->vehicle_no }}</td>
            </tr>
            <tr>
                <td><strong>Driver Name:</strong> {{ $route->driver_name }}</td>
                <td><strong>Driver Licenese No:</strong> {{ $route->driver_license }}</td>
                <td><strong>Driver Mobile No:</strong> {{ $route->driver_phone }}</td>
            </tr>
            <tr style="border-bottom: 0px;">
                <td><strong>Mobile Team Staff:</strong> {{ $route->mobileTeam->mobile_name }}</td>
                <td colspan="2"><strong>Mobile Team Staff Mobile No:</strong> {{ $route->mobileTeam->mobile_phone }}
                </td>
            </tr>

        </table>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th rowspan="2">S.No</th>
                    <th rowspan="2">Center <br> Code</th>
                    <th rowspan="2">Hall <br> No</th>
                    <th rowspan="2">Hall Name</th>
                    <th rowspan="2">Address</th>
                    <th colspan="2">Upward</th>
                    <th colspan="2">Downward</th>
                </tr>
                <tr>
                    <th width="7%">Arrival</th>
                    <th>Departure</th>
                    <th width="7%">Arrival</th>
                    <th>Departure</th>
                </tr>

            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($routeData as $Data)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $Data['center_code'] }}</td>
                        <td>{{ $Data['hall_code'] }}</td>
                        <td>{{ $Data['venue_name'] }}</td>
                        <td>{{ $Data['venue_address'] }}</td>
                        <td>10:30 AM</td>
                        <td>10:30 AM</td>
                        <td>10:45 AM</td>
                        <td>10:45 AM</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
