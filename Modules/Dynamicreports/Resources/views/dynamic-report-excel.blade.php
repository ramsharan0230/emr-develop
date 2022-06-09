<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From date:</b></th>
            <th colspan="2">{{ $finalfrom }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To date:</b></th>
            <th colspan="2">{{ $finalto }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Report:</b></th>
            <th colspan="2">{{ $reportData->fldreportname }}</th>
        </tr>
        <tr><th></th></tr>
        {!! $thead !!}
    </thead>
    <tbody>
        {!! $tbody !!}
    </tbody>
</table>
