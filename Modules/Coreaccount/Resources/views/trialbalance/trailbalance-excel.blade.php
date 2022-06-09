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
        <th colspan="5"><b>Trial balance</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="10"><b>{{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateNepToEng($from_date)->full_date .")" : ''}} TO
                {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateNepToEng($to_date)->full_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Datetime:</b></th>
        <th colspan="5">{{ \Carbon\Carbon::now() }}</th>
    </tr>

    <tr><th></th></tr>
    <tr>
            <th class="text-center" rowspan="2">S/N</th>
            <th class="text-center" rowspan="2">Group</th>
            <th class="text-center" rowspan="2">SubGroup</th>
            <th class="text-center" rowspan="2">Account</th>
            <th class="text-center" colspan="2">Opening</th>
            <th class="text-center" colspan="2">Turnover</th>
            <th class="text-center" colspan="2">Closing</th>
        </tr>
        <tr>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
        </tr>
    </thead>
    <tbody>
    {!! $html !!}
    </tbody>
</table>
