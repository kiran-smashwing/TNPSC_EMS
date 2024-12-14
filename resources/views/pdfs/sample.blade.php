<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Template</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;

            background-color: #fff;
            box-sizing: border-box;
        }

        /* .container {
            padding: 20mm;
        } */

        .header-container {
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .logo-container {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-content {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .logo-image {
            max-width: 90px;
            max-height: 90px;
        }

        .qr-code-container {
            position: absolute;
            top: 500%;
            text-align: center;
            width: 100%;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            display: inline-block;
        }

        h3 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }

        h5 {
            font-size: 14pt;
            margin: 5px 0 0 0;
            /* text-decoration: underline; */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: auto;
            }

            .container {
                max-width: 100vw !important;
                padding: 0 !important;
                margin: 0% !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ asset('storage/assets/images/login-logo1.png') }}" alt="Logo" class="logo-image">
            </div>
            <div class="header-content">
                <h3>TAMIL NADU PUBLIC SERVICE COMMISSION</h3>
                <h5>Chennnai District || CI MEETING || 01-01-2024 - 10:30 AM<br>
                </h5>
            </div>
        </div>
        <!-- QR Code Section -->
        {{-- <div class="qr-code-container">
            <img src="https://i.sstatic.net/7Eqij.png" alt="QR Code" class="qr-code">
        </div> --}}


        <!-- Add content here -->
    </div>
</body>

</html>
