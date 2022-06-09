<table>
    <thead>
        <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th><b>From date:</b></th>
        <th colspan="2">{{ $eng_from_date }} {{ isset($eng_from_date) ? "(". \App\Utils\Helpers::dateToNepali($eng_from_date) .")" :'' }}</th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th><b>To date:</b></th>
        <th colspan="2">{{ $eng_to_date }} {{ isset($eng_to_date) ? "(". \App\Utils\Helpers::dateToNepali($eng_to_date)  .")" :'' }}</th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th><b>Printed At:</b></th>
        <th colspan="2">{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b></th>
        <th colspan="2">{{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>
    <tr><th></th></tr>
    <tr>
        <th>S.N.</th>
        <th>Bill No.</th>
        <th>Patient ID/Enc ID</th>
        <th>Patient First Name</th>
        <th>Patient First Middle Name</th>
        <th>Patient First Last Name</th>
        <th>Patient Age</th>
        <th>Patient sex</th>
        <th>Patient Contact</th>
        <th>UserId</th>
        <th>Date Time</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
        @if(!$unsampled_test->isEmpty())
            @php
                $count=1;
            @endphp
            @foreach ($unsampled_test as $list)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$list->fldbillno}}</td>
                <td>{{$list->fldpatientval}}/{{$list->encounter_id}}</td>
                <td>{{strtoupper($list->fldptnamefir) ?? ''}}</td>
                <td>{{strtoupper($list->fldmidname) ?? ''}}</td>
                <td>{{strtoupper($list->fldptnamelast) ?? ''}}</td>
                <td>{{Carbon\Carbon::parse($list->fldptbirday)->age ?? ''}} Y</td>
                <td>{{$list->fldptsex ?? ''}}</td>
                <td>{{$list->fldptcontact ?? ''}}</td>
                <td>{{$list->user_id}}</td>
                <td>{{$list->date}}</td>
                <td>{{$list->fldstatus}}</td>
            </tr>
            @endforeach
        @endif
    </tbody>