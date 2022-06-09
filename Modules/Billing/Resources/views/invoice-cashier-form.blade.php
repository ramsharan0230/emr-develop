@extends('inpatient::pdf.layout.main')

@section('content')
<div class="a4">


    @php
        $fldbillno = $patbillingDetails ? $patbillingDetails->fldbillno : '';
        $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
        $bill_type = Options::get('Service-Billing-Header');
        $bill_total = Options::get('Service-Billing-Total');
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

            <h5 class="bill-title">
                @if(isset($invoice_title))
                    {{ strtoupper($invoice_title) }}
                @else
                    INVOICE
                @endif
                @if(($billCount-1) > 1)
                    (COPY OF ORIGINAL) Print-{{ $billCount-1 }}
                @endif
            </h5>
            <div style="width: 100%;"></div>
            <table style="width: 60%; float: left;">

                <tbody>
                <tr>
                    <td>EncID: {{ $enpatient->fldencounterval }}</td>
                </tr>
                <tr>
                    <td>
                        Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})
                    </td>
                </tr>
                <tr>
                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                @if(isset($enpatient->patientInfo) and ($enpatient->patientInfo->fldcountry != 'NEPAL'))
                <tr>
                    <td>Address: {{$enpatient->patientInfo->fldcountry}},{{$enpatient->patientInfo->fldptaddvill}}</td>
                </tr>
                @else
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
                </tr>
                @endif
                
                <tr>
                    <td>Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
                </tr>
                @if(isset($enpatient->patientInfo->fldnhsiid))
                <tr>
                    <td>NHSI No.: {{  $enpatient->patientInfo->fldnhsiid }}</td>
                </tr>
                <tr>
                    <td>Claim Code: {{ $enpatient->fldclaimcode }}</td>
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
                    <td>Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
                </tr>
                <tr>
                    <td>Transactions Date: {{ Helpers::dateToNepali($patbillingDetails->fldtime) }}</td>
                </tr>
                {{--@if (strpos($fldbillno, 'REG') === 0)
                    <tr>
                        <td>Consultation:{{ \App\Utils\RegistrationHelpers::getBillConsulataionName($fldbillno) }}</td>
                    </tr>
                @endif--}}
                <tr>
                    <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($fldbillno) }}</td>
                </tr>
                <tr>
                    {{-- <td>Discount Mode: {{ $patbillingDetails?$patbillingDetails->flddiscountgroup:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td> --}}
                    <td>Discount Mode: {{ $patbillingDetails?$patbillingDetails->flddiscountgroup:'' }}</td>
                </tr>
                <tr>
                    {{-- <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td> --}}
                    <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }}</td>
                </tr>
                @if($patbilling && collect($patbilling)->unique('package_name') && collect($patbilling)->unique('package_name')[0]->package_name != null)
                    @php
                        $patbillingPackage = collect($patbilling)->unique('package_name')[0];
                    @endphp
                    <tr>
                        <td>Package: {{ $patbilling && $patbillingPackage->package_name ? $patbillingPackage->package_name :'' }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
        @php
            $flditemrate = $fldditemamt = $flddiscountamt = $flditemtax = 0;
        @endphp
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">

            <table class="table content-body">
                <thead class="thead-light">
                <tr>
                    <th>S/N</th>
                    <th>Particulars</th>
                    <th style="max-width:130px;">Payables</th>
                    <th style="text-align: right;">QTY</th>
                    <th>Rate</th>
                    {{--                    <th>Discount</th>--}}
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @if(count($patbilling) && collect($patbilling)->unique('package_name') && collect($patbilling)->unique('package_name')[0]->package_name == null)
                    @forelse($patbilling as $billItem)
                        @php
                            $flditemtax += $billItem->fldtaxamt;
                            $flditemrate += $billItem->flditemrate * $billItem->flditemqty;
                            $flddiscountamt += $billItem->flddiscamt;
                            $fldditemamt += $billItem->fldditemamt;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $billItem->fldbillitem }}</td>
                            <td style="max-width:130px;">{{ isset($payables[$billItem->fldid]) ? implode(', ' , $payables[$billItem->fldid]) : '' }}</td>
                            <td style="text-align: right;">{{ $billItem->flditemqty }}</td>
                            <td>{{ \App\Utils\Helpers::numberFormat($billItem->flditemrate) }}</td>
                            {{--                            <td>{{ \App\Utils\Helpers::numberFormat($billItem->flddiscamt) }}</td>--}}
                            <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldtaxamt) }}</td>
                            <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldditemamt) }}</td>
                        </tr>
                    @empty

                    @endforelse
                @else

                    @forelse($patbilling as $billItem)
                        @php
                            $flditemtax += $billItem->fldtaxamt;
                            $flditemrate += $billItem->flditemrate * $billItem->flditemqty;
                            $flddiscountamt += $billItem->flddiscamt;
                            $fldditemamt += $billItem->fldditemamt;
                        @endphp
                    @empty
                    @endforelse
                    <tr>
                        <td>1</td>
                        <td>{{ $patbilling && $patbillingPackage->package_name ? $patbillingPackage->package_name :'' }}</td>
                        @php
                            $itemsPayable ='';
                        @endphp
                        @foreach($patbilling->pluck('fldid') as $idPayable)
                            @if(isset($payables[$idPayable]))
                                @php
                                    $itemsPayable .=implode(', ' , $payables[$idPayable]);
                                @endphp
                            @endif
                        @endforeach
                        <td>{{ $itemsPayable }}</td>
                        <td>1</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($flditemrate) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($flditemtax) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($fldditemamt) }}</td>
                    </tr>
                @endif

                </tbody>
            </table>

            <table class="table" style="margin-top:8px;">
                <tr>
                    @php
                        if($patbillingDetails->fldreceivedamt == 0){
                        $words = "Zero Rupee Only";
                        }else{
                        $words = ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt));
                        }
                    @endphp
                    <td>
                        <p>
                            In words: {{ $words }} /-
                            <br>Payment: {{ ucfirst(($patbillingDetails and $patbillingDetails->payment_mode !='')?$patbillingDetails->payment_mode:$patbillingDetails->fldbilltype) }}
                        </p>
                        <p>
                            Remarks: {{ $patbillingDetails?$patbillingDetails->remarks:'' }}
                            {{--                            Remarks: {{ $patbillingDetails ? $patbillingDetails->remarks:'' }}--}}
                        </p>
                        <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/>
                        {{-- <p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbillingDetails->flduserid)) }}</p>
                        <p>Print By:
                            {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                            <br>{{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }}</p> --}}
                        @if($bill_total == "total1")
                        @include('pdf-header-footer.total1')
                        @elseif($bill_total == "total2")
                        @include('pdf-header-footer.total2')
                        @endif

                    </td>

                    <td>
                        <ul>
                            <li><span>Sub Total:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flditemrate) }}</span></li>
                            <li><span>Discount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flddiscountamt) }}</span></li>
                            <li><span>Total Tax:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flditemtax) }}</span></li>
                            <li><span>Total Amt:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($fldditemamt) }}</span></li>
                            <li><span>Recv Amt:</span><span>Rs.{{ $patbillingDetails ? ((strpos($patbillingDetails->fldbillno,'CRE') !== false) ? 0.00 : (\App\Utils\Helpers::numberFormat($patbillingDetails?$patbillingDetails->fldreceivedamt:0))) : 0.00 }}</span></li>
                        </ul>


                    </td>
                </tr>

            </table>

            <p class="footer">GET WELL SOON!</p>
        </div>

    </div>
</div>
@endsection
@push('after-script')
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.print();
        }, 3000);
    });
</script>
@endpush
