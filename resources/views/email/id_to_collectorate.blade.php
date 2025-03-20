<!DOCTYPE html>
<html lang="ta">

<head>
    <meta charset="UTF-8" />
    <title>ID to Collectorate</title>
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

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .meeting-title {
            background-color: #E3F1EE;
            /* background-color: #f0f0f0; */
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .date-ref {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .sender-info,
        .recipient-info {
            max-width: 45%;
        }

        .recipient-info {
            text-align: right;
        }

        .subject {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .separator {
            text-align: center;
            margin: 15px 0;
        }

        .content {
            margin-bottom: 30px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }

        .report-table th {
            background-color: #e3f1ee;
            text-align: left;
            font-weight: bold;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .signature {
            text-align: right;
        }

        .centered-footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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
    <img src={{ asset('storage/assets/images/watermark.png') }} alt="Watermark" class="watermark" />

    <div class="header-container">
        <div class="logo-container">
            <img src={{ asset('storage/assets/images/watermark.png') }} alt="Logo" class="logo-image" />
        </div>
        <div class="header-content">
            <h3>தமிழ்நாடு அரசுப்பணியாளர் தேர்வாணையம்</h3>
            <h5>TAMIL NADU PUBLIC SERVICE COMMISSION</h5>
        </div>
    </div>
    <div class="meeting-title">
        <h5>தேர்வுகள் / அவசரம் <br>மின்னஞ்சல்</h5>
    </div>
    <div class="header">

    </div>

    <div class="date-ref">
        <div><b> கடித எண்:</b> 32131241243324</div>
        <div><b> நாள்:</b> 27.04.2022 </div>
    </div>

    <div class="info-row">
        <div class="sender-info">
            <p>
                <b>அனுப்புநர்</b> <br />
                திரு. கிரண் குராலா, இ.ஆ.ப.,<br />
                தேர்வுக் கட்டுப்பாட்டு அலுவலர்,<br />
                தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்,<br />
                தேர்வாணையச்சாலை,<br />
                சென்னை &ndash; 600 003
            </p>
        </div>

        <div class="recipient-info">
            <p>
                <b>பெறுநர்</b> <br />
                மாவட்ட ஆட்சியாளர்கள் அவர்களுக்கு,<br />
                சென்னை
            </p>
        </div>
    </div>

    <div class="salutation">
        <p>அய்யா / அம்மையீர்,</p>
    </div>

    <div class="subject">
        <p>
            பொருள்: தேர்வுகள் - தேர்வின் பெயர் -தேர்வு நடத்த தேர்வுக்
            கூடங்கள் தெரிவு செய்தல் மற்றும் முதன்மை கண்காணிப்பாளர்கள்
            நியமனம் செய்தல் தொடர்பாக.
        </p>
    </div>

    <div class="separator">
        <p>*******</p>
    </div>

    <div class="content">
        <p>
            தேர்வின் பெயர் யில் அடங்கிய பதவிகளுக்கான நேரடி நியமனத்திற்கான
            அறிவிப்பு (அறிவிக்கை எண் 02/2022 தேர்வாணையத்தால், 21-01-2022
            அன்று வெளியிடப்பட்டது.)
        </p>

        <p>
            2. இத்தேர்வினை நடத்துவதற்கு தேர்வுக் கூடங்கள் தெரிவு செய்தல்,
            முதன்மைக் கண்காணிப்பாளர்கள் நியமனம் செய்தல் போன்ற
            முன்னேற்பாடுகளை மேற்கொள்ளுமாறு கேட்டுக்கொள்கிறேன். இத்தேர்வு
            நடைபெறும் நாள் மற்றும் தேர்வெழுதும் விண்ணப்பதாரர்களின் எண்ணிக்கை
            ஆகிய தகவல்கள் கீழே கொடுக்கப்பட்டுள்ளன.
        </p>

        <table class="report-table">
            <tr>
                <th>தேர்வின் பெயர்</th>
                <th>தேர்வு மையத்தின் பெயர்</th>
                <th>தேர்வு நாள் மற்றும் வேளை</th>
                <th>விண்ணப்பதாரர்களின் எண்ணிக்கை (தோராயமாக)</th>
            </tr>
            <tr>
                <td>தேர்வின் பெயர்</td>
                <td>சென்னை</td>
                <td>30-04-2022 ( முற்பகல் மற்றும் பிற்பகல்)</td>
                <td>100</td>
            </tr>
        </table>

        <p>
            3. தேர்வாணையத் தேர்வுகளின் தலைமை ஒருங்கிணைப்பாளரான தாங்கள்,
            தேர்வாணையத்தால் பிரத்தியேகமாக தயாரிக்கப்பட்டுள்ள இணைய செயலி
            மூலமாக தேர்வுக் கூடங்களைத் தெரிவு செய்து, அதன் வாயிலாகவே,
            பள்ளிகளின் தலைமையாசிரியர்களி டமிருந்து ஒப்புதல் பெற்று,
            விவரங்களை வழங்குமாறு தங்களை கேட்டுக்கொள்கிறேன். இணைய செயலியை
            பயன்படுத்துவதற்கான பயனாளர் குறியீடு (User Name) : --email--
            மற்றும் கடவுச்சொல் (Password): 1234. இணைய செயலியின் இணைப்பு
            [link]:
            https://demo.klabstechindia.com/TNPSC_EMS/public/tnpsc_exams
        </p>

        <p>
            4. மேலும், தங்களால் தெரிவு செய்யப்படும் தேர்வுக் கூடங்களில்,
            கீழ்கண்ட வசதிகள் உள்ளனவா என்பதை உறுதி செய்து கொள்ளுமாறும்
            கேட்டுக்கொள்கிறேன்:-
        </p>
        <ol>
            <li>தண்ணீர் மற்றும் கழிப்பிட வசதிகள்</li>
            <li>போதிய தளவாடங்கள் (Furniture)</li>
            <li>வெளிச்சம் மற்றும் மின் வசதி</li>
            <li>போதிய காற்றோட்ட வசதி</li>
            <li>தேர்வுக் கூடங்களுக்கு செல்ல போதிய பேருந்து/இரயில் வசதி</li>
            <li>
                தேர்வுக் கூடங்கள், முதன்மை கண்காணிப்பாளர்கள் மற்றும் அறை
                கண்காணிப்பாளர்களைக் கீழ்க்கண்டவாறு தெரிவு செய்யுமாறு
                கேட்டுக்கொள்ளப் பணிக்கப்பட்டுள்ளேன்.
            </li>
        </ol>

        <table class="report-table">
            <tr>
                <td>
                    ஒவ்வொரு தேர்வுக் கூடத்திலும்(Venue) அனுமதிக்கப்பட
                    வேண்டிய விண்ணப்பதாரர்களின் எண்ணிக்கை
                </td>
                <td>300 முதல் 400 வரை</td>
            </tr>
            <tr>
                <td>
                    ஒவ்வொரு அறையிலும் (Rooms)தேர்வு எழுதும்
                    விண்ணப்பதாரர்களின் எண்ணிக்கை
                </td>
                <td>20</td>
            </tr>
            <tr>
                <td>முதன்மை கண்காணிப்பாளர் (Chief Invigilator)</td>
                <td>
                    300 முதல் 400 தேர்வர்களுக்கு. ஒரு முதன்மை கண்காணிப்பாளர்
                    நியமிக்கப்படுவதுடன், இருப்புப் பட்டியலில் (Reserve List)
                    ஒரு முதன்மை கண்காணிப்பாளர் வைக்கப்பட வேண்டும். முதன்மை
                    கண்காணிப்பாளர்கள் அந்தந்தப் பள்ளியின் /கல்லூரியின்
                    தலைமையாசிரியராகவோ, உதவி தலைமையாசிரியராகவோ, முதல்வராகவோ,
                    உதவி முதல்வராகவோ அல்லது அவர்களால் பரிந்துரைக்கப்படும்
                    அப்பள்ளியைச் சேர்ந்த ஆசிரியராகவோ இருக்க வேண்டும்.
                </td>
            </tr>
            <tr>
                <td>அறைக் கண்காணிப்பாளர்கள் (Invigilators)</td>
                <td>
                    ஒவ்வொரு அறைக்கும் அதாவது 20 தேர்வர்களுக்கு ஒரு அறை
                    கண்காணிப்பாளர் நியமிக்கப்படவேண்டும். அறை
                    கண்காணிப்பாளர்கள் அப்பள்ளியில் பணிபுரியும் ஆசிரியர்களாக
                    இருக்க வேண்டும். 1 அல்லது 2 அறை கண்காணிப்பாளர்கள்
                    இருப்புப் பட்டியலில் வைக்கப்பட வேண்டும்.
                </td>
            </tr>
        </table>

        <p>
            6. மேலும், இணைப்பில் உள்ள 23 கலம் கொண்ட படிவத்தில், முதன்மைக்
            கண்காணிப்பாளர் பெயர், பதவியின் பெயர், கைபேசி எண், தேர்வுக்
            கூடத்தின் பெயர், முழு முகவரி மற்றும் நில எல்லை குறி (Land Mark)
            ஆகியவற்றை, ஆங்கிலத்தில், Excel Formatல் tnpscida@gmail.com என்ற
            மின்னஞ்சல் முகவரிக்கும் மற்றும் தபால் வழியாகவும்
            தேர்வாணைத்திற்கு அனுப்பி வைக்கும்படி கேட்டுக்கொள்கிறேன்.
            தேர்வுக் கூடங்களுக்கு வாடகை அளிப்பது தொடர்பாக அவை அரசு/அரசு உதவி
            பெறும் அல்லது தனியார் நிறுவனம் என்னும் விவரத்தையும்
            குறிப்பிடும்படியும் கேட்டுக்கொள்கிறேன்.
        </p>

        <p>
            7. தற்போது, தேர்விற்கான கால அவகாசம் மிகக் குறைவாக இருக்கின்ற
            காரணத்தினால், தேர்வுக்கூடம் மற்றும் முதன்மைக்
            கண்காணிப்பாளர்களின் பட்டியல் மிக விரைவாக தேவைப்படும் சூழல்
            எழுந்துள்ளதால் காலதாமத்தைத் தவிர்க்குமாறும் கேட்டுக்கொள்கிறேன்.
            இது தொடர்பாக தங்களின் மேலான ஒத்துழைப்பைப் பெரிதும்
            எதிர்பார்க்கிறேன்.
        </p>
    </div>

    <div class="footer-row">
        <p>
            இணைப்பு<br />
            23 கலம் கொண்ட படிவம்
        </p>

        <p class="signature">
            ஒம்/- தேர்வுக் கட்டுப்பாட்டு அலுவலர் <br />
            பிரிவு அலுவலர்
        </p>
    </div>

    <div class="centered-footer">
        <p>
            தொலைபேசி: 044-25300440, 303, 302 மின்னஞ்சல் முகவரி
            tnpscida@gmail.com
        </p>
    </div>
</body>

</html>
