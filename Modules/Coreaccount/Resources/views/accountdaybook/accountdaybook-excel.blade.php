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
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Day Book Report</b></th>
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
        <th><b>Printed Date:</b>{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>

    <tr><th></th></tr>
    <tr>
    <th class="text-center" >S/N</th>
            <th>TranDateBS</th>
            <th>TranDateAD</th>
            <th class="text-center" >Voucher No</th>
            <th class="text-center" >Voucher Type</th>
            <th class="text-center" >Voucher Date</th>
            <th class="text-center" >Amount</th>
            <th class="text-center" >User</th>
    </tr>
    </thead>
    <tbody>
    {!! $html !!}
    </tbody>
</table>
