
<table class="table" id="table">
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
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>From date:</b></th>
        <th colspan="2">{{ $from_date }} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateToNepali($from_date) .")" :'' }}</th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>To date:</b></th>
        <th colspan="2">{{ $to_date }} {{ isset($to_date) ? "(". \App\Utils\Helpers::dateToNepali($to_date)  .")" :'' }}</th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed At:</b>{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN</th>
        <th>Department Name</th>
        <th>Numbers of Consultation</th>
    </tr>
    </thead>
    <tbody>
    @if(!$top_ten_dept->isEmpty())
    <?php
        $count = 1;
    ?>
    @foreach ($top_ten_dept as $list)
    <tr>
        <td>{{$count++}}</td>
        <td>{{$list->name}} </td>
        <td>{{$list->test_count}} </td>
    </tr>
    @endforeach
    @endif
    </tbody>
</table>
