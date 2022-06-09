<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>

    <tr>
        <th>Sales Report</th>
    </tr>
    
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th colspan="2">करदाता दर्ता नं (PAN) :</th>
        <th><b>{{ Options::get('hospital_pan')}}</b></th>
        <th></th>
        <th></th>
        <th>करदाताको नाम:</th>
        <th><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        <th></th>
        <th></th>
        <th>साल …</th>
        <th><b>{{$year}}</b></th>
        <th></th>
        <th>महिना …</th>
        <th><b>{{$month}}</b></th>
        <th></th>
        <th></th>
        <th>कर अवधि: …</th>
        <th><b>{{ isset($taxduration) ? $taxduration->fldname : ''}}</b></th>
        
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

    </thead>
    <tbody>
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
    </tbody>
</table>
