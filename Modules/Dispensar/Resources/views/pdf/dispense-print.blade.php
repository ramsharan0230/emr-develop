@extends('inpatient::pdf.layout.main')

@section('title')
PHARMACY BILL
@endsection

@section('content')
{{-- <style>
        @page {
            margin: 4mm 0 1mm;
        }

        body {
            margin: 0 auto;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 95%;
            text-align: center;
           margin:3px auto;
        }

        p {
            margin-top: 1px;
            margin-bottom: 5px;
        }
        .a4 {
            width: auto;
            margin: 0 auto;
        }

        .footer {
            position: absolute;
            width: 100%;
            text-align: center;
            margin-top: -40px;
            padding:0;
        }

        .bar-code {
            width: 200px;
            height: 25px;
            margin-top: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border:1px solid #000;
        }

        .pdf-container{
        margin: 0 auto;
        width: 95%;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body table {
            page-break-inside: auto
        }

        .content-body tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        .content-body td:nth-child(1),
        .content-body th:nth-child(1),
        .content-body td:nth-child(2),
        .content-body th:nth-child(2),
        .content-body th:nth-child(3),
        .content-body td:nth-child(3) {
            text-align: left;

        }


        .content-body td,
        .content-body th {
            border: 1px solid #000;
            font-size: 13px;
            text-align: right;
            padding-right:4px;
        }

        h2,
        h4 {
            line-height: 0.5rem;
        }

        ul {
            float: right;
            padding: 0;
            margin: 0;
        }

        ul li {
            text-align: right;
            ;
            list-style: none;

        }

        ul li span:first-child {
            text-align: left;
        }

        ul li span:nth-child(2) {
            text-align: right;
            width: 150px;
            display: inline-block;
        }
    </style>
    <header>
    <table style="width: 95%;margin:0 auto;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt=""  style="max-width:100%"/></td>
            <td style="width:60%;">
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                <h4 style="text-align: center;margin-top:4px;margin-bottom:7px;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;margin-top:4px;margin-bottom:7px;">Pharmacy Unit</h4>
                <h4 style="text-align: center;margin-top:4px;margin-bottom:7px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
               <h4 style="text-align: center;margin-top:4px;margin-bottom:0;">Contact No : {{Options::get('system_telephone_no')}}</h4>
            </td>
            <td style="width: 20%;"></td>
        </tr>
        </tbody>
    </table>
</header> --}}
<div class="a4">
    @php
        $fldbillno = $billNumberGeneratedString;
        $bill_type = Options::get('Pharmacy-Billing-Header');
        $bill_total = Options::get('Pharmacy-Billing-Total');
    @endphp
    @if(isset($depositDetail) and !empty($depositDetail))
        @php
            $totalsum = 0;
        @endphp
        @foreach($depositDetail as $k => $deposit)
            @php
                $totalsum += $deposit->fldreceivedamt;
            @endphp

        @endforeach
    @endif
    @php
        $patbilldata = \App\PatBillDetail::where('fldbillno',$billNumberGeneratedString)->where('fldsave','1')->first();
    @endphp

    @if($bill_type == "header1")
    @include('pdf-header-footer.header1')
    @elseif($bill_type == "header2")
    @include('pdf-header-footer.header2')
    @elseif($bill_type == "header3")
    @include('pdf-header-footer.header3')
    @endif

    <div class="main-body">
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <h5 class="bill-title">@if(isset($discharge_invoice_title) && $discharge_invoice_title !='') {{$discharge_invoice_title}} @else INVOICE @endif </h5>
            <table style="width: 60%; float: left;">
                <tbody>
                    <tr>
                        <td>EncID: {{ $encounterinfo->fldencounterval }}</td>
                    </tr>
                    <tr>
                        <td>
                            Name: {{ $encounterinfo->patientInfo->fldrankfullname }}({{ $encounterinfo->fldpatientval }})
                        </td>
                    </tr>
                    <tr>
                        <td>Age/Sex: {{ (($encounterinfo->patientInfo)) ? $encounterinfo->patientInfo->fldagestyle.'/'.$encounterinfo->patientInfo->fldptsex:'' }}</td>
                    </tr>
                    <tr>
                        <td>Address: {{ $encounterinfo->patientInfo->fulladdress ?? "" }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: {{ $encounterinfo->patientInfo->fldptcontact }}</td>
                    </tr>
                    @if(isset($encounterinfo->patientInfo->fldnhsiid))
                    <tr>
                        <td>NHSI No.: {{  $encounterinfo->patientInfo->fldnhsiid }}</td>
                    </tr>
                    <tr>
                        <td>Claim Code: {{ $encounterinfo->fldclaimcode }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <table style="width: 40%;float:right;text-align:right;">
                <tbody>
                    <tr>
                        <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
                    </tr>
                    <tr>
                        <td>DDA REG No.: {{Options::get('dda_number')}}</td>
                    </tr>
                    <tr>
                        <td>Bill Number: {{ $billNumberGeneratedString }}</td>
                    </tr>
                    <tr>
                        <td>Transactions Date: {{ Helpers::dateToNepali($time) }}</td>
                    </tr>
                    <tr>
                        <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($fldbillno) }}</td>
                    </tr>
                    <tr>
                        {{-- <td>Discount Mode: {{ $patbilldata && $patbilldata->flddiscountgroup?$patbilldata->flddiscountgroup:'' }} {{ $encounterinfo && $encounterinfo->fldclaimcode?'('.$encounterinfo->fldclaimcode.')':'' }}</td> --}}
                        <td>Discount Mode: {{ $patbilldata && $patbilldata->flddiscountgroup?$patbilldata->flddiscountgroup:'' }}</td>
                    </tr>
                    <tr>
                        <td>Payment: <b>{{strtoupper($paymentmode)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <table class="table content-body">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Particulars</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Transaction Date</th>
                        <th>SubTotal</th>
                        <!-- <th>VAT</th>
                        <th>Discount</th>
                        <th>Total</th> -->
                    </tr>
                </thead>
                <tbody>

                    @if(isset($dispensemedicine) and !empty($dispensemedicine))
                    @php
                        $narcmedicines = array();
                    @endphp
                    @foreach($dispensemedicine as $med)

                    @php
                    $narcmedicines[]=$med->flditemname;
                    $entrydata = \App\Entry::where('fldstockno',$med->flditemno)->first();

                    @endphp
                    @if($med->flditemtype == 'Medicines')
                        @php
                        $detail = \DB::table('tblmedbrand')->select('fldbrand')->where('fldbrandid',$med->flditemname)->first();
                        if($encounterinfo && (strtolower($encounterinfo->fldbillingmode) == 'health insurance' || strtolower($encounterinfo->fldbillingmode) == 'hi' || strtolower($encounterinfo->fldbillingmode) == 'healthinsurance' )){
                            $medicine = $med->hiitemname;
                            
                        }else{
                            $medicine = $detail->fldbrand;
                        }
 
                        @endphp
                    @elseif($med->flditemtype == 'Extra Items')
                        @php
                        if($encounterinfo && (strtolower($encounterinfo->fldbillingmode) == 'health insurance' || strtolower($encounterinfo->fldbillingmode) == 'hi' || strtolower($encounterinfo->fldbillingmode) == 'healthinsurance' )){
                            $medicine = $med->hiitemname;
                            
                        }else{
                            $medicine = $med->flditemname;
                        }
                         
                        @endphp
                    @elseif($med->flditemtype == 'Surgicals')
                        @php
                        if($encounterinfo && (strtolower($encounterinfo->fldbillingmode) == 'health insurance' || strtolower($encounterinfo->fldbillingmode) == 'hi' || strtolower($encounterinfo->fldbillingmode) == 'healthinsurance' )){
                            $medicine = $med->hiitemname;
                            
                        }else{
                            $medicine = $med->flditemname;
                        }
                        //$medicine = $med->flditemname;
                        @endphp
                    @else
                        @php
                            $medicine = $med->flditemname;
                        @endphp
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $medicine }}{{($med->fldtaxamt > 0)? "**" : ""}}</td>

                        <td>{{ $med->flditemqty }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($med->flditemrate) }}</td>
                        <td>{{ $entrydata->fldbatch }}</td>
                        <td>{{ \App\Utils\Helpers::dateToNepali(date("Y-m-d",strtotime($entrydata->fldexpiry))) }}</td>
                        <td>{{ \App\Utils\Helpers::dateToNepali(date("Y-m-d",strtotime($med->fldordtime))) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($med->flditemqty*$med->flditemrate)) }}</td>
                        <!-- <td>{{ $med->fldtaxamt }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($med->flddiscamt) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($med->fldditemamt) }}</td> -->
                    </tr>


                    @endforeach
                    @endif
                </tbody>
            </table>
            @php
                $narcconsultants  = \App\PatDosing::select(DB::raw("CONCAT(fldconsultant,' ',fldregno) AS consultantdetail"))->whereIn('flditem',$narcmedicines)->whereNotNull('fldconsultant')->whereNotNull('fldregno')->where('fldencounterval',$encounterinfo->fldencounterval)->orderBy('fldid','DESC')->groupBy('fldconsultant')->get();
            @endphp

            <table class="table" style="margin-top:8px;border:none;">
                <tr>
                    <td>
                        <p>Total Amt In Words: {{ \App\Utils\Helpers::numberToNepaliWords(isset($patbilldata) ? $patbilldata->fldreceivedamt:'') }}
                            <br>

                        </p>
                        @if(isset($narcconsultants) and count($narcconsultants) > 0)
                        <p>
                            @php
                                $username = explode(" ",$narcconsultants[0]->consultantdetail);
                                $fullname = \App\CogentUsers::select('firstname','middlename','lastname')->where('username',$username[0])->first();
                            @endphp
                            @if(isset($fullname) and !empty($fullname))
                                @php
                                 $detail = $fullname->firstname.' '.$fullname->middlename.' '.$fullname->lastname.' '.$username[1];
                                @endphp
                            @endif

                            Narcotic Prescribed By : Dr. {{$fullname ? $detail : $narcconsultants[0]->consultantdetail}}
                        </p>
                        @endif
                        <p>
                            Remarks: {{ $patbilldata?$patbilldata->remarks:'' }}
                        </p>
                        <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($encounterinfo->fldencounterval, 'C128') }}" alt="barcode" />


                    </td>
                    <td>
                        <ul>

                           <li><span>Sub Total:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat(($patbilldata?$patbilldata->flditemamt:0))}}</span></li>
                            <li><span>Discount:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($patbilldata?$patbilldata->flddiscountamt:0) }}</span></li>
                            <li><span>Total Tax:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($patbilldata?$patbilldata->fldtaxamt:0) }}</span></li>
                            <li><span>Total Amt:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($patbilldata?$patbilldata->fldchargedamt:0) }}</span></li>
                            @if(isset($depositDetail) and !empty($depositDetail))
                            <li><span>Previous Deposit:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($patbilldata?$patbilldata->fldprevdeposit:0)}}</span></li>
                            <hr/>
                            @endif
                            <li><span>Recv Amt:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($patbilldata?$patbilldata->fldreceivedamt:0) }}</span></li>
                            <li><span>To be Paid:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat((($patbilldata) and $patbilldata->fldcurdeposit < 0)? abs($patbilldata->fldcurdeposit):0) }}</span></li>
                        </ul>
                    </td>
                </tr>
            </table>
            @if(isset($depositDetail) and count($depositDetail) > 0)
            <h3 style="margin: 4px; text-decoration: underline;">Deposit Detail</h3>
                <table class="table content-body" style="width: 50%;;">
                    <thead class="thead-light">
                    <tr>
                        <th>SN</th>
                        <th>Bill No.</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($depositDetail)
                        @php
                            $totalsum = 0;
                        @endphp
                        @foreach($depositDetail as $k => $deposit)
                            @php
                                $totalsum += $deposit->fldreceivedamt;
                            @endphp
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$deposit->fldbillno}}</td>
                                <td>{{$deposit->fldtime}}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($deposit->fldreceivedamt)}}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td><b>Total</b></td>
                        <td colspan="3" style="text-align: center;"><b>{{\App\Utils\Helpers::numberFormat($totalsum)}}</b></td>
                    </tr>
                    </tbody>
                </table>
            @endif
            {{-- <p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbilldata->flduserid)) }}</p>
                            <p>Print By:
                                {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                                <br>{{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}</p> --}}
            @if($bill_total == "total1")
            @include('pdf-header-footer.total1')
            @elseif($bill_total == "total2")
            @include('pdf-header-footer.total2')
            @endif
            <p>??????????????? ??????????????? ?????????????????? ????????????????????? ????????? ???????????????????????? ????????????????????? ???????????? <br/>(????????? ?????????????????? ???????????????????????? ?????? ????????? ?????????????????????)</p>
        </div>

        @php
        $isOpd = strpos($encounterinfo->fldcurrlocat, 'OPD');
        @endphp
        @foreach($medicines as $medicine)
        @if(in_array($medicine->fldid, $printIds))
        <div class="pagebreak">
            <div style="width: 100%;">
                <div style="width: 30%;float: left;text-align: left;">{{ $encounterinfo->patientInfo->fldfullname }} ({{ $encounterinfo->patientInfo->fldpatientval }})</div>
                <div style="width: 30%;float: left; text-align: center;">{{ $encounterinfo->fldadmitlocat }}</div>
                <div style="width: 30%;float: right; text-align: right;">{{ $encounterinfo->fldencounterval }}</div>
            </div>
            <div style="width: 100%;">
                <h2>{{ $medicine->flditem }}</h2>
            </div>
            <div style="width: 100%;">
                <div style="width: 30%;float: left;text-align: left;">{{ $medicine->fldbatch }}</div>
                <div style="width: 30%;float: left; text-align: center;">{{ ($medicine->medicine && $medicine->medicine->medbrand) ? $medicine->medicine->medbrand->flddosageform : '' }}</div>
                <div style="width: 30%;float: right; text-align: right;">{{ explode(' ', $medicine->fldexpiry)[0] }}</div>
            </div>
            <div style="width: 100%;text-align: center;">
                <div>
                    <h2>
                        {{ ($medicine->medicine && $medicine->medicine->medbrand && $medicine->medicine->medbrand->label) ? (($isOpd) ? $medicine->medicine->medbrand->label->fldopinfo : $medicine->medicine->medbrand->label->fldipinfo) : '' }}
                    </h2>
                </div>
            </div>
            <div style="width: 100%;">
                <div>1 {{ isset($trans['Cap']) ? $trans['Cap'] : 'Cap' }} {{ isset($trans['Every']) ? $trans['Every'] : 'Every' }} {{ ceil(24/$medicine->fldfreq )}} {{ isset($trans['Hour']) ? $trans['Hour'] : 'Hour' }} {{ isset($trans['Difference']) ? $trans['Difference'] : 'Difference' }} {{ $medicine->flddays }} {{ isset($trans['Day']) ? $trans['Day'] : 'Day' }} </div>
            </div>
            <div style="width: 100%;">
                <div style="width: 30%;float: left;text-align: left;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</div>
                <div style="width: 30%;float: left; text-align: center;">{{ date('M d, Y') }}</div>
                <div style="width: 30%;float: right; text-align: right;">{{ $medicine->fldroute }}</div>
            </div>
        </div>
        @endif
        @endforeach
        <p class="footer">GET WELL SOON!</p>
    </div>
</div>
@endsection
