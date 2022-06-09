<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>Supplier:</b></th>
            <th colspan="2">{{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldsuppname : "" }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>Payment:</b></th>
            <th colspan="2">{{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldpurtype : "" }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>Invoice:</b></th>
            <th colspan="2">{{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldbillno : "" }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>Reference:</b></th>
            <th colspan="2">{{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldreference : "" }}</th>
        </tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr>
            <td>&nbsp;</td>
            <td>Category</td>
            <td>Generic</td>
            <td>Brand</td>
            <td>Batch</td>
            <td>Expiry</td>
            <td>CasDisc</td>
            <td>Purchased Qty</td>
            <td>Qty Bon</td>
            <td>Total Qty</td>
            <td>NetCost</td>
            <td>VAT AMT</td>
            <td>CCost</td>
            <td>Sub Total</td>
            <td>Total Amount</td>
        </tr>
    </thead>
    <tbody>
        @php
            $total_dsc = 0;
            $total_totl = 0;
            // $total_vat = 0;
            $total_vat = ($purchaseBillDetails->fldtotaltax) ? $purchaseBillDetails->fldtotaltax : 0;
            $total_cc = 0;
            $total_subtotal = 0;
            $sub_total = 0;
        @endphp
        @foreach($purchaseEntries as $entry)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $entry->fldcategory }}</td>
            @if ($entry->fldcategory == "Medicines")
                <td>{{ (isset($entry->medbrand)) ? $entry->medbrand->flddrug : "" }}</td>
                <td>{{ (isset($entry->medbrand)) ? $entry->medbrand->fldbrand : "" }}</td>
            @elseif ($entry->fldcategory == "Surgicals")
                <td>{{ (isset($entry->surgbrand)) ? $entry->surgbrand->fldsurgid : "" }}</td>
                <td>{{ (isset($entry->surgbrand)) ? $entry->surgbrand->fldbrand : "" }}</td>
            @else
                <td>{{ (isset($entry->extrabrand)) ? $entry->extrabrand->fldextraid : "" }}</td>
                <td>{{ (isset($entry->extrabrand)) ? $entry->extrabrand->fldbrand : "" }}</td>
            @endif
            <td>{{ $entry->Entry->fldbatch }}</td>
            <td>{{ $entry->Entry->fldexpiry }}</td>
            <td>Rs. {{ ($entry->fldcasdisc) ? $entry->fldcasdisc : "0.00" }}</td>
            <td>{{ $entry->fldtotalqty }}</td>
            <td>{{ ($entry->fldqtybonus) ? $entry->fldqtybonus : "0" }}</td>
            <td>{{ $entry->fldtotalqty + (($entry->fldqtybonus) ? $entry->fldqtybonus : 0) }}</td>
            <td>Rs. {{ ($entry->fldnetcost) ? \App\Utils\Helpers::numberFormat(($entry->fldnetcost)) : "0.00" }}</td>
            @php
                $vamt = ($entry->fldvatamt) ? (($entry->fldvatamt)) : 0.00;
            @endphp
            <td>Rs. {{\App\Utils\Helpers::numberFormat($vamt)}}</td>
            @php
                $carcost = ($entry->fldcarcost) ? (($entry->fldcarcost)) : 0.00;
                $totcost = ($entry->fldtotalcost) ? (($entry->fldtotalcost)) : 0.00;
                $total = $entry->fldnetcost * $entry->fldtotalqty;
                $sub_total += $total;
                $subtotal = $totcost - $vamt;
                                $total_dsc += ($entry->fldcasdisc) ? (($entry->fldcasdisc)) : 0.00;

            @endphp
            <td>{{ \App\Utils\Helpers::numberFormat($carcost) }}</td>
            <td>Rs. {{ \App\Utils\Helpers::numberFormat(($total)) }}</td>
            <td>Rs. {{ \App\Utils\Helpers::numberFormat(($total + $carcost-$total_dsc)) }}</td>
            @php
                $total_totl += ($totcost + $carcost);
                $total_cc += $carcost;
                // $total_vat += ($entry->fldvatamt) ? $entry->fldvatamt : 0.00;
                 if(isset($purchaseBillDetails->fldtotalvat) && $purchaseBillDetails->fldtotalvat> 0){
                    $total_vat = ($purchaseBillDetails->fldtotalvat) ? $purchaseBillDetails->fldtotalvat : 0;
                }else{
                    $total_vat = (isset($purchaseBillDetails->fldtotaltax)) ? $purchaseBillDetails->fldtotaltax : 0;
                }
                $total_subtotal += $subtotal;
            @endphp
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr>
            <th colspan="2"><b>Sub Total:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($sub_total)) }}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Vat Amt:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($total_vat)) }}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Discount:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($total_dsc)) }}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Group Discount:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($purchaseBillDetails->fldlastdisc)) }}</th>
        </tr>
        <tr>
            <th colspan="2"><b>Total Carry Cost:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($total_cc)) }}</th>
        </tr>
        @php
            $totaftergroupdisc = $sub_total + $total_vat + $total_cc - (($purchaseBillDetails->fldlastdisc) ? $purchaseBillDetails->fldlastdisc : 0) - (($total_dsc) ? $total_dsc : 0);
            // $totaftergroupdisc = $total_totl - (($purchaseBillDetails->fldlastdisc) ? $purchaseBillDetails->fldlastdisc : 0) - (($total_dsc) ? $total_dsc : 0);
            // + $total_cc
        @endphp
        <tr>
            <th colspan="2"><b>Total Amount:</b></th>
            <th colspan="2">Rs. {{ \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</th>
        </tr>
    </tfoot>
</table>
