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
                <tr colspan="7">
                    <td>{{($dateType == "invoice_date") ? $data->invoice_date : $data->entry_date}}</td>
                </tr>
                <tr>
                    <td>{{($dateType == "invoice_date") ? $data->invoice_date : $data->entry_date}}</td>
                    <td>{{($itemRadio == "select_item") ? $selectedItem : "%"}}</td>
                    <td>Rs. {{$data->rate}}</td>
                    <td>{{$data->qnty}}</td>
                    <td>Rs. {{$data->dsc}}</td>
                    <td>Rs. {{$data->tax}}</td>
                    <td>Rs. {{$data->totl}}</td>
                </tr>
            @endforeach
    </tbody>
</table>
