<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CONSOLIDATED / COMPREHENSIVE REPORT</title>
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

        .report-table {
            width: 99.9%;
            /* Set table width to 100% to ensure full width */
            border-collapse: collapse;
            margin-bottom: 20px;
            box-sizing: border-box;
            /* Ensure borders are considered in width */
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


        .signature-container {
            display: flex;
            flex-direction: column;
            width: 99.8%;
            margin-top: 5px;
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
            <h5>CONSOLIDATED / COMPREHENSIVE REPORT</h5>
        </div>
        <table class="report-table">
            <tr>
                <th class="sno-column">S.No.</th>
                <th width="40%">Details</th>
                <th>Information</th>
            </tr>
            <tr>
                <td class="sno-column">1</td>
                <td>Name of the Examination</td>
                <td>{{ $exam_data->exam_main_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">2</td>
                <td>Date & Session</td>
                <td>
                    {{ $exam_date ?? '-' }} / {{ $exam_session_type ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="sno-column">3</td>
                <td>Name of the Centre & Centre Code</td>
                <td>{{ $user->center->center_name ? $user->center->center_name . ' (' . $user->center->center_code . ')' : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">4</td>
                <td>Hall Name & Hall Code</td>
                <td>{{ $user->venue->venue_name ?? 'N/A' }} / {{ $hall_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">5</td>
                <td>Time of Receiving Examination Material</td>
                <td>{{ $scanTime }}</td>
            </tr>
            <tr>
                <td class="sno-column">6</td>
                <td>Whether the Question paper bundles received were intact without any damage in respect of the seals
                    affixed on them?</td>
                <td>{{ 'No' }}</td>
            </tr>
            <tr>
                <td class="sno-column">7</td>
                <td>Time of Opening the Question Paper Box</td>
                <td>{{ $qp_box_open_time }}</td>
            </tr>
            <tr>
                <td class="sno-column">8</td>
                <td>Register number(s) of candidates who have used non-personalised OMR (with reasons)</td>
                <td>{{ $remarks_summary['non_personalized_omr'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">9</td>
                <td>Register number(s) of candidates who have returned Blank OMR Answer Sheet without shading any answer
                </td>
                <td>{{ $remarks_summary['blank_omr'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">10</td>
                <td>Register number(s) of candidates who have used pencil for shading the answers in OMR Answer Sheet
                </td>
                <td>{{ $remarks_summary['pencil_omr'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">11</td>
                <td>Register number(s) of candidates who have used pen Other than black ball point pen for shading the
                    answers in OMR Answer Sheet</td>
                <td>{{ $remarks_summary['pen_other_than_black'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">12</td>
                <td>Register number(s) of Differently Abled candidates who appeared in the exam with the assistance of
                    scribe</td>
                <td>{{ $merged_scribes }}</td>
            </tr>
            <tr>
                <td class="sno-column">13</td>
                <td>Register number(s) of candidates who have wrongly seated in the place of other candidate </td>
                <td>{{ $candidate_remarks_summary['wrong_seating'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">14</td>
                <td>Register number(s) of candidates written the exam/used the OMR answer sheet of other candidate</td>
                <td>{{ $candidate_remarks_summary['wrong_omr'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">15</td>
                <td>Whether any of the candidates indulged in malpractice or any act in violation of the instructions
                    issued by the Commission? If so, the Register number(s) of such candidates</td>
                <td>{{ $candidate_remarks_summary['malpractice'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">16</td>
                <td>Whether any candidates left the examination hall during the examination? If so, the details of such
                    candidate(s) with reasons.</td>
                <td>{{ $candidate_remarks_summary['left_exam'] }}</td>
            </tr>
            <tr>
                <td class="sno-column">17</td>
                <td>Whether any declaration from candidates & Scribes obtained, if so, how many?</td>
                <td>{{ 'No' }}</td>
            </tr>
            <tr>
                <td class="sno-column">18</td>
                <td>Time of packing OMR Answer Sheets and other examination materials</td>
                <td>{{ $finalString }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">19</td>
                <td>Whether the entire counting and packing activities of all the Bundles A (Covers A1 & A2) & B (Covers
                    B1,B2,B3,B4 & B5) have been completely videographed without any break?</td>
                <td>{{ $checklist_videography_data[0]['description'] == 1 ? 'Yes - ' . $checklist_videography_data[0]['inspection_staff'] : 'No' }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">20</td>
                <td>Whether the Videographer has video graphed all the exam rooms during the time of examination
                    covering the entrance and the black board in the classroom, where the REGISTER NUMBERS and the
                    seating arrangement are displayed?</td>
                <td>{{ $checklist_videography_data[1]['description'] == 1 ? 'Yes - ' . $checklist_videography_data[1]['inspection_staff'] : 'No' }}
                </td>
            </tr>



        </table>

        <table class="report-table">
            <thead>
                <tr>
                    <th>No. of candidates</th>
                    <th>No. of Question Papers</th>
                    <th>No. of OMR Answer Sheets</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Actual allotted<br>{{ $session_confirmedhalls }}</td>
                    <td>Total received <br>{{ ($copies['FN']['D1'] ?? 0) + ($copies['AN']['D1'] ?? 0) }}</td>
                    <td>Total received <br>{{ ($copies['FN']['D2'] ?? 0) + ($copies['AN']['D2'] ?? 0) }}
                    </td>
                </tr>




                <tr>
                    <td>Additional<br>{{ $sessionDetails['count'] }}</td>
                    <td>Distributed to candidates<br>{{ $attendanceData['present'] }}</td>
                    <td>Distributed to candidates<br>{{ $attendanceData['present'] }}</td>
                </tr>
                <tr>
                    <td>Total<br>{{ $session_confirmedhalls }}</td>
                    <td>Absent<br>{{ $attendanceData['absent'] }}</td>
                    <td>Absent<br>{{ $attendanceData['absent'] }}</td>
                </tr>
                <tr>
                    <td>Present<br>{{ $attendanceData['present'] }}</td>
                    <td>Defective<br>0</td>
                    <td>Defective<br>0</td>
                </tr>
                <tr>
                    <td>Absent<br>{{ $attendanceData['absent'] }}</td>
                    <td>Balance Unused<br>{{ $balanceUnusedQuestionPapers }}</td>
                    <td>Balance Unused<br>{{ $balanceUnusedOMR }}</td>
                </tr>
                <tr>
                    <td colspan="5"
                        style="font-style: italic; text-align: left; border: 1px solid #ddd; padding: 10px;">
                        ** The details of candidates permitted to appear for the examination in addition to the
                        candidates
                        actually allotted to this venue by the Commission.
                    </td>
                </tr>
            <tbody>
        </table>
        <table class="report-table" id="certificate">
            <caption>Self Declaration</caption>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Certified</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Certified that none of my relative or person known to me has appeared for the above said examination
                    in this venue.</td>
                <td>
                    {{-- Loop through checklist_consolidate_data and find checklist_id 15 --}}
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 14);
                    @endphp
                    {{-- Render the checkbox-like div, checking if 'status' is 'Yes' --}}
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>
                </td>
            </tr>




            <tr>
                <td>2</td>
                <td>Certified that only the candidates were allowed inside the examination hall.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 15);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Certified that all the candidates were permitted to enter the examination venue 30 minutes before
                    the commencement of examination and no candidate was permitted to leave the examination venue before
                    the closure of examination.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 16);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Certified that the candidates were allowed to take only the Memorandum of Admission (Hall Ticket)
                    and black pen and they were not allowed to keep any banned items with them during the examination.
                </td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 17);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>5</td>
                <td>Certified that no other candidates except those given in the attendance sheets have appeared for the
                    examination in this venue.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 18);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>6</td>
                <td>Certified that I was personally present during opening of Question paper.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 19);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>7</td>
                <td>Certified that all the unused question papers and wrappers of Question Paper booklets have been
                    returned in a sealed bundle to the TNPSC.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 20);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>8</td>
                <td>Certified that all the used and unused OMR sheets have been packed in the self adhesive tamper proof
                    covers supplied by the TNPSC.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 21);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>9</td>
                <td>Certified that no used or unused or Spare OMR Answer Sheet has been retained by me.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 22);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>10</td>
                <td>Certified that I was personally present during counting and packing of used OMR Answer Sheets.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 23);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>11</td>
                <td>Certified that the instructions given by TNPSC were followed without any deviation.</td>
                <td>
                    <div
                        class="certificate-checkbox {{ isset($checklist_consolidate_data[25]) && $checklist_consolidate_data[25]['status'] == 'Yes' ? 'checked' : '' }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>12</td>
                <td>Certified that the examination was conducted smoothly without any untoward incident.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 24);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            {{-- <tr>
                <td>13</td>
                <td>Certified that Part 1 and 2 of the used OMR Answer Sheets have been detached and all the used and
                    unused OMR sheets have been packed in the self-adhesive tamper proof covers supplied by the TNPSC
                    before me.</td>
                <td>
                    @php
                    $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 25);
                @endphp
                <div
                    class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                    {{-- Empty div to style as checkbox
                </div>
                    
                </td>
            </tr> --}}
            <tr>
                <td>13</td>
                <td>Certified that Videographer has recorded the entire proceedings till sealing of Bundle-I.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 25);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>14</td>
                <td>Certified that candidates have been permitted to write examination in the subject mentioned in the
                    hall ticket.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 26);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>
            <tr>
                <td>15</td>
                <td>Seating arrangements were made as per the room sketch.</td>
                <td>
                    @php
                        $checklist = collect($checklist_consolidate_data)->firstWhere('checklist_id', 27);
                    @endphp
                    <div
                        class="certificate-checkbox {{ $checklist && $checklist['status'] == 'Yes' ? 'checked' : '' }}">
                        {{-- Empty div to style as checkbox --}}
                    </div>

                </td>
            </tr>


        </table>
    </div>

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
                        <div class="name-space">
                            {{ $user->ci_name && $user->ci_designation ? $user->ci_name . ' - ' . $user->ci_designation : 'N/A' }}
                        </div>
                    </div>
                    <div class="signature-row-inline signature-top-bottom-inline">
                        <div class="signature-label">Phone Number : </div>
                        <div class="name-space">{{ $user->ci_phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
</body>

</html>
