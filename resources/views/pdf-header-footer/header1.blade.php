<style>   
    @page {
        margin: 5mm 0;
    }
</style>
<header class="pdf-container">
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
            <td style="width:60%;">
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name_nepali'])?Options::get('siteconfig')['system_name_nepali']:'' }}</h3>
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;"> Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;"> Email: {{ Options::get('system_email') ? Options::get('system_email'):'' }}</h4>
                {{--                @if(isset($certificate))--}}
                {{--                    <h4 style="text-align: center;">{{ucfirst($certificate)}} REPORT</h4>--}}
                {{--                @endif--}}
            </td>
            <td style="width: 20%;"></td>
        </tr>
        </tbody>
    </table>
</header>
