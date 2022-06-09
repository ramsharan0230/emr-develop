<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Birth Certificate</title>
</head>

<body style="text-align: center;">
    <table class='header_table' style="text-align: left; width: 100%; font-size:10pt;" border="0" cellpadding="1" cellspacing="0" id="headtable">
        <tr>
            <br>
            <br>
            <td style="vertical-align: top; text-align: left;">
                <img src="{{ asset('uploads/config/'.(Options::get('brand_image'))??'') }}" alt="" width="100" height="100"/>
                <br>
                <br><b>
                    Hospital No: 616</b>
                <br><b>
                    Certificate No:167</b>
            </td>
            <td style="vertical-align: top; text-align: center;">
                <h2 style="text-align: center;">{{ (Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h2>
                <h4 style="text-align: center;">{{ (Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;">{{ (Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <h4> &#x260E; 4-412430
                    <br>4-412530<br>
                    Ext: 100, 101 <br>
                    Fax: 4-435153 <br>
                    P.O. Box: 21147</h4>
                <h5>Email: nph@nepalpolice.gov.np
                    <br>Maharajgunj, Kathmandu,Nepal</h5>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; text-align: center;">
            </td>
            <td style="vertical-align: top; text-align: center;">
                <h1><u>BIRTH CERTIFICATE</h1>
            </td>
        </tr>
    </table>
    <table class='header_table' style="text-align: left; width: 100%; font-size:20pt;border=" 0" cellpadding="1" cellspacing="0" id="headtable">
        <tr>
            <td style="vertical-align: top; text-transform: uppercase;">
                {!!  $certificate !!}
            </td>
{{--            <td style="vertical-align: top; text-transform: uppercase; ">--}}
{{--                THIS IS TO CERTIFY THAT A <b>{{ $childdata->patientinfo->fldptsex }} BABY</b> WAS BORN TO<b> MRS. {{ $motherinfo->fldptnamefir }} {{ $motherinfo->fldptnamelast }},</b> WIFE OF<b> MR. {{ $motherinfo->fldptguardian ?: '-' }},</b> PERMANENT RESIDENT OF <b>{{ $motherinfo->fldptaddvill }} {{ $motherinfo->fldptadddist }}</b> ON <b>{{ $childdata->flddeltimenepali }}</b> IN NEPAL POLICE HOSPITAL, KATHMANDU, NEPAL.--}}
{{--            </td>--}}
        </tr>
    </table>
    <br>
    <br>
    <table class='header_table' style="text-align: left; width: 100%; font-size:17pt;border=" 0" cellpadding="1" cellspacing="0" id="headtable">
        <tr>
            <td style="vertical-align: top; text-align: left;">
                <b> BIRTH TIME:</b> - {{ explode(' ', $childdata->patientinfo->fldptbirday)[1] }}
                <br>
                <b>BIRTH WEIGHT:</b> - {{ $childdata->flddelwt }}
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table class='header_table' style="text-align: left; width: 100%; font-size:17pt;border=" 0" cellpadding="1" cellspacing="0" id="headtable">
        <tr>
            <td style="vertical-align: top; text-align: left;">
                <b>DATE:</b> {{ \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d'))->full_date }}
            </td>
            <td style="vertical-align: top; text-align: right;">------------------------
                <br>Signature of Doctor
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table class='header_table' style="text-align: left; width: 100%; font-size:17pt;border=" 0" cellpadding="1" cellspacing="0" id="headtable">
        <tr>
            <td style="vertical-align: top; text-align: left;">
                IDENTIFICATION
            </td>
            <td style="vertical-align: top; text-align: center;">CHECKED &#9745;
            </td>
        </tr>
    </table>
</body>

</html>
