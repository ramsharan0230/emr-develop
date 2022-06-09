<h2>{{ $certificate }}</h2>
<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ $selectedItem }}</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{$finalfrom}} TO {{$finalto}}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>Datetime: {{ \Carbon\Carbon::now() }}</b></th>
        <th style="text-align: center"></th>
    </tr>

    <tr><th></th></tr>
    <tr>
        <th>Particular</th>
        <th>Rate</th>
        <th>Qty</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total Amount</th>
        <th>Receive Amount</th>
    </tr>
    </thead>
    <tbody>
        @php
            $total_qty = 0;
            $total_rate = 0;
            $total_dsc = 0;
            $total_tax = 0;
            $total_totl = 0;
            $total_received = 0;
        @endphp
        @foreach ($datas as $itemtype => $data)
            <tr>
                <td colspan="9" style="text-align: center;"><b>{{ htmlspecialchars($itemtype) }}</b></td>
            </tr>
            @foreach($data as $date=>$d)
                <td colspan="9"><b>{{ isset($date) ? \App\Utils\Helpers::dateToNepali($date) :'' }}</b></td>
                @foreach($d as $item)
                    @php
                        $total_qty += $item->qnty;
                        $total_rate += $item->rate;
                        $total_dsc += $item->dsc;
                        $total_tax += $item->tax;
                        $total_totl += ($item->totl + $item->dsc);
                        $total_received += $item->totl;
                    @endphp
                    <tr>
                        <td>{{htmlspecialchars($item->flditemname)}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($item->rate))}}</td>
                        <td>{{$item->qnty}}</td>
                        <td>Rs. {{ \App\Utils\Helpers::numberFormat(($item->dsc))}}</td>
                        <td>Rs. {{ \App\Utils\Helpers::numberFormat(($item->tax))}}</td>
                        <td>Rs. {{ \App\Utils\Helpers::numberFormat(($item->totl + $item->dsc))}}</td>
                        <td>Rs. {{ \App\Utils\Helpers::numberFormat(($item->totl))}}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Total Quantity</td>
            <td>{{$total_qty}}</td>
        </tr>
        <tr>
            <td>Total Discount</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($total_dsc))}}</td>
        </tr>
        <tr>
            <td>Total Tax</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($total_tax))}}</td>
        </tr>
        <tr>
            <td>Total Amount</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($total_totl))}}</td>
        </tr>
        <tr>
            <td>Total Received</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($total_received))}}</td>
        </tr>
    </tfoot>
</table>
