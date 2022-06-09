<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Referable Doctors List</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="10"><b>From: {{$from_date}} {{ isset($eng_from_date) ? "(". $eng_from_date .")" : ''}} TO
                {{$to_date}} {{ isset($eng_to_date) ? "(" .$eng_to_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::now() }}</th>
    </tr>

    <tr><th></th></tr>
    <tr>
        <th class="text-center" >S/N</th>
        <th class="text-center" >Name</th>
        <th class="text-center" >Date</th>
        <th class="text-center" >Item Name</th>
        <th class="text-center" >Type</th>
        <th class="text-center" >Share</th>
        <th class="text-center" >Tax Amt</th>
    </tr>
    </thead>
    <tbody>
    {!! $html !!}
    </tbody>
</table>
