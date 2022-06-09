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
        <th colspan="5" style="text-align: center"><b>{{$finalfrom}} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateNepToEng($finalfrom)->full_date .")" : ''}} TO
                {{$finalto}} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateNepToEng($finalto)->full_date . ")":'' }}</b></th>
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
        <th>Particulars</th>
        <th>Rate</th>
        <th>Qty</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
        @php
            $totalRate = 0;
            $totalQty = 0;
            $totalDisc = 0;
            $totalTax = 0;
            $grandTotal = 0;
        @endphp
        @foreach ($datas as $data)
            <tr>
                <td>{{htmlspecialchars($data->flditemname
                )}}</td>
                <td>Rs. {{$data->rate}}</td>
                <td>{{$data->qnty}}</td>
                <td>Rs. {{$data->dsc}}</td>
                <td>Rs. {{$data->tax}}</td>
                <td>Rs. {{$data->tot}}</td>
            </tr>
            @php
                $totalRate += $data->rate;
                $totalQty += $data->qnty;
                $totalDisc += $data->dsc;
                $totalTax += $data->tax;
                $grandTotal += $data->tot;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td>Rs. {{$totalRate}}</td>
            <td>{{$totalQty}}</td>
            <td>Rs. {{$totalDisc}}</td>
            <td>Rs. {{$totalTax}}</td>
            <td>Rs. {{$grandTotal}}</td>
        </tr>
    </tfoot>
</table>
