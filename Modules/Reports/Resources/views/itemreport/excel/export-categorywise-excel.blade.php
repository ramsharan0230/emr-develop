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
        <th>Category</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total Amount</th>
        <th>Receive Amount</th>
    </tr>
    </thead>
    <tbody>
        @php
            $total_dsc = 0;
            $total_tax = 0;
            $total_totl = 0;
            $total_received = 0;
        @endphp
        @foreach ($datas as $data)
            @php
                $total_dsc += $data->dsc;
                $total_tax += $data->tax;
                $total_totl += ($data->totl + $data->dsc);
                $total_received += $data->totl;
            @endphp
            <tr>
                <td>{{htmlspecialchars($data->flditemtype)}}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($data->dsc)) }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($data->tax)) }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($data->totl + $data->dsc)) }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($data->totl)) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
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
