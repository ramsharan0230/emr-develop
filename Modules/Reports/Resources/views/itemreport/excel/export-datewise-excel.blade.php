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
        <th>Date</th>
        <th>Particulars</th>
        <th>Rate</th>
        <th>Qty</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td>{{($dateType == "invoice_date") ? \App\Utils\Helpers::dateToNepali($data->invoice_date) : \App\Utils\Helpers::dateToNepali($data->entry_date)}}</td>
                <td>{{($itemRadio == "select_item") ? $selectedItem : "%"}}</td>
                <td>{{ \App\Utils\Helpers::numberFormat(($data->rate))}}</td>
                <td>{{$data->qnty}}</td>
                <td>Rs. {{ \App\Utils\Helpers::numberFormat(($data->dsc))}}</td>
                <td>Rs. {{ \App\Utils\Helpers::numberFormat(($data->tax))}}</td>
                <td>Rs. {{ \App\Utils\Helpers::numberFormat(($data->totl + $data->dsc))}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
