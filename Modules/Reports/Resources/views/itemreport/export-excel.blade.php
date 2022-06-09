<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From date:</b></th>
            <th colspan="2">{{ $finalfrom }} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateToNepali($finalfrom). ")" :'' }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To date:</b></th>
            <th colspan="2">{{ $finalto }} {{ isset($finalto) ? "(". \App\Utils\Helpers::dateToNepali($finalto). ")" :'' }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Category:</b></th>
            <th colspan="2">{{ $category }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Billing Mode:</b></th>
            <th colspan="2">{{ $billingmode }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Comp:</b></th>
            <th colspan="2">{{ $comp }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Particulars:</b></th>
            <th colspan="2">{{ ($itemRadio == "select_item") ? $selectedItem : "All" }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>Entry Date</th>
            <th>Encounter</th>
            <th>Invoice</th>

            <th>Patient Name</th>
            @if($itemRadio == "packages")
                <th>Package Name</th>
            @endif
            <th>Particulars</th>
            <th>Rate</th>
            <th>Qty</th>
            <th>Disc</th>
            <th>Tax</th>

            <th>Item Amt</th>

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
                {{-- @if(!($data->fldtempbillno == null && $data->fldsave == 0)) --}}
                @php
                    $total_qty += $data->flditemqty;
                    $total_rate += $data->flditemrate;
                    $total_dsc += $data->flddiscamt;
                    $total_tax += $data->fldtaxamt;
                    $total_totl += $data->fldditemamt;
                    $total_particulars += 1;
                @endphp
                <tr>
                    <td>{{(( isset($data->entrytime) ? \App\Utils\Helpers::dateToNepali($data->entrytime) :''))}}</td>
                    <td>{{$data->fldencounterval}}</td>
                    <td>{{$data->fldbillno}}</td>
                    {{-- <td>{{$data->fldtempbillno}}</td> --}}
                    {{-- @if($data->fldtempbillno != null && $data->fldsave == 0)
                        <td>{{$data->fldtempbillno}}</td>
                    @else
                        <td>{{$data->fldbillno}}</td>
                    @endif --}}
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                    @if($itemRadio == "packages")
                        <td>{{$data->package_name}}</td>
                    @endif
                    <td>{{htmlspecialchars($data->flditemname)}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($data->flditemrate)}}</td>
                    <td>{{$data->flditemqty}}</td>
                    <td>Rs. {{ \App\Utils\Helpers::numberFormat($data->flddiscamt)}}</td>
                    <td>Rs. {{ \App\Utils\Helpers::numberFormat($data->fldtaxamt)}}</td>

                    <td>Rs. {{ \App\Utils\Helpers::numberFormat($data->fldditemamt)}}</td>
                    @if($temp == 1)
                    {{-- <td rowspan="{{$maxrow}}">Rs. {{ \App\Utils\Helpers::prevamountperbill($data->fldbillno) }}</td>
                    <td rowspan="{{$maxrow}}">Rs. {{ \App\Utils\Helpers::remainamountperbill($data->fldbillno) }}</td> --}}
                    @php
                        $receive = \App\Utils\Helpers::receiveAmountAndPrevDepositPerbill($data->fldbillno)['receive'];
                        $previous = \App\Utils\Helpers::receiveAmountAndPrevDepositPerbill($data->fldbillno)['previous'];
                    @endphp
                    <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ". \App\Utils\Helpers::numberFormat($previous) : ""}}</td>
                    <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ". \App\Utils\Helpers::numberFormat($receive) : ""}}</td>
                    {{-- <td rowspan="{{$maxrow}}">{{ ($data->fldbillno != "") ? "Rs. ".\App\Utils\Helpers::receivedamounttotalperbill($data->fldbillno) : ""}}</td> --}}
                    @php

                        if(($data->fldbillno != "")){
                            $total_prev_amt += $previous;
                            $total_receive_amt += $receive;

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
    <tfoot>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr>
            <th colspan="2"><b>Total Quantity:</b></th>
            <th colspan="2">{{ $total_qty }}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Discount:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_dsc)}}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Tax:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_tax)}}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Amount:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_totl)}}</th>
        </tr>
        {{-- <tr>
            <th colspan="2"><b>Total Previous Amt:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_prev_amt) }}</th>
        </tr>
        {{-- <tr>
            <th colspan="2"><b>Total Remaining Amt:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_remain_amt) }}</th>
        </tr> --}}
        <tr>
            <th colspan="2"><b>Total Received:</b></th>
            <th colspan="2">{{ \App\Utils\Helpers::numberFormat($total_receive_amt)}}</th>
        </tr>
    </tfoot>
</table>
