<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr>
            <td>S.No</td>
            <td>Group Name</td>
            <td>Group Name (Nep)</td>
            <td>Report Id</td>
            <td>GroupTree</td>
            <td>ParentId</td>
            
            
        </tr>
        @if(isset($results) and count($results) > 0)
            @foreach($results as $r)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$r->GroupName}}</td>
                    <td>{{$r->GroupNameNep}}</td>
                    <td>{{$r->ReportId}}</td>
                    <td>{{$r->GroupTree}}</td>
                    <td>{{$r->ParentId}}</td>
                </tr>
            @endforeach
        @endif
    </thead>
    <tbody>
        
    </tbody>
</table>