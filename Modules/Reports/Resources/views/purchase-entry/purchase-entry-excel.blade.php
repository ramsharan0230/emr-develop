
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
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</b></th>
    </tr>

    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Purchase Entry Report</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From date:</b></th>
        <th colspan="2">{{ $from_date }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To date:</b></th>
        <th colspan="2">{{ $to_date }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2">{{ (isset($entries[0])) ? $entries[0]->fldexpiry : '' }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SNo.</th>
        <th>Supplier Name</th>
        <th>Purchase Date</th>
        <th>Category</th>
        <th>Item Name</th>
        <th>Batch</th>
        <th>Expiry</th>
        <th>P.E Ref No</th>
        <th>GRN No.</th>
        {{-- <th>Quantity</th> --}}
        <td>Purchased Qty</td>
        <td>Qty Bon</td>
        <td>Total Qty</td>
        <td>NetCost</td>
        <td>VAT AMT</td>
        <td>CCost</td>
        <td>Sub Total</td>
        <td>Total Amount</td>
        {{-- <th>Sell Rate</th> --}}
        <th>Department</th>
    </tr>
    </thead>
    <tbody>
    @if($entries)
        @php
            $i = 1;
        @endphp
        @php
            $total_dsc = 0;
            $total_totl = 0;
            $total_vat = 0;
            $total_cc = 0;
            $total_subtotal = 0;
        @endphp
        @foreach ($entries as $entry)
            @foreach ($entry->purchase as $purchase)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $purchase->fldsuppname }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->fldpurdate)->format('Y-m-d') ?? null }}</td>
                    <td>{{ $entry->fldcategory }}</td>
                    <td>{{ $entry->fldstockid }}</td>
                    <td>{{ $entry->fldbatch }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->fldexpiry)->format('Y-m-d')}}</td>
                    <td>{{ $purchase->fldreference ?? null }}</td>
                    <td>{{ $purchase->fldgrnno ?? null }}</td>
                    {{-- <td>{{ $entry->fldqty }}</td> --}}
                    <td>{{ $purchase->fldtotalqty }}</td>
                    <td>{{ ($purchase->fldqtybonus) ? $purchase->fldqtybonus : "0" }}</td>
                    <td>{{ $purchase->fldtotalqty + (($purchase->fldqtybonus) ? $purchase->fldqtybonus : 0) }}</td>
                    {{-- <td>{{ $entry->fldsellpr }}</td> --}}
                    <td>Rs. {{ ($purchase->fldnetcost) ?  \App\Utils\Helpers::numberFormat(($purchase->fldnetcost)) : "0.00" }}</td>
                    @php
                        $vamt = ($purchase->fldvatamt) ?  $purchase->fldvatamt : 0.00;
                    @endphp
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($vamt)}}</td>
                    @php
                        $carcost = ($purchase->fldcarcost) ?  ($purchase->fldcarcost) : 0.00;
                        $totcost = ($purchase->fldtotalcost) ?  ($purchase->fldtotalcost) : 0.00;
                        $total = (($purchase->fldnetcost) ?  ($purchase->fldnetcost) : 0) * $entry->fldtotalqty;
                        $subtotal = $totcost - $vamt;
                    @endphp
                    <td>{{ \App\Utils\Helpers::numberFormat($carcost) }}</td>
                    <td>Rs. {{  \App\Utils\Helpers::numberFormat(($subtotal)) }}</td>
                    <td>Rs. {{  \App\Utils\Helpers::numberFormat(($totcost + $carcost)) }}</td>
                    <td>{{ \App\Utils\Helpers::getDepartmentFromComp($entry->fldcomp) }}</td>
                    @php
                        $total_dsc += ($purchase->fldcasdisc) ?  ($purchase->fldcasdisc) : 0.00;
                        $total_totl += ($totcost + $carcost);
                        $total_cc += $carcost;
                        $total_vat += ($purchase->fldvatamt) ? $purchase->fldvatamt : 0.00;
                        $total_subtotal += $subtotal;
                    @endphp
                </tr>
                @php
                    ++$i;
                @endphp
            @endforeach
        @endforeach
    @endif
    </tbody>
    <tfoot>
        <tr>
            <td>Sub Total</td>
            <td> Rs. {{ \App\Utils\Helpers::numberFormat(($total_subtotal))}}</td>
        </tr>
        <tr>
            <td>Total Vat Amt</td>
            <td>Rs. {{  \App\Utils\Helpers::numberFormat(($total_vat)) }}</td>
        </tr>
        <tr>
            <td>Total Discount</td>
            <td>Rs. {{  \App\Utils\Helpers::numberFormat(($total_dsc)) }}</td>
        </tr>
        <tr>
            <td>Total Carry Cost</td>
            <td>Rs. {{  \App\Utils\Helpers::numberFormat(($total_cc)) }}</td>
        </tr>
        @php
            $totaftergroupdisc = $total_subtotal + $total_vat + $total_cc - (($total_dsc) ? $total_dsc : 0);
        @endphp
        <tr>
            <td>Total Amount</td>
            <td>Rs. {{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</td>
        </tr>
    </tfoot>
</table>
