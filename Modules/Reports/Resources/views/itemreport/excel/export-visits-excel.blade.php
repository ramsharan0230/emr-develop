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
        <th>EncId</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            @if(isset($data->encounter->patientInfo))
            <tr>
                <td>{{ $data->fldencounterval }}</td>
                <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->fldptsex : ""}}</td>
                <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFullAddress() : ""}}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>
