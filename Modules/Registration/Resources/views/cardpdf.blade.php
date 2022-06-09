<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration Card</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-size: 14px;
            width:100%;
            height: 100%;
        }

        .wrapper {
            width: 300px;
            position: relative;
        }

        table td {
            line-height: 10px;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="wrapper" style="page-break-inside:avoid;">
        <img src="{{ asset('new/images/ID-card.jpg') }}" alt="" style="width: 100%; position: relative;">
        <table style="position: absolute; top: 10px; left: 0px; width: 300px;">
            <tbody>
                <tr>
                    <td align="center"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" style="height: 70px;"></td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 120px; left: 10px; width: 300px;">
            <tbody>
                <tr>
                    <td align="center" style="font-size: 16px; color: #000000; font-weight: bold;">{{ Options::get('system_patient_rank')  == 1 && (isset($encounter)) && (isset($encounter->fldrank) ) ?$encounter->fldrank:''}} {{ $patient->fldtitle}} {{ $patient->fldptnamefir}} {{ $patient->fldmidname}} {{$patient->fldptnamelast}} </td>
                </tr>
                <tr>
                    <td align="center" style="font-size: 12px; color: #000000;">{{$encounter->fldpatientval}}</td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 180px; left: 60px; width: 300px;">
            <tbody>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size: 12px; color: #000000;  font-weight: bold;" width="40">Gender</td>
                                <td style="font-size: 12px; color: #000000;">: {{$patient->fldptsex}} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size: 12px; color: #000000;  font-weight: bold;" width="40">Address</td>
                                <td style="font-size: 12px; color: #000000;">: {{$patient->fldptaddvill}} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size: 12px; color: #000000;  font-weight: bold;" width="40">D.O.B.</td>
                                <td style="font-size: 12px; color: #000000;">: {{$patient->fldptbirday}} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size: 12px; color: #000000;  font-weight: bold;" width="40">Issue Date</td>
                                <td style="font-size: 12px; color: #000000;">: {{$encounter->fldregdate}} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size: 12px; color: #000000;  font-weight: bold;" width="40">Contact No.</td>
                                <td style="font-size: 12px; color: #000000;">: {{$patient->fldptcontact}} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 345px; left: 115px; width: 30px;">
            <tbody>
                <tr>
                    <td align="center">{!! Helpers::generateQrCode($patient->fldpatientval)!!}</td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 570px; left: 0px; width: 300px;">
            <tbody>
                <tr>
                    <td align="center"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" style="height: 40px;"></td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 640px; left: 50px; width: 300px;">
            <tbody>
                <tr>
                    <td style="font-size: 12px; color: #000000; font-weight: bold;">{{ (isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'') }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #000000;">{{ (isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'') }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #000000;"><span style="font-weight: bold;">Tel: </span> {{ Options::get('system_telephone_no') }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #000000;"><span style="font-weight: bold;">Email:</span>{{  Options::get('system_feedback_email') }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #000000;">{{ (isset(Options::get('siteconfig')['hospital_code'])?Options::get('siteconfig')['hospital_code']:'')  }}</td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 780px; left: 115px; width: 30px;">
            <tbody>
                <tr>
                    <td align="center">{!! Helpers::generateQrCode($patient->fldpatientval)!!}</td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 870px; left: 15px; width: 280px;">
            <tbody>
                <tr>
                    <td align="center" style="font-size: 14px; font-weight: bold; color: #2e65f6; line-height: 12px;">KINDLY BRING THIS CARD EACH TIME YOU VISIT</td>
                </tr>
            </tbody>
        </table>
        <table style="position: absolute; top: 915px; left: 15px; width: 280px;">
            <tbody>
                <tr>
                    <td align="center" style="font-size: 12px; color: #000000; line-height: 12px;">This card shall remain the property of Hospital. If found kindly inform us.</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
<php die(); ?>

