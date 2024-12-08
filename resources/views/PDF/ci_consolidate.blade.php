<!doctype html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil&display=swap" rel="stylesheet">
    <style>
        .docx-wrapper {
            /* padding: 15x 15px; */
            /* Add left and right padding to create some breathing space */
            display: flex;
            flex-flow: column;
            align-items: center;
            width: 100%;
            /* max-width: 800px; */
            /* Set a max width for better control over content size */
            /* margin: 0 auto; */
            /* Center the wrapper horizontally */
            box-sizing: border-box;
            /* Include padding in the width calculation */
        }

        .docx-wrapper>section.docx {
            margin-bottom: 30px;
            width: 100%;
            /* max-width: 800px; */
            /* Ensure sections also conform to the wrapper's max-width */
            box-sizing: border-box;
            /* Include padding in width calculation */
        }


        .docx {
            color: black;
        }

        section.docx {
            box-sizing: border-box;
            display: flex;
            flex-flow: column nowrap;
            position: relative;
            overflow: hidden;
        }

        /* section.docx>article {
            margin-bottom: auto;
        } */

        .docx table {
            border-collapse: collapse;
        }

        .docx table td,
        .docx table th {
            vertical-align: top;
        }

        .docx p {
            margin: 0px;
            min-height: 1em;
        }

        .docx span {
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }

        .docx a {
            color: inherit;
            text-decoration: inherit;
        }
    </style>
    <!--docxjs document theme values-->
    <style>
        .docx {
            --docx-majorHAnsi-font: Cambria;
            --docx-minorHAnsi-font: Calibri;
            --docx-dk1-color: #000000;
            --docx-lt1-color: #FFFFFF;
            --docx-dk2-color: #1F497D;
            --docx-lt2-color: #EEECE1;
            --docx-accent1-color: #4F81BD;
            --docx-accent2-color: #C0504D;
            --docx-accent3-color: #9BBB59;
            --docx-accent4-color: #8064A2;
            --docx-accent5-color: #4BACC6;
            --docx-accent6-color: #F79646;
            --docx-hlink-color: #0000FF;
            --docx-folHlink-color: #800080;
        }
    </style>
    <!--docxjs document styles-->
    <style>
        .docx span {
            font-family: var(--docx-minorHAnsi-font);
            min-height: 15px;
            /* Approx. 11pt to 15px */
            font-size: 15px;
            /* Approx. 11pt to 15px */
        }

        .docx p {}

        .docx p,
        p.docx_normal span {
            font-family: Trebuchet MS;
        }

        .docx table,
        table.docx_tablenormal td {
            /* padding-top: 20px;
            /* Approx. 15pt to 20px */
            /* padding-left: 20px; */
            /* Approx. 15pt to 20px */
            /* padding-bottom: 0px; */
            /* Approx. 0pt to 0px */
            /* padding-right: 7px; */
            /* Approx. 5.4pt to 7px  */
        }

        p.docx_bodytext span {
            min-height: 16px;
            /* Approx. 12px to 16px */
            /* min-width: 50%; */
            /* No change, already in % */
            font-size: 16px;
            /* Approx. 12px to 16px */
            font-family: Trebuchet MS;
        }

        p.docx_title {
            /* margin-top: 5px; */
            /* Approx. 3.7pt to 5px */
            /* margin-left: 190px; */
            /* Approx. 141.8pt to 190px */
            /* margin-right: 135px; */
            /* Approx. 101.8pt to 135px */
            text-align: center;
        }

        p.docx_title span {
            font-weight: bold;
            min-height: 19px;
            /* Approx. 14pt to 19px */
            font-size: 19px;
            /* Approx. 14pt to 19px */
            font-family: Trebuchet MS;
        }

        p.docx_listparagraph span {
            font-family: Trebuchet MS;
        }

        p.docx_tableparagraph {
            margin-top: 2px;
            /* Approx. 1.25pt to 2px */
        }

        p.docx_tableparagraph span {
            font-family: Trebuchet MS;
        }
    </style>


</head>

<body>
    <div class="docx-wrapper">
        <section class="docx">
            <article>
                <p class="docx_title"
                    style="margin-top: 12px; text-indent: 28.9px; margin-left: 79.1px; margin-right: 4.8px;">
                    <span style="font-family: &quot;Nirmala UI&quot;;">
                        <div
                            style="display: block; position: relative; text-indent: 0px; width: 0px; height: 0px; left: 2.2px; top: -5px;">
                            <img src="{{ asset('storage/assets/images/login-logo.png') }}"
                                style="position: relative; left: 0px; top: 0px; width: 109.2px; height: 114.03px;">
                        </div>
                    </span>
                </p>
                <p class="docx_title"
                    style="line-height: 1.15; text-indent: 28.9px; font-size: 22px; margin-left: 79.1px; margin-right: 4.75px;">
                    <span style="font-family: 'Noto Sans Tamil'; font-size: 24px;">தமிழ்நாடு</span>
                    <span style="font-family: Arial; font-size: 24px;"></span>
                    <span style="font-family: 'Noto Sans Tamil'; font-size: 24px;">அரசுப்</span>
                    <span style="font-family: Arial; font-size: 24px;"></span>
                    <span style="font-family: 'Noto Sans Tamil'; font-size: 24px;">பணியாளர்</span>
                    <span style="font-family: 'Noto Sans Tamil'; font-size: 24px;"></span>
                    <span style="font-family: 'Noto Sans Tamil'; font-size: 24px;">தேர்வாணையம்</span>
                </p>

                <p class="docx_title"
                    style="line-height: 1.15; text-indent: 28.9px; margin-left: 79.1px; margin-right: 4.75px;">
                    <span style="font-family: Arial; font-size: 22px;">TAMIL</span>
                    <span style="font-family: Arial; font-size: 22px;"></span>
                    <span style="font-family: Arial; font-size: 22px;">NADU</span>
                    <span style="font-family: Arial; font-size: 22px;"></span>
                    <span style="font-family: Arial; font-size: 22px;">PUBLIC</span>
                    <span style="font-family: Arial; font-size: 22px;"></span>
                    <span style="font-family: Arial; font-size: 22px;">SERVICE</span>
                    <span style="font-family: Arial; font-size: 22px;"></span>
                    <span style="font-family: Arial; font-size: 22px;">COMMISSION</span>
                </p>

                <p
                    style="margin-top: 0.05px; text-indent: 28.9px; margin-left: 43.1px; margin-right: 4.75px; text-align: center;">
                </p>
                <p
                    style="margin-top: 0.05px; text-indent: 28.9px; margin-left: 43.1px; margin-right: 4.75px; text-align: center;">
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">CONSOLIDATED</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">/</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">COMPREHENSIVE</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">REPORT</span>
                </p>
                <p
                    style="margin-top: 0.05px; text-indent: 28.9px; margin-left: 43.1px; margin-right: 4.75px; text-align: center;">
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">to</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">be</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">furnished</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">by</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">the</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">CHIEF</span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;"></span>
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 10px; font-size: 20px; text-decoration: underline;">INVIGILATOR</span>
                </p>
                <p class="docx_bodytext"></p>
                <p class="docx_bodytext" style="margin-top: 0.25pt; margin-bottom: 0.05pt;"></p>
                <table class="first-row last-row first-col last-col" style="width: 100%; table-layout: auto;">
                    <colgroup>
                        <col style="width: 26.9pt;">
                        <col style="width: 268.95pt;">
                        <col style="width: 242.05pt;">
                    </colgroup>
                    <tr style="height: 45pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">1</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Name</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Examination</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.8pt; margin-right: 1.3pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Posts</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">included</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Combined</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Civil</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Services</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Examination-I
                                    (Group-I Services) (16/2020)</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 30.6pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">2</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Date</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp;</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Session</span>
                            </p>
                        </td>

                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">FN</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 30.6pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">3</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Name</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">centre</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp;</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Centre</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Code</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.8pt;">
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">Virudhunagar</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">2901</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 30.6pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">4</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Venue</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Name</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp;</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Venue</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Code</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">KVS</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">HR.</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">SEC.</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">SCHOOL</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">012</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 45pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">5</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Time</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">receiving</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">material</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">at</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">venue</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">07:40</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 59.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">6</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 9.95pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Question</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">paper</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">bundles</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">received</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">were intact
                                    without any damage in respect of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">seals</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">affixed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">on</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">them?</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 30.6pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">7</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Time</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Opening</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Question</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Paper</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Box</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">09:05</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 45pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">8</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 9.95pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">number(s)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">who</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">non-</span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">personalized</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">(with</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">reasons)</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 59.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">9</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">number(s)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">who</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">returned</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Blank</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Sheet</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">without</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">shading</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">answer</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 59.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">10</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 23.05pt; text-align: justify;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register
                                    number(s)of candidates who have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">used pencil for
                                    shading the answers in OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Sheet</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 59.4pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">11</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 20.55pt; text-align: justify;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register number(s)
                                    of candidates who have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">pen</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Other</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">than</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">black</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">ball</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">point</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">pen</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">for</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">shading</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">answers</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Sheet</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 59.4pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">12</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 4.5pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register number(s)
                                    of Differently Abled</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">who</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">appeared</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">exam</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">with</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">assistance</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">scribe</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 73.75pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0.05pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">13</span>
                            </p>
                        </td>
                        <td
                            style="width: 268.95pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 6.35pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">number(s)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">who</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">wrongly seated in
                                    the place of other candidate</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">and/or</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">written</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">exam/used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">sheet</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">other</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidate</span>
                            </p>
                        </td>
                        <td
                            style="width: 242.05pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.05pt; margin-left: 7.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                </table>
                <p></p>
            </article>
            <footer>
                <p class="docx_bodytext" style="line-height: 0.06;">
                    <span></span>
                </p>
            </footer>
        </section>
        <section class="docx" style="width: 100%; min-height: 842pt;">
            <article>
                <table class="first-row last-row first-col last-col" style="width: 100%; table-layout: auto;">
                    <colgroup>
                        <col style="width: 26.9pt;">
                        <col style="width: 53.8pt;">
                        <col style="width: 107.6pt;">
                        <col style="width: 107.6pt;">
                        <col style="width: 134.5pt;">
                        <col style="width: 107.6pt;">
                    </colgroup>
                    <tr style="height: 73.75pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0.05pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">14</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 4.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether any of the
                                    candidates indulged in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">malpractice or any
                                    act in violation of the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">instructions</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">issued</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">by</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">Commission.If</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">so, the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Register
                                    number(s)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">such</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.05pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>


                    </tr>
                    <tr style="height: 59.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">15</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">candidatesleft</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the
                                    examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">hall</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">during</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">examination?</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">if</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">so, the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">details</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">such</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">candidate(s)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">with</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">reasons.</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 45pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">16</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 4.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">declaration</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">from</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp;</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Scribes</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">obtained,</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">if</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">so,</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">how</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">many?</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No</span>
                            </p>
                        </td>

                    </tr>
                    <tr style="height: 160.2pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">17</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 10.1pt; line-height: 1.03; margin-left: 7.85pt; margin-right: 4.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Time</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">packing</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Sheets</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">other</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">materials</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">B4</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">10:44</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.5pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">B2</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">10:46</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">B5</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">10:49</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">BUNDLE</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">II</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">11:39</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">AM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.5pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">A2</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">12:10</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">A1</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">01:11</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">B1</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">01:11</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.5pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">A</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">01:14</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">BUNDLE</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">I</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">01:15</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                            <p class="docx_tableparagraph" style="margin-top: 0.45pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">COVER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">B</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19-11-2022</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">01:17</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">PM</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 59.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">18</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 19.65pt; text-align: justify;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Name</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp;</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Designation</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Inspection</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">staff</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">/</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Oﬃcer deputed by the Commission </span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">/ </span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">District</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Collector</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; line-height: 1.03; margin-left: 7.75pt; margin-right: 50.4pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">MALAR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">KODI</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">R</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">-</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">JUNIOR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">REVENUE</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">INSPECTOR</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 73.8pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0.05pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">19</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 4.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether the entire counting and packing</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">activities of all the Bundles A (Covers A1 &amp; A2)</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">&amp; B (Covers
                                    B1,B2,B3,B4 &amp; B5) have been</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">completely</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">video
                                    graphed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">without</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">break?</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0.2pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.05pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20xt;">Yes</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 102.2pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt 0.75pt 1.5pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.1pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">20</span>
                            </p>
                        </td>
                        <td colspan="3"
                            style="width: 269pt; border-width: 0.75pt 0.75pt 1.5pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 4.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Whether</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span
                                      style="font-family: Arial; min-height: 12px; font-size: 20px;">Videographer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">has</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">video</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">graphed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">all the exam rooms during the time of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">covering</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">entrance</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">black board in the classroom, where the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">REGISTER</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">NUMBERS</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">seating</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">arrangement</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">are</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">displayed?</span>
                            </p>
                        </td>
                        <td colspan="2"
                            style="width: 242.1pt; border-width: 0.75pt 0.75pt 1.5pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0.1pt;"></p>
                            <p class="docx_tableparagraph" style="margin-top: 0pt; margin-left: 7.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Yes</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36.3pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 1.5pt 0.75pt 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 1.5pt 0.75pt 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.8pt; margin-right: 7.35pt; text-align: right;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No.of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 1.5pt 0.75pt 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 1.5pt 0.75pt 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.8pt; margin-left: 6.3pt; margin-right: 5.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No.of Question
                                    Papers</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 1.5pt 0.75pt 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 1.65pt; line-height: 1.03; text-indent: -31.8pt; margin-left: 35.2pt; margin-right: 2.55pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">No.of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Sheets</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 35.95pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; text-indent: 3.55pt; margin-left: 19.75pt; margin-right: 18.75pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Actual</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">allotted</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 9.95pt; margin-right: 9.3pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">300</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 9.9pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Total</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">received</span>
                            </p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 6.3pt; margin-right: 5.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">320</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 9.8pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">310</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 35.95pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 8.45pt; margin-left: 13.15pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Additional</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-right: 7.05pt; text-align: right;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">**</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; text-indent: -7.55pt; margin-left: 24.1pt; margin-right: 15.65pt;">
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 20px;">Distributed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">to</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">candidates</span>
                            </p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 6.3pt; margin-right: 5.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">172</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 9.8pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">172</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.55pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 26.8pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Total</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.95pt; margin-right: 9.3pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">300</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.9pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Absent</span>
                            </p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 6.3pt; margin-right: 5.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">128</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.8pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">128</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.55pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 19.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Present</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.95pt; margin-right: 9.3pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">172</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.95pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Defective</span>
                            </p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 1.3pt; margin-left: 0.5pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">0</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 1.3pt; margin-left: 0.45pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">0</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.55pt;">
                        <td colspan="2"
                            style="width: 80.7pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 21.55pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Absent</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.95pt; margin-right: 9.3pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">128</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.95pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Balance</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">Unused</span>
                            </p>
                        </td>
                        <td
                            style="width: 134.5pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 6.3pt; margin-right: 5.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">148</span>
                            </p>
                        </td>
                        <td
                            style="width: 107.6pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 9.8pt; margin-right: 9.35pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">138</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </article>
        </section>
        <section class="docx" style=" width: 100% ; min-height: 60pt;">
            <article>
                <p class="docx_bodytext" style="margin-left: 5.3pt;">
                    <span style="font-family: Arial; min-height: 10pt; font-size: 10pt;"></span>
                </p>
                <p></p>
            </article>
        </section>
        <section class="docx" style=" width: 100%; min-height:  342pt;">
            <article>
                <p style="margin-top: 4.3pt; margin-left: 5.3pt;">
                    <span
                        style="font-family: Arial; font-weight: bold; min-height: 12px; font-size: 18px;">CERTIFICATE:</span>
                </p>
                <p class="docx_bodytext" style="margin-top: 0.05pt;"></p>
                <table class="first-row last-row first-col last-col" style="width: 100%; table-layout: auto;">
                    <colgroup>
                        <col style="width: 26.9pt;">
                        <col style="width: 457.15pt;">
                        <col style="width: 53.75pt;">
                    </colgroup>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 20px;">1</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 22.65pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed that
                                    none of
                                    my relative or person known to me has appeared for the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">above</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">said</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">this</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">venue.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.55pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">2</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">only</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">were</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">allowed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">inside</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">hall.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 50.4pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">3</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 6.4pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">all</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">were</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">permitted</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">to</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">enter</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">venue</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">45</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">minutes</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">before</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">commencement</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">no</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">candidate</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">was</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">permitted</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">to</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">leave</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">venue</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">before</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">closure</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 50.35pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">4</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 30.1pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed that the
                                    candidates were allowed to take only the Memorandum of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Admission (Hall
                                    Ticket) and black pen and they were not allowed to keep any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">banned</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">items</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">with</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">them</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">during</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">5</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">no</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">other</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">except</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">those</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">given</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">attendance</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">sheets</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">appeared</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">for</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">this</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">venue.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.6pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">6</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">I</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">was
                                    personally</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">present</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">during
                                    opening</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Question
                                    paper.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">7</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 22.65pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed
                                    that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">all</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">unused</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">question</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">papers</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">wrappers
                                    of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Question</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Paper</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">booklets</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">been</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">returned</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">a</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">sealed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">bundle</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">to</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">TNPSC.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 35.95pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">8</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">all</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">unused</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">sheets</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">been</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">packed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">self</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">adhesive</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">tamper</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">proof</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">covers</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">supplied</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">by</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">TNPSC.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.7pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">9</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 6.4pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">no</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">or</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">unused</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">or</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Spare</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Sheet</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">has</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">been</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">retained</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">by</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">me.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">10</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">I</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">was</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">personally</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">present</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">during</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">counting</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">and</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">packing</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">used</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">OMR</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Answer</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Sheets.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">11</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 22.65pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">instructions</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">given</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">by</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">TNPSC</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">were</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">followed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">without</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">deviation.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">12</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 22.65pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">was</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">conducted</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">smoothly</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">without</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">any</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">untoward</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">incident.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 50.4pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">13</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 14.6pt; text-align: justify;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed that
                                    Part 1
                                    and 2 of the used OMR Answer Sheets have been detached</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">and all the used
                                    and
                                    unused OMR sheets have been packed in the self-adhesive</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">tamper</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">proof</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">covers</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">supplied</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">by</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">TNPSC</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">before</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">me.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-top: 0pt;"></p>
                            <p class="docx_tableparagraph"
                                style="margin-top: 0pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 35.95pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">14</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="line-height: 1.03; margin-left: 7.85pt; margin-right: 27.3pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed that
                                    Videographer has recorded the entire proceedings till sealing of</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Bundle-I.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 36pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">15</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="line-height: 1.03; margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Certiﬁed</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">that</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">candidates</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">have</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">been</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">permitted</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">to</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">write</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">examination</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">subject</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">mentioned</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">in</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">hall</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">ticket.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-top: 8.45pt; margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 21.55pt;">
                        <td
                            style="width: 26.9pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph"
                                style="margin-left: 5.5pt; margin-right: 4.8pt; text-align: center;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">16</span>
                            </p>
                        </td>
                        <td
                            style="width: 457.15pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 7.85pt;">
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">Seating</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span
                                    style="font-family: Arial; min-height: 12px; font-size: 18px;">arrangements</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">were</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">made</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">as</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">per</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">the</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">room</span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;"></span>
                                <span style="font-family: Arial; min-height: 12px; font-size: 18px;">sketch.</span>
                            </p>
                        </td>
                        <td
                            style="width: 53.75pt; border-width: 0.75pt; border-style: solid; border-color: rgb(0, 0, 0); padding-left: 0pt; padding-right: 0pt;">
                            <p class="docx_tableparagraph" style="margin-left: 0.75pt; text-align: center;">
                                <span
                                    style="font-family: &quot;Segoe UI Symbol&quot;; min-height: 12px; font-size: 18px;">✔</span>
                            </p>
                        </td>
                    </tr>
                </table>
                <p class="docx_bodytext" style="margin-top: 0.45pt;"></p>
                <p style="margin-top: 0.05pt; margin-left: 7.9pt;">
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Signature</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">
                        <span class="docx-tab-stop"></span>: </span>
                </p>
                <p style="margin-top: 8.6pt; margin-left: 7.9pt;">
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Name</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">
                        <span class="docx-tab-stop"></span>: </span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Mr.</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">K.C.</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">KUMARAN</span>
                </p>
                <p style="margin-top: 8.65pt; margin-left: 7.9pt;">
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Designation</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">
                        <span class="docx-tab-stop"></span>: </span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">PG.</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">ASST.</span>
                </p>
                <p style="margin-top: 8.6pt; margin-left: 7.9pt;">
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Mobile</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Number</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">
                        <span class="docx-tab-stop"></span>9487415484 </span>
                </p>
                <p style="margin-top: 8.65pt; margin-left: 7.9pt;">
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">School</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">/</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Oﬃce</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;"></span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">Seal</span>
                    <span style="font-family: Arial; min-height: 11.5pt; font-size: 11.5pt;">
                        <span class="docx-tab-stop"></span>: </span>
                </p>
            </article>
        </section>
    </div>
</body>

</html>
