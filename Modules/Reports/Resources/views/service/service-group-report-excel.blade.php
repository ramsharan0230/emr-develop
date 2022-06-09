<table>
    <thead>
    <tr>
        <th></th>
    </tr>
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
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b></b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateNepToEng($eng_from_date)->full_date .")" : ''}} TO
                {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateNepToEng($eng_to_date)->full_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>Datetime: {{ \Carbon\Carbon::now() }}</b></th>
        <th style="text-align: center"></th>
    </tr>

    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ $title == '' ? 'Miscellaneous' : ucwords($title) }}</b></th>
        <th style="text-align: center"></th>
    </tr>

    <tr>
        <th></th>
    </tr>
    <tr>
        <th>SNo</th>
        <th>Bill Number</th>
        <th>Encounter/Patient</th>
        <th>Name</th>
        <th>Item</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Dis</th>
        <th>Amount</th>
        <th>User</th>
        <th>Time</th>
    </tr>
    </thead>
    <tbody>
    @if($serviceData)
        @foreach($serviceData as $service)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $service->BILLNO }}</td>
                <td>{{ $service->PATIENTID }}</td>
                <td>{{ $service->fldptnamefir .' '.$service->fldptnamelast }}</td>
                <td>{{ $service->SERVICETYPE }}</td>
                <td>{{ $service->QTY }}</td>
                <td>{{ $service->AMOUNT }}</td>
                <td>{{ $service->DISCOUNT }}</td>
                <td>{{ $service->Total_Amount }}</td>
                <td>{{ $service->USERNAME }}</td>
                <td>{{ $service->BILLDATETIME }}</td>
            </tr>
        @endforeach
    @endif
        <tr>
            <td colspan="6" style="text-align: right"><b>Total</b></td>
            <td><b>{{ $serviceData->sum('AMOUNT') }}</b></td>
            <td><b>{{ $serviceData->sum('DISCOUNT') }}</b></td>
            <td><b>{{ $serviceData->sum('Total_Amount') }}</b></td>
        </tr>
    </tbody>
</table>
