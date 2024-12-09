<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            /* background-image: url('{{ asset('storage/assets/images/logo_background.png') }}');
            background-size: contain; Ensures the image scales without stretching */
            background-position: center center; /* Centers the background image horizontally and vertically */
            background-repeat: no-repeat; /* Prevents the background from repeating */
            margin: 0;
            padding: 0;
            height: 100vh; /* Ensures the body covers the full viewport height */
            display: flex; /* Enables flexbox for the layout */
            justify-content: center; /* Centers content horizontally */
            align-items: center; /* Centers content vertically */
        }

        #header {
            overflow: hidden; /* Clear float */
            margin: 20px;
            /* border-bottom: 2px solid #000; Adds a line below the header */
            padding-bottom: 10px;
        }

        #header img {
            float: left; /* Floats the image to the left */
            max-width: 150px; /* Adjust image size */
            max-height: 150px;
            object-fit: contain;
            margin-right: 20px; /* Space between the image and text */
        }

        #header-text {
            display: flex;
            align-items: center; /* Vertically centers the text */
            justify-content: center; /* Horizontally centers the text within its space */
            height: 150px; /* Ensures alignment matches the image's height */
        }

        #header-text b {
            display: block;
            text-align: center;
        }

        #customers {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.9); /* Transparent background */
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers th {
            text-align: left;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div id="header">
    <img src="{{ asset('storage/assets/images/login-logo.png') }}" alt="Header Image">
    <div id="header-text">
        <b>TAMIL NADU PUBLIC SERVICE COMMISSION</b>
        <b>Sample Examination</b>
        <b>Exam Date: 01-01-2024 | Session: FN & AN</b>
        <b style="margin-top: 10px;">Attendance Report</b>
    </div>
</div>

<table id="customers">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Centre Code</th>
            <th>Centre Name</th>
            <th>Total Present</th>
            <th>Total Absent</th>
            <th>Total Allotted</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>C001</td>
            <td>Centre One</td>
            <td>150</td>
            <td>50</td>
            <td>200</td>
            <td>75.00%</td>
        </tr>
        <tr>
            <td>2</td>
            <td>C002</td>
            <td>Centre Two</td>
            <td>180</td>
            <td>20</td>
            <td>200</td>
            <td>90.00%</td>
        </tr>
    </tbody>
</table>

<br><br>

<table id="customers">
    <tbody>
        <tr>
            <th></th>
            <th>Overall</th>
            <th></th>
            <th></th>
            <th>Present</th>
            <th>Absent</th>
            <th>Allotted</th>
        </tr>
        <tr>
            <td></td>
            <td>Total</td>
            <td></td>
            <td></td>
            <td>330</td>
            <td>70</td>
            <td>400</td>
        </tr>
        <tr>
            <td></td>
            <td>Percentage(%)</td>
            <td></td>
            <td></td>
            <td>82.50%</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

</body>
</html>
