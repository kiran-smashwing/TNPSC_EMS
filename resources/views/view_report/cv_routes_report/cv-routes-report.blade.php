<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charted Vehicle Routes Report</title>
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

        @foreach ($routes as $route)
            <div class="meeting-title">
                @php
                    // Generate a comma-separated list of unique "MM/YYYY" from exam dates
                    $notificationNos = $examDetails
                        ->map(function ($exam) {
                            return $exam->exam_main_notification; // Get the unique notification numbers
                        })
                        ->unique()
                        ->implode(', ');
                @endphp

                <h5>CHARTED VEHICLE ROUTES - NOTIFICATION NO - {{ $notificationNos }}</h5>

                @foreach ($examDetails as $exam)
                    <p>
                        <strong>Exam Name:</strong> {{ $exam->exam_main_name }} |
                        <strong>Exam Date:</strong> {{ date('d-m-Y', strtotime($exam->exam_main_startdate)) }}
                    </p>
                @endforeach
            </div>
            <table class="attendance-table" style="margin-bottom: 0px;">
                <tr>
                    <td><strong>Route No:</strong> {{ $route->route_no }} </td>
                    <td><strong>Charted Vehicle No:</strong> {{ $route->charted_vehicle_no ?? 'N/A' }} </td>
                    <td><strong>Driver Name:</strong> {{ $route->driver_details['name'] ?? 'N/A' }}</td>
                    <td><strong>Driver License No:</strong> {{ $route->driver_details['licence_no'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Driver Mobile No:</strong> {{ $route->driver_details['phone'] ?? 'N/A' }}</td>
                    <td ><strong>Escort Vehicle No:</strong>{{ $route->escort_vehicle_details['vehicle_no'] ?? 'N/A' }}</td>
                    <td><strong>Driver Name:</strong> {{ $route->escort_vehicle_details['driver_name'] ?? 'N/A' }}</td>
                    <td><strong>Driver License No:</strong>{{ $route->escort_vehicle_details['driver_licence_no'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Driver Mobile No:</strong>{{ $route->escort_vehicle_details['driver_phone'] ?? 'N/A' }}</td>
                    <td><strong>Police Name:</strong> {{ $route->pc_details['name'] ?? 'N/A' }}</td>
                    <td colspan="2"><strong>Police Mobile No:</strong> {{ $route->pc_details['phone'] ?? 'N/A' }}</td>
                </tr>
            
            </table>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>District</th>
                        <th>TNPSC Officer</th>
                        <th>Police Officer</th>
                        <th>Revenue Officer</th>
                    </tr>
                </thead>
                @php
                    $i = 1;
                @endphp
                @foreach ($route->escortstaffs as $escortstaff)
                    <tbody>

                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $escortstaff->district_code }} - {{ $escortstaff->district->district_name }}</td>
                            <td>{{ $escortstaff->departmentOfficials->dept_off_name ?? 'N/A' }} -
                                {{ $escortstaff->departmentOfficials->dept_off_phone ?? 'N/A' }}</td>
                            <td>{{ $escortstaff->si_details['name'] }} - {{ $escortstaff->si_details['phone'] }}</td>
                            <td>{{ $escortstaff->revenue_staff_details['name'] }} -
                                {{ $escortstaff->revenue_staff_details['phone'] }}</td>
                    </tbody>
                @endforeach

            </table>
            <div style="page-break-after: always;"></div>
        @endforeach

    </div>

</body>

</html>
