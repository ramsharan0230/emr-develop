@extends('inpatient::pdf.layout.main')

@section('title')
PHARMACY BILL
@endsection

@section('content')
{{-- <style>
        @page {
            margin: 24mm 0 11mm;
        }

        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 100%;
            text-align: center;
            margin-bottom: 2px;
            margin-top: 3px;
        }


        .a4 {
            width: auto;
            margin: 0 auto;
        }

        .footer {
            /* position: absolute; */
            width: 100%;
            text-align: center;
            margin:0;
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
            border: 1px solid #ddd;
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
    </style> --}}
<div class="a4">
    @php
        $fldbillno = $billNumberGeneratedString;
        $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
        $bill_type = Options::get('Pharmacy-Billing-Header');
        $bill_total = Options::get('Pharmacy-Billing-Total');
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
            <h5 class="bill-title">TP BILL </h5>
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
                        <td>Transaction Date: {{ Helpers::dateToNepali($time) }}</td>
                    </tr>
                    <tr>
                        <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($fldbillno) }}</td>
                    </tr>
                    <tr>
                        <td>Billing Mode: {{ $encounterinfo && $encounterinfo->fldbillingmode?$encounterinfo->fldbillingmode:'' }} {{ $encounterinfo && $encounterinfo->fldclaimcode?'('.$encounterinfo->fldclaimcode.')':'' }}</td>
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
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dispensemedicine) and !empty($dispensemedicine))
                        @php
                            $viewtotalsum = 0;
                            $discountamt = 0;
                            $totaltax = 0;
                            $narcmedicines = array();

                        @endphp
                    @foreach($dispensemedicine as $med)

                    @php
                    $narcmedicines[]=$med->flditemname;
                    $entrydata = \App\Entry::where('fldstockno',$med->flditemno)->first();
                    $viewtotalsum += $med->flditemqty*$med->flditemrate;
                    $discountamt += $med->flddiscamt;
                    $totaltax += $med->fldtaxamt;
                    @endphp
                    @if($med->flditemtype == 'Medicines')
                        @php
                        $detail = \DB::table('tblmedbrand')->select('fldbrand')->where('fldbrandid',$med->flditemname)->first();
                        $medicine = $detail->fldbrand;
                        @endphp
                    @elseif($med->flditemtype == 'Extra Items')
                        @php
                         $medicine = $med->flditemname;
                        @endphp
                    @elseif($med->flditemtype == 'Surgicals')
                        @php
                        $medicine = $med->flditemname;
                        @endphp
                    @else
                        @php
                            $medicine = $med->flditemname;
                        @endphp
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }} </td>
                        <td>{{ $medicine }} {{($med->fldtaxamt > 0) ? "**" : ""}}</td>
                        <td>{{ $med->flditemqty }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($med->flditemrate) }}</td>
                        <td>{{ $entrydata->fldbatch }}</td>
                        <td>{{ $entrydata->fldexpiry ? \App\Utils\Helpers::dateToNepali($entrydata->fldexpiry)  :'' }}</td>
                        <td>{{ $med->fldordtime ? \App\Utils\Helpers::dateToNepali($med->fldordtime) :'' }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($med->flditemqty*$med->flditemrate)) }}</td>
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
                        <p>Total Amt In Words: Zero Rupees Only.
                            <br>
                            Payment: {{$paymentmode}}
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
                            Remarks: {{ $remarks }}
                        </p>
                        <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($encounterinfo->fldencounterval, 'C128') }}" alt="barcode" />
                         {{-- <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                        </br>{{\App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}</p> --}}
                        @if($bill_total == "total1")
                        @include('pdf-header-footer.total1')
                        @elseif($bill_total == "total2")
                        @include('pdf-header-footer.total2')
                        @endif
                    </td>
                    <td>
                        <ul>
                           <li><span>Sub Total:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($viewtotalsum)}}</span></li>
                            <li><span>Discount:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($discountamt) }}</span></li>
                            <li><span>Total Tax:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($totaltax) }}</span></li>
                            <li><span>Total Amt:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat(($viewtotalsum+$totaltax-$discountamt)) }}</span></li>
                            <li><span>Recv Amt:</span><span>Rs.0.00</span></li>
                        </ul>
                    </td>
                </tr>
            </table>


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
