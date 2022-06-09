<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title>
</head>

<body>
<style>


        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 95%;
            text-align: center;
            margin:3px auto;
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
           /* border:1px solid #000;
*/        }

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
    <!-- <header class="heading" style="margin: 0 auto; width: 98%;text-align:center ">
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
            <td style="width:70%;">
                <h2 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h2>
                <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</header> -->
    @php
        $fldbillno = $patbillingDetails ? $patbillingDetails->fldbillno : '';
        $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
    @endphp
    @php
        $itemno = array();
    @endphp
   @if(isset($itemdata) and count($itemdata) > 0)
        @foreach($itemdata as $item)
            @php
                $itemno[] = $item->flditemno;
            @endphp
        @endforeach
   @endif

   @if(isset($itemno) and !empty($itemdata))
        @php
         $entrydata = \App\Entry::whereIn('fldstockno', $itemno)->get();
        @endphp
   @endif
   @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
        @if(isset($entrydata) and !empty($entrydata))
           <style type="text/css">
               @page {
                    margin: 4mm 0 4mm;
                }
           </style>
       @else
            <style type="text/css">
               @page {
                    margin: 24mm 0 11mm;
                }
           </style>
       @endif
    @else
        <style type="text/css">
           @page {
                margin: 24mm 0 11mm;
            }
       </style>
    @endif
   @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
    @if(isset($entrydata) and !empty($entrydata))
       <header>
        <table style="width: 95%;margin:0 auto;">
            <tbody>
            <tr>
                <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="logo" style="max-width:100%;" /></td>
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
    </header>
    @endif
   @endif
    <div class="pdf-container" style="margin: 0 auto; width: 95%;">

        <h5 class="bill-title">INVOICE @if($billCount > 1) (COPY OF ORIGINAL)Print-{{ $billCount-1 }}@endif</h5>
        <div style="width: 100%;"></div>
        <table style="width: 60%;float:left;">
            <tbody>
                <tr>
                    <td>EncID: {{ $enpatient->fldencounterval ?? ""}}</td>
                </tr>
                <tr>
                    <td>
                        Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ? $enpatient->fldrank : ''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})
                    </td>
                </tr>
                <tr>
                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fulladdress : '' }}</td>
                </tr>
                <tr>
                    <td>Phone No: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldptcontact : '' }}</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 40%;float:right;text-align:right;">
            <tbody>
                <tr>
                    <td>Pan Number: {{ Options::get('hospital_pan') ? Options::get('hospital_pan') : Options::get('hospital_vat') }}</td>
                </tr>
                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                            @if(isset($entrydata) and !empty($entrydata))
                <tr>
                    <td>DDA REG No.: {{Options::get('dda_number')}}</td>
                </tr>
                @endif
                @endif
                <tr>
                    <td>Bill Number: {{ $patbillingDetails ? $patbillingDetails->fldbillno : '' }}</td>
                </tr>
                <tr>
                <td>Transactions Date: {{ Helpers::dateToNepali($patbillingDetails->fldtime) }}</td>
                </tr>

                @if (strpos($fldbillno, 'REG') === 0 || strpos($fldbillno, 'CAS') === 0)
                <tr>
                    <td>Doctor Name:{{ \App\Utils\RegistrationHelpers::getBillConsulataionName($fldbillno) }}</td>
                </tr>
                @endif

                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                    <tr>
                        <td>Discount Mode: {{ $patbillingDetails ? $patbillingDetails->flddiscountgroup : '' }} {{ $enpatient && $enpatient->fldclaimcode ? '('.$enpatient->fldclaimcode.')' : '' }}</td>
                    </tr>
                @else
                    <tr>
                        <td>Discount Mode: {{ $patbillingDetails ? $patbillingDetails->flddiscountgroup : '' }} {{ $enpatient && $enpatient->fldclaimcode ? '('.$enpatient->fldclaimcode.')' : '' }}</td>
                    </tr>
                @endif

                <tr>
                    <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode ? $enpatient->fldbillingmode : '' }} {{ $enpatient && $enpatient->fldclaimcode ? '('.$enpatient->fldclaimcode.')' : '' }}</td>
                </tr>
                @if($itemdata && collect($itemdata)->unique('package_name') && collect($itemdata)->unique('package_name')[0]->package_name != null)
                    @php
                        $patbillingPackage = collect($itemdata)->unique('package_name')[0];
                    @endphp
                    <tr>
                        <td>Package: {{ $itemdata && $patbillingPackage->package_name ? $patbillingPackage->package_name :'' }}</td>
                    </tr>
                @endif
                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                    @if(isset($entrydata) and !empty($entrydata))
                    <tr>
                        <td>Payment: <b>{{ strtoupper(($patbillingDetails and $patbillingDetails->payment_mode !='')?$patbillingDetails->payment_mode:$patbillingDetails->fldbilltype) }}</b></td>
                    </tr>
                    @endif
                @endif
            </tbody>
        </table>
        <div style="clear: both"></div>
    </div>


    <div class="pdf-container">

        <table class="table content-body">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Particulars</th>
                    @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                    @if(isset($entrydata) and !empty($entrydata))

                        @else
                            <th style="max-width:130px;">Payables</th>
                        @endif
                    @else
                        <th style="max-width:130px;">Payables</th>
                    @endif
                    <th>QTY</th>
                    <th>Rate</th>
                    @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                        @if(isset($entrydata) and !empty($entrydata))
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Transaction Date</th>
                        @endif
                    @endif
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($itemdata) and count($itemdata) > 0)
                    @php
                        $previousPackage = [];
                        $narcmedicines = [];
                    @endphp
                    @foreach($itemdata as $k=>$itd)
                        @if($itd->package_name != null)
                            @if(!in_array($itd->package_name, $previousPackage))
                                @php array_push($previousPackage, $itd->package_name) @endphp
                                <tr>
                                    <td>1</td>
                                    <td>{{ $itemdata && $patbillingPackage->package_name ? $patbillingPackage->package_name :'' }}</td>
                                    @php
                                        $itemsPayable ='';
                                    @endphp
                                    @foreach($itemdata->pluck('fldid') as $idPayable)
                                        @if(isset($payables[$idPayable]))
                                            @php
                                                $itemsPayable .=implode(', ' , $payables[$idPayable]);
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td style="max-width:130px;">{{ $itemsPayable }}</td>
                                    <td>1</td>
                                    <td>{{  \App\Utils\Helpers::numberFormat(($patbillingDetails ? $patbillingDetails->flditemamt : 0))}}</td>
                                    <td>{{  \App\Utils\Helpers::numberFormat(($patbillingDetails ? $patbillingDetails->fldchargedamt : 0)) }}</td>
                                </tr>
                            @endif
                        @else
                            @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                @if(isset($entrydata) and !empty($entrydata))
                                    @php
                                        $narcmedicines[]=$itd->flditemname;
                                        $entdata = \App\Entry::where('fldstockno',$itd->flditemno)->first();
                                    @endphp
                                @endif
                            @endif
                            @php
                                $sn = $k+1;
                            @endphp

                            @if($itd->flditemtype == 'Medicines')
                                @php
                                    $detail = \DB::table('tblmedbrand')->select('fldbrand')->where('fldbrandid',$itd->flditemname)->first();
                                    $itemname = $detail->fldbrand;
                                @endphp
                            @elseif($itd->flditemtype == 'Extra Items')
                                @php

                                    $itemname = $itd->flditemname;
                                    @endphp
                                @elseif($itd->flditemtype == 'Surgicals')
                                    @php

                                    $itemname = $itd->flditemname;
                                    @endphp
                                @else
                                    @php
                                        $itemname = $itd->flditemname;
                                    @endphp
                                @endif
                            <tr>
                                <td>{{$sn}}</td>
                                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                    @if(isset($entrydata) and !empty($entrydata))
                                        <td>{{$itemname}}{{($itd->fldtaxamt > 0)? "**" : ""}}</td>
                                    @endif
                                @else
                                    <td>{{$itemname}}</td>
                                @endif

                                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                    @if(isset($entrydata) and !empty($entrydata))

                                    @else
                                    <td>{{ isset($payables[$itd->fldid]) ? implode(', ' , $payables[$itd->fldid]) : '' }}</td>
                                    @endif
                                @else
                                    <td style="max-width:130px;">{{ isset($payables[$itd->fldid]) ? implode(', ' , $payables[$itd->fldid]) : '' }}</td>
                                @endif

                                <td>{{$itd->flditemqty}}</td>
                                <td>{{  \App\Utils\Helpers::numberFormat(($itd->flditemrate ? $itd->flditemrate : 0)) }}</td>
                                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                    @if(isset($entrydata) and !empty($entrydata))
                                    <td>{{ (isset($entdata) and !empty($entdata)) ? $entdata->fldbatch : '' }}</td>
                                    <td>{{ (isset($entdata) and !empty($entdata)) ? \App\Utils\Helpers::dateToNepali(date("Y-m-d",strtotime($entdata->fldexpiry)))  : '' }}</td>
                                    <td>{{ (isset($itd) and !empty($itd)) ? \App\Utils\Helpers::dateToNepali(date("Y-m-d",strtotime($itd->fldordtime)))  : '' }}</td>
                                    @endif
                                @endif
                                @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                                    @if(isset($entrydata) and !empty($entrydata))
                                    <td>{{  \App\Utils\Helpers::numberFormat($itd->flditemqty*$itd->flditemrate) }}</td>
                                    @endif
                                @else
                                <td>{{  \App\Utils\Helpers::numberFormat(($itd->fldditemamt ? $itd->fldditemamt : 0)) }}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @endif

            </tbody>
        </table>
        @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
            @if(isset($entrydata) and !empty($entrydata))
                @php
                    $narcconsultants  = \App\PatDosing::select(DB::raw("CONCAT(fldconsultant,' ',fldregno) AS consultantdetail"))->whereIn('flditem',$narcmedicines)->whereNotNull('fldconsultant')->whereNotNull('fldregno')->where('fldencounterval',$enpatient->fldencounterval)->orderBy('fldid','DESC')->groupBy('fldconsultant')->get();
                @endphp
            @endif
        @endif
        <table class="table" style="margin-top:8px;">
            <tr>
                <td>
                    <p >In words: {{ $patbillingDetails ? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)):'' }} /-&nbsp;
                        <br />
                        @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                            @if(isset($entrydata) and !empty($entrydata))

                            @else
                            Payment: <b>{{ ucfirst(($patbillingDetails and $patbillingDetails->payment_mode !='') ? $patbillingDetails->payment_mode : $patbillingDetails->fldbilltype) }}</b>
                            @endif
                        @else
                            Payment: <b>{{ ucfirst(($patbillingDetails and $patbillingDetails->payment_mode !='') ? $patbillingDetails->payment_mode : $patbillingDetails->fldbilltype) }}</b>
                        @endif

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
                        Remarks: {{ $patbillingDetails ? $patbillingDetails->remarks : '' }}
                    </p>
                    @if ($enpatient && $enpatient->fldencounterval)
                        <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode" />
                    @endif
                    <p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbillingDetails->flduserid)) }}</p>
                    <p>Print By:
                    {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                    <br>{{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}</p>
                    <span style="text-align: right;">Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbillingDetails->flduserid)) }}</span>
                    @if((strpos($fldbillno,'PHM') !== false) or (strpos($fldbillno,'RET') !== false))
                        @if(isset($entrydata) and !empty($entrydata))
                            <p>कृपया सामान फिर्ता ल्याउदा विल अनिवार्य ल्याउनु होला <br/>(विल काटेको मितीदेखि १० दिन भित्रमा)</p>
                        @endif
                    @endif

                </td>
                <td>
                    <ul>
                        <li><span>Sub Total:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat(($patbillingDetails ? $patbillingDetails->flditemamt : 0))}}</span></li>
                        <li><span>Discount:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($patbillingDetails ? $patbillingDetails->flddiscountamt : 0) }}</span></li>
                        <li><span>Total Tax:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($patbillingDetails ? $patbillingDetails->fldtaxamt : 0) }}</span></li>
                        <li><span>Total Amt:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($patbillingDetails ? $patbillingDetails->fldchargedamt : 0) }}</span></li>
                        <li><span>Recv Amt:</span><span>Rs.{{ (strpos($fldbillno,'CRE') !== false) ? 0.00 : ( \App\Utils\Helpers::numberFormat($patbillingDetails ? $patbillingDetails->fldreceivedamt : 0)) }}</span></li>
                    </ul>
                </td>
            </tr>
        </table>
        <p class="footer">GET WELL SOON!</p>
    </div>
</body>

</html>
