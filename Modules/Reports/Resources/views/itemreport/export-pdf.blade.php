@extends('inpatient::pdf.layout.main')

@section('title', 'ITEM REPORT')

@section('content')
    <style>
        h2 {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #alignleft {
            text-align: left;
        }
        #totaltable {
            margin-top: 20px;
        }

        .heading {
            margin-bottom: 20px;
        }

        .heading tbody tr td:nth-child(2) {
            text-align: right;
        }

        #totaltable td {
            text-align: right;
            height: 20px;
            width: 130px;
        }

    </style>
    <h2>Item Report</h2>
    <table style="width: 100%;" class="heading">
        <tbody>
            <tr>
                <td>
                    From Date: 2021-11-24 00:00:00 (2078-08-08 00:00:00)
                </td>
                <td id="alignright">
                Category: %
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    To Date: 2021-12-7 23:59:59 (2078-08-21 23:59:59)
                </td>
                <td id="alignright">
                    Billing Mode: %
                </td>

            </tr>
            <tr>
                <td>
                    Particulars: 1,25-(OH)2 Vit. D(General)
                </td>
                <td id="alignright">
                    Comp: %
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Entry Date</th>
                <th>Encounter</th>
                <th id="alignleft">Invoice</th>
                {{-- <th>TP Bill</th> --}}
                <th id="alignleft">Patient Name</th>
                @if($itemRadio == "packages")
                    <th>Package Name</th>
                @endif
                <th id="alignleft">Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                {{-- <th>Total</th> --}}
                <th>Item Amt</th>
                {{-- <th>Previous Amt</th>
                <th>Remaining Amt</th> --}}
                <th>Prev Deposit</th>
                <th>Received Amt</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_qty = 0;
                $total_rate = 0;
                $total_dsc = 0;
                $total_tax = 0;
                $total_totl = 0;
                $total_particulars = 0;
                $total_prev_amt = 0;
                // $total_remain_amt = 0;
                $total_receive_amt = 0;
            @endphp
            @foreach ($datas as $data)
                @php
                    $maxrow = count($data);
                    $temp = 1;
                @endphp
                @foreach ($data as $data)
                    {{-- @if(($data->fldtempbillno == null && $data->fldsave == 1) || ($data->fldtempbillno != null && $data->fldsave == 0)  || ($data->fldtempbillno != null && $data->fldsave == 1)) --}}
                    @php
                        $total_qty += $data->flditemqty;
                        $total_rate += $data->flditemrate;
                        $total_dsc += $data->flddiscamt;
                        $total_tax += $data->fldtaxamt;
                        $total_totl += $data->fldditemamt;
                        $total_particulars += 1;
                    @endphp
                    <tr>
                        <td>{{((isset($data->entrytime) ? \App\Utils\Helpers::dateToNepali($data->entrytime) :''))}}</td>
                        <td>{{$data->fldencounterval}}</td>
                        <td id="alignleft">{{$data->fldbillno}}</td>
                        {{-- <td id="alignleft">{{$data->fldtempbillno}}</td> --}}
                        {{-- @if($data->fldtempbillno != null && $data->fldsave == 0 && $data->fldtempbilltransfer == 0)
                            <td id="alignleft">{{$data->fldtempbillno}}</td>
                        @else
                            <td id="alignleft">{{$data->fldbillno}}</td>
                        @endif --}}
                        <td id="alignleft">{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                        @if($itemRadio == "packages")
                            <td id="alignleft">{{$data->package_name}}</td>
                        @endif
                        <td id="alignleft">{{$data->flditemname}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($data->flditemrate)}}</td>
                        <td>{{$data->flditemqty}}</td>
                        <td>Rs. {{\App\Utils\Helpers::numberFormat($data->flddiscamt)}}</td>
                        <td>Rs. {{\App\Utils\Helpers::numberFormat($data->fldtaxamt)}}</td>
                        {{-- <td>Rs. {{$data->tot + $data->flddiscamt}}</td> --}}
                        <td>Rs. {{\App\Utils\Helpers::numberFormat($data->fldditemamt)}}</td>
                        @if($temp == 1)
                        @php
                            $receive = \App\Utils\Helpers::receiveAmountAndPrevDepositPerbill($data->fldbillno)['receive'];
                            $previous = \App\Utils\Helpers::receiveAmountAndPrevDepositPerbill($data->fldbillno)['previous'];
                        @endphp
                        <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ". \App\Utils\Helpers::numberFormat($previous) : ""}}</td>
                        <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ". \App\Utils\Helpers::numberFormat($receive) : ""}}</td>
                        {{-- <td rowspan="{{$maxrow}}">Rs. {{ \App\Utils\Helpers::prevamountperbill($data->fldbillno) }}</td>
                        <td rowspan="{{$maxrow}}">Rs. {{ \App\Utils\Helpers::remainamountperbill($data->fldbillno) }}</td> --}}
                        {{-- <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ".\App\Utils\Helpers::receivedamounttotalperbill($data->fldbillno) : ""}}</td> --}}
                        @php
                            // $total_prev_amt += \App\Utils\Helpers::prevamountperbill($data->fldbillno);
                            // $total_remain_amt += \App\Utils\Helpers::remainamountperbill($data->fldbillno);
                            if(($data->fldbillno != "")){
                                $total_prev_amt += $previous;
                                $total_receive_amt += $receive;
                                // $total_receive_amt += \App\Utils\Helpers::receivedamounttotalperbill($data->fldbillno);
                            }
                        @endphp
                        @endif
                        @php
                            $temp += 1;
                        @endphp
                    </tr>
                    {{-- @endif --}}
                @endforeach
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>{{ ($total_particulars > 0) ? ($total_rate/$total_particulars) : "0" }}</td>
                <td>{{ $total_qty }}</td>
                <td>Rs. {{ \App\Utils\Helpers::numberFormat($total_dsc) }}</td>
                <td>Rs. {{ \App\Utils\Helpers::numberFormat($total_tax) }}</td>
                <td colspan="5">Rs. {{ $total_totl }}</td>
            </tr>
        </tfoot> --}}
    </table>
    <div style="float: right;">
        <table id="totaltable">
            <tr>
                <td>Total Quantity</td>
                <td>{{ $total_qty }}</td>
            </tr>
            <tr>
                <td>Total Discount</td>
                <td>Rs. {{\App\Utils\Helpers::numberFormat($total_dsc)}}</td>
            </tr>
            <tr>
                <td>Total Tax</td>
                <td>Rs. {{\App\Utils\Helpers::numberFormat($total_tax)}}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td>Rs. {{\App\Utils\Helpers::numberFormat($total_totl)}}</td>
            </tr>
            <tr>
                <td>Total Previous Amount</td>
                <td>Rs. {{\App\Utils\Helpers::numberFormat($total_prev_amt)}}</td>
            </tr>
            <tr>
                <td>Total Received</td>
                <td>Rs. {{\App\Utils\Helpers::numberFormat($total_receive_amt)}}</td>
            </tr>
        </table>
    </div>
@endsection
