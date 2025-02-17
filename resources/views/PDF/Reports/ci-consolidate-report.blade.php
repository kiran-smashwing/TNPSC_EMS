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
        @php
            $checklist_videography_data = null;
            $checklist_consolidate_data = null;
        @endphp
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
                <td>
                    {{ $timeReceivingMaterial ? \Carbon\Carbon::parse($timeReceivingMaterial->received_at)->format('d-m-Y h:i A') : 'N/A' }}
                </td>
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
                <td>
                    {{ isset($qpboxTimeLog['qp_timing_log']['qp_box_open_time'])
                        ? \Carbon\Carbon::parse($qpboxTimeLog['qp_timing_log']['qp_box_open_time'])->format('d-m-Y h:i A')
                        : 'N/A' }}
                </td>

            </tr>
            <tr>
                <td class="sno-column">8</td>
                <td>Register number(s) of candidates who have used non-personalised OMR (with reasons)</td>
                <td>{{ $nonPersonalisedOMRCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">9</td>
                <td>Register number(s) of candidates who have returned Blank OMR Answer Sheet without shading any answer
                </td>
                <td>{{ $blankOMRSheetCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">10</td>
                <td>Register number(s) of candidates who have used pencil for shading the answers in OMR Answer Sheet
                </td>
                <td>{{ $usedPencilCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">11</td>
                <td>Register number(s) of candidates who have used pen Other than black ball point pen for shading the
                    answers in OMR Answer Sheet</td>
                <td>{{ $usedOtherPenCanidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">12</td>
                <td>Register number(s) of Differently Abled candidates who appeared in the exam with the assistance of
                    scribe</td>
                <td>{{ isset($formattedScribes) && is_array($formattedScribes) ? implode(', ', $formattedScribes) : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">13</td>
                <td>Register number(s) of candidates who have wrongly seated in the place of other candidate </td>
                <td>{{ $wronglySeatedCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">14</td>
                <td>Register number(s) of candidates written the exam/used the OMR answer sheet of other candidate</td>
                <td>{{ $usedOMRofOtherCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">15</td>
                <td>Whether any of the candidates indulged in malpractice or any act in violation of the instructions
                    issued by the Commission? If so, the Register number(s) of such candidates</td>
                <td>{{ $indulgedMalpracticeCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">16</td>
                <td>Whether any candidates left the examination hall during the examination? If so, the details of such
                    candidate(s) with reasons.</td>
                <td>{{ $leftTheExamHallCandidates ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="sno-column">17</td>
                <td>Whether any declaration from candidates & Scribes obtained, if so, how many?</td>
                <td>{{ 'No' }}</td>
            </tr>
            <tr>
                <td class="sno-column">18</td>
                <td>Time of packing OMR Answer Sheets and other examination materials</td>
                <td>{{ isset($formattedBundles) && is_array($formattedBundles) ? implode(', ', $formattedBundles) : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">19</td>
                <td>Whether the entire counting and packing activities of all the Bundles A (Covers A1 & A2) & B (Covers
                    B1, B2, B3, B4 & B5) have been completely videographed without any break?</td>
                <td>
                    {{ isset($videographyAnswer['checklist'][1]) && $videographyAnswer['checklist'][1]['value'] == '1' ? 'Yes - ' . ($videographyAnswer['checklist'][1]['remark'] ?? 'No Remark') : 'No' }}
                </td>
            </tr>
            <tr>
                <td class="sno-column">20</td>
                <td>Whether the Videographer has videographed all the exam rooms during the time of examination covering
                    the entrance and the blackboard in the classroom, where the REGISTER NUMBERS and the seating
                    arrangement are displayed?</td>
                <td>
                    {{ isset($videographyAnswer['checklist'][2]) && $videographyAnswer['checklist'][2]['value'] == '1' ? 'Yes - ' . ($videographyAnswer['checklist'][2]['remark'] ?? 'No Remark') : 'No' }}
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
                    <td>Actual allotted<br>{{ $allocatedCount ?? '0' }}</td>
                    <td>Total received <br>{{ $totalQPsReceived ?? '0' }}</td>
                    <td>Total received <br>{{ $totalOMRsReceived ?? '0' }}</td>
                </tr>
                <tr>
                    <td>Additional<br>{{ $totalAdditionalCandidates ?? '0' }}</td>
                    <td>Distributed to candidates<br>
                        {{ (isset($candidateAttendance['present']) ? $candidateAttendance['present'] : 0) + ($totalAdditionalCandidates ?? 0) }}
                    </td>

                    <td>Distributed to candidates<br>
                        {{ (isset($candidateAttendance['present']) ? $candidateAttendance['present'] : 0) + ($totalAdditionalCandidates ?? 0) }}
                    </td>

                </tr>
                <tr>
                    <td>Total<br>{{ $allocatedCount + $totalAdditionalCandidates ?? '0' }}</td>
                    <td>Absent<br>{{ $candidateAttendance['absent'] ?? 0 }}</td>
                    <td>Absent<br>{{ $candidateAttendance['absent'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Present<br>{{ $candidateAttendance['present'] ?? 0 }}</td>
                    <td>Defective<br>{{ $totalPaperReplacements ?? 0 }}</td>
                    <td>Defective<br>{{ $totalPaperReplacements ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Absent<br>{{ $candidateAttendance['absent'] ?? 0 }}</td>
                    <td>Balance Unused<br>
                        {{ ($totalQPsReceived ?? 0) - (isset($candidateAttendance['present']) ? $candidateAttendance['present'] : 0) - ($totalAdditionalCandidates ?? 0) }}
                    </td>
                    <td>Balance Unused<br>
                        {{ ($totalOMRsReceived ?? 0) - (isset($candidateAttendance['present']) ? $candidateAttendance['present'] : 0) - ($totalAdditionalCandidates ?? 0) }}
                    </td>

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
        @php
            // Parse the JSON string from consolidate_answer into an array
            $checklistAnswers = $consolidateAnswer->consolidate_answer['checklist']?? [];
        @endphp

        <table class="report-table" id="certificate">
            <caption>Self Declaration</caption>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Certified</th>
            </tr>
            @foreach ($consolidateChecklist as $index => $checklist)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $checklist->ci_checklist_description }}</td>
                    <td>
                        <div class="certificate-checkbox {{ isset($checklistAnswers[$checklist->ci_checklist_id]) && $checklistAnswers[$checklist->ci_checklist_id] == '1' ? 'checked' : '' }}">
                            {{-- Empty div to style as checkbox --}}
                        </div>
                        
                    </td>
                </tr>
            @endforeach
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
