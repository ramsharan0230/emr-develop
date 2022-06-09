@extends('inpatient::pdf.layout.main')

@section('content')
<div class="a4">

    @php
    $flditemrate = $fldditemamt = $flddiscountamt = $flditemtax = 0;
    $bill = Request::get('tpBillNumber');
    $explodebill = explode('-',$bill);
    $billtype = $explodebill[0];
    @endphp

    @if(isset($patbilling) && (strtoupper(substr($patbilling[0]->fldtempbillno, 0,2)) == 'TP' ))
        @php
            $bill_type = Options::get('Service-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
    @endif

    @if(isset($patbilling) && (strtoupper(substr($patbilling[0]->fldtempbillno, 0,2)) == 'TPPHM' ))
        @php
            $bill_type = Options::get('Pharmacy-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
    @endif

    <div class="main-body">

        <div class="pdf-container" style="margin: 0 auto; width: 95%;">

            <h5 class="bill-title">
                TP RECEIPT
            </h5>
            <div style="width: 100%;"></div>
            <table style="width: 60%;float:left;">
                <tbody>
                <tr>
                    <td>EncID: {{ $enpatient->fldencounterval ?? ""}}</td>
                </tr>
                <tr>
                    <td>
                        Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})
                    </td>
                </tr>
                <tr>
                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}
                    </td>
                </tr>
                <tr>
                    <td>Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
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
                    <td>Bill Number: {{ $patbilling?$patbilling[0]->fldtempbillno:'' }}</td>
                </tr>
                <tr>
                    <td>Transaction Date: {{ count($patbilling) ? \App\Utils\Helpers::dateToNepali($patbilling[0]->fldordtime) :'' }}</td>
                </tr>


                {{--                <tr>--}}
                {{--                    <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($fldbillno) }}</td>--}}
                {{--                </tr>--}}

                <tr>
                    <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($patbilling[0]->fldtempbillno) }}</td>
                </tr>

                <tr>
                    <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
                </tr>
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>

        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <div class="table-dental2" style="margin-top: 16px;">
                <table class="table content-body">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Particulars</th>
                        @if($billtype !='TPPHM')
                        <th style="width: 150px; text-align: left">Payable</th>
                        @endif
                        <th>QTY</th>
                        <th>Rate</th>
                        @if($billtype == 'TPPHM')
                            <th>Batch</th>
                            <th>Expiry</th>
                        @endif

                        @if($billtype != 'TPPHM')
                            <th>Tax</th>
                        @endif                      {{--  <th>Discount</th>--}}

                        <th style="width: 100px; ">Transaction Date</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(count($patbilling))
                        @forelse($patbilling as $billItem)
                            @php
                                $flditemtax += $billItem->fldtaxamt;
                                $flditemrate += $billItem->flditemrate * $billItem->flditemqty;
                                $flddiscountamt += $billItem->flddiscamt;
                                $fldditemamt += $billItem->fldditemamt;
                                $payables='';
                            @endphp
                            @if($billItem->flditemtype == 'Medicines')
                                @php
                                $detail = \DB::table('tblmedbrand')->select('fldbrand')->where('fldbrandid',$billItem->flditemname)->first();
                                $itemname = $detail->fldbrand;
                                @endphp
                            @elseif($billItem->flditemtype == 'Surgicals' || $billItem->flditemtype == 'Extra Items')
                                @php
                                $itemname = $billItem->flditemname;
                                @endphp
                            @else
                                @php
                                $itemname = $billItem->serviceCost->fldbillitem;
                                @endphp
                            @endif

                            @if($billtype == 'TPPHM')
                                @php
                                    $entrydata = \App\Entry::where('fldstockno',$billItem->flditemno)->first();
                                @endphp
                            @endif

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $itemname }} {{($billItem->fldtaxamt > 0) ? "**" : ""}}</td>
                                @if($billtype !='TPPHM')
                                <td style="width: 150px; text-align: left">
                                    @if($billItem->pat_billing_shares)
                                        @foreach($billItem->pat_billing_shares->where('type', 'payable') as $user)
                                            @if($user->user)
                                                @php
                                                $payables .= $user->user->fldfullname. ', ';
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    {{ rtrim($payables, ', ') }}
                                </td>
                                @endif
                                <td>{{ $billItem->flditemqty }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->flditemrate) }}</td>
                                @if($billtype == 'TPPHM')
                                    <td>{{$entrydata->fldbatch}}</td>
                                    <td style="width: 200px;">{{$entrydata->fldexpiry ? \App\Utils\Helpers::dateToNepali($entrydata->fldexpiry)  :''}}</td>
                                @endif
                                {{--                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->flddiscamt) }}</td>--}}
                                 @if($billtype != 'TPPHM')
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldtaxamt) }}</td>
                                @endif
                                <td style="width: 200px;">{{ $billItem->fldordtime ? \App\Utils\Helpers::dateToNepali($billItem->fldordtime) :'' }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldditemamt) }}</td>
                            </tr>
                        @empty

                        @endforelse
                    @endif

                    </tbody>
                </table>

                <table class="table" style="margin-top:8px;">
                    <tr>
                        <td>
                            {{--
                            <p>Total Amt In words: {{ ucwords(\App\Utils\Helpers::numberToNepaliWords($fldditemamt))}} /-</p>--}}
                            <p>Total Amt In Words: Zero Rupees Only.
                                @if($billtype == 'TPPHM')
                                <br>
                                Payment: Credit
                                @endif
                            </p>
                            @php
                                $remark = \App\Dispenseremark::select('fldremark')->where('fldbillno',$patbilling?$patbilling[0]->fldtempbillno:'')->first();
                            @endphp
                            @if($billtype == 'TPPHM')
                            <p>Remarks: {{ (isset($remark) and !empty($remark)) ? $remark->fldremark : ""}}</p>
                            @endif

                            @if ($enpatient && $enpatient->fldencounterval)
                                <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/>
                            @endif

                            {{-- <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                                </br>{{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}</p> --}}
                            @if(isset($patbilling) && (strtoupper(substr($patbilling[0]->fldtempbillno, 0,2)) == 'TP' ))
                                @php
                                    $bill_total = Options::get('Service-Billing-Total');
                                @endphp
                                @if($bill_total == "total1")
                                @include('pdf-header-footer.total1')
                                @elseif($bill_total == "total2")
                                @include('pdf-header-footer.total2')
                                @endif
                            @endif

                            @if(isset($patbilling) && (strtoupper(substr($patbilling[0]->fldtempbillno, 0,2)) == 'TPPHM' ))
                                @php
                                    $bill_total = Options::get('Pharmacy-Billing-Total');
                                @endphp
                                @if($bill_total == "total1")
                                @include('pdf-header-footer.total1')
                                @elseif($bill_total == "total2")
                                @include('pdf-header-footer.total2')
                                @endif
                            @endif

                        </td>
                        <td>
                            <ul>
                                <li><span>Sub Total:</span><span>Rs.{{\App\Utils\Helpers::numberFormat($flditemrate) }}</span></li>
                                <li><span>Discount:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($flddiscountamt) }}</span></li>
                                <li><span>Total Tax:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($flditemtax) }}</span></li>
                                <li><span>Total Amt:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($fldditemamt) }}</span></li>
                                <li><span>Recv Amt:</span><span>Rs. 0.00</span></li>
                            </ul>
                        </td>


                    </tr>
                </table>

                <p class="footer">GET WELL SOON!</p>
            </div>
            @if($show_tp == '1')
            <div class="table-dental2" style="margin-top: 16px;">
                    <h6>TP RECORD</h6>
                    <table class="table content-body">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Particulars</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>Tax</th>
                            <th style="width: 100px;">Transaction Date</th>
                            <th>User</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $tpflditemrate = $tpfldditemamt = $tpflddiscountamt = $tpflditemtax = 0;

                        @endphp
                        @if(count($tpitems))
                            @forelse($tpitems as $item)
                                @php
                                    $tpflditemtax += $item->fldtaxamt;
                                    $tpflditemrate += $item->flditemrate * $item->flditemoldqty;
                                    $tpflddiscountamt += $item->flddiscamt;
                                    $tpfldditemamt += $item->fldditemamt;

                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->flditemname }} {{($item->fldtaxamt > 0) ? "**" : ""}}</td>

                                    <td>{{ $item->flditemoldqty }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($item->flditemrate) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($item->fldtaxamt) }}</td>
                                    <td style="width: 200px;">{{ $item->updated_at ? \App\Utils\Helpers::dateToNepali($billItem->updated_at) :\App\Utils\Helpers::dateToNepali($billItem->created_at) }}</td>
                                    <td>{{ $item->updated_by ? $item->updated_by : $item->flduserid }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($item->fldditemamt) }}</td>
                                </tr>
                            @empty

                            @endforelse
                        @endif

                        </tbody>
                    </table>
                    <table class="table" style="margin-top:8px;">
                    <tr>
                        <td>



                        </td>
                        <td>
                            <ul>
                                <li><span>Sub Total:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($tpflditemrate) }}</span></li>
                                <li><span>Discount:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($tpflddiscountamt) }}</span></li>
                                <li><span>Total Tax:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($tpflditemtax) }}</span></li>
                                <li><span>Total Amt:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($tpfldditemamt) }}</span></li>

                            </ul>
                        </td>


                    </tr>
                </table>
            </div>
            @endif
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