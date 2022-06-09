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
        <th>Particular</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td colspan="4" style="text-align: center;"><b>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}} ( {{ $data->fldencounterval }} ) </b></td>
            </tr>
            <tr>
                <td><b>***</b></td>
                <td><b>Rs. {{ $data->dsc }}</b></td>
                <td><b>Rs. {{ $data->tax }}</b></td>
                <td><b>Rs. {{ $data->tot }}</b></td>
            </tr>
        @endforeach
    </tbody>
</table>
