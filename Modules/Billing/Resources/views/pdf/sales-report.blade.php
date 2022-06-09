<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-size: small;
        }
        @page {
            margin-top: 35mm;
            margin-bottom:30mm;
        }
        @media print {           
            .main-body {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
        table {
            page-break-inside: auto
        }
            
        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }


        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            bottom: 0;
        }

        h2, p {
            display: flex;
            justify-content: center;
        }
        .heading {
            display:flex;
            justify-content: space-around;
            align-items: center;
        }
        .heading-2 , .heading-2 td{
            width: 80px;
        }
        .custom-height {
            height: 60px;
        }
        .boldtext {
            font-weight: "600";
        }
        .right-align {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="main-body">
        <h2>बिक्री खाता</h2>
        <p>(नियम २३ को उपनियम (१) को खण्ड  (ज) संग सम्बन्धित ) </p>
        <table width="100%">
            <tr>
                <th colspan="15">
                    <div class="heading">
                        <span>करदाता दर्ता नं (PAN) : {{ Options::get('hospital_pan')}}</span>
                        <span>करदाताको नाम: {{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</span>
                        <span>साल: {{$year}}</span>
                        <span>महिना: {{$month}}</span>
                        <span>कर अवधि: {{$taxduration->fldname}}</span>
                    </div>                
                </th>
            </tr>
            <tr>
                <th colspan="7" class="custom-height">बीजक</th>
                <td rowspan="2" class="heading-2">जम्मा बिक्री / निकासी (रु)</td>
                <td rowspan="2" class="heading-2">स्थानीय कर छुटको बिक्री  मूल्य (रु)</td>
                <th colspan="2">करयोग्य बिक्री</th>
                <th colspan="4">निकासी</th>
            </tr>
            <tr class="heading-2">
                <td>मिति</td>
                <td>बीजक नं.</td>
                <td>खरिदकर्ताको नाम</td>
                <td>खरिदकर्ताको स्थायी लेखा नम्बर</td>
                <td>वस्तु वा सेवाको नाम</td>
                <td>वस्तु वा सेवाको परिमाण</td>
                <td>वस्तु वा सेवाको परिमाण मापन गर्ने इकाइ</td>
                <td class="right-align">मूल्य (रु)</td>
                <td class="right-align">कर (रु)</td>                
                <td>निकासी गरेको वस्तु वा सेवाको मूल्य (रु)</td>
                <td class="right-align">निकासी गरेको देश</td>
                <td class="right-align">निकासी प्रज्ञापनपत्र नम्बर</td>
                <td class="right-align">निकासी प्रज्ञापनपत्र मिति</td>
            </tr>
    
            

            @if(isset($salesdata) and count($salesdata) > 0)
                @foreach($salesdata as $report)
                    @php
                        $nepalidate = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($report->fldtime)));
                    @endphp
                    <tr>
                        <td>{{$nepalidate->year}}-{{$nepalidate->month}}-{{$nepalidate->date}}</td>
                        <td>{{$report->fldbillno}}</td>
                        <td>{{strtoupper($report->patientname)}}</td>
                        <td>{{$report->fldpannumber}}</td>
                        <td>{{$report->flditemname}}</td>
                        <td>{{$report->flditemqty}}</td>
                        <td></td>
                        <td>{{\App\Utils\Helpers::numberFormat(($report->flditemrate*$report->flditemqty))}}</td>
                        @if($report->fldtaxamt !=0)
                            <td></td>
                            <td>{{\App\Utils\Helpers::numberFormat(($report->fldditemamt-$report->fldtaxamt-$report->flddiscamt))}}</td>
                            <td>{{\App\Utils\Helpers::numberFormat($report->fldtaxamt)}}</td>
                        @else
                            <td>{{\App\Utils\Helpers::numberFormat($report->fldditemamt)}}</td>
                            <td></td>
                            <td></td>
                        @endif
                        <td>{{\App\Utils\Helpers::numberFormat($report->flditemrate)}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</body>
</html>