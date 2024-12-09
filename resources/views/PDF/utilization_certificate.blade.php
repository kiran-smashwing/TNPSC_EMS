<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisation Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 20px;
            margin: 0;
            padding: 0;
            position: relative;
            /* Required for the pseudo-element to work properly */
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('storage/assets/images/logo_background.png') }}') no-repeat center center;
            background-size: 400px 400px;
            /* Adjust size of the watermark */
            opacity: 0.01;
            /* Sets the opacity to 10% */
            z-index: -1;
            /* Ensures the watermark stays behind the content */
            border: none;
            /* Removes any border around the logo */
        }

        .container {
            position: relative;
            /* Ensures the container is above the watermark */
            z-index: 1;
        }

        h4 {
            text-align: center;
            text-decoration: underline;
            font-size: 20px;
        }

        p {
            line-height: 1.8;
            font-size: 18px;
            text-indent: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 16px;
        }

        td {
            vertical-align: top;
        }

        td.center {
            text-align: center;
        }

        td.right {
            text-align: right;
        }

        .amount-section {
            font-weight: bold;
            font-size: 16px;
        }

        .signature-table td {
            padding: 30px 15px;
            font-size: 16px;
        }

        .signature-table .seal {
            vertical-align: top;
        }

        .signature-table .signature,
        .signature-table .name {
            text-align: center;
            width: 30%;
        }

        .name-designation {
            padding-left: 15px;
            text-align: left;
            font-size: 20px;
        }
    </style>



</head>

<body>
    <div class="container">
        <h4>UTILISATION CERTIFICATE</h4>
        <p>
            Certified that the advance amount of Rs. <u>10000</u> (Rupees <u>Ten Thousand Only</u>) received from the
            Secretary, TNPSC, Chennai has been utilised towards the conduct of the written examination, the details of
            which are mentioned below:
        </p>

        <table>
            <tr>
                <td>1</td>
                <td>Name of the Examination</td>
                <td>Exam Name (Notification)</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Date of Examination</td>
                <td>01-01-2024 (FN)</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Name of the Centre</td>
                <td>Centre Name - Centre Code</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Venue Name and Number</td>
                <td>Venue Name - Venue Code</td>
            </tr>
            <tr>
                <td colspan="3" class="amount-section">The details of the expenditure incurred are as follows:</td>
            </tr>
            <tr>
                <th>S.No</th>
                <th>Description</th>
                <th>Amount (in Rs.)</th>
            </tr>
            <tr>
                <td>1</td>
                <td><b>Remuneration</b></td>
                <td></td>
            </tr>
            <tr>
                <td rowspan="5"></td>
                <td>(a) Chief Invigilators, Invigilators, Assisting Staff</td>
                <td>5000</td>
            </tr>
            <tr>
                <td>(b) Sweeper, Sanitary Worker, Waterman</td>
                <td>500</td>
            </tr>
            <tr>
                <td>(c) Police Personnel</td>
                <td>1000</td>
            </tr>
            <tr>
                <td>(d) Scribe(s) (if any)</td>
                <td>500</td>
            </tr>
            <tr>
                <td>(e) Inspection staff deputed by DRO / District Collector</td>
                <td>2000</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Stationery</td>
                <td>300</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Venue (Hall) Rent</td>
                <td>2000</td>
            </tr>
            <tr>
                <td rowspan="5"></td>
                <td><b>TOTAL</b></td>
                <td>10500</td>
            </tr>
            <tr>
                <td>Amount Received</td>
                <td>10000</td>
            </tr>
            <tr>
                <td>Amount Spent</td>
                <td>10500</td>
            </tr>
            <tr>
                <td>Balance Amount</td>
                <td>500</td>
            </tr>
        </table>

        <p>
            The unspent amount of Rs.5000 (Rupees Fifty Thousand Only) is returned. The said
            unspent amount has been handed over to (TNPSC Inspection/Mobile Team) staff, Thiru.
            ______________________(Name, Designation)
        </p>

        <table class="signature-table">
            <tr>
                <td class="seal" rowspan="2">School / Office Seal</td>
                <td class="signature">Signature with date</td>
                <td></td>
            </tr>
            <tr>
                <td class="name">Name and Designation</td>
                <td class="name-designation">
                    Name, Designation
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
