<header class="heading" style="margin: 0 auto; width: 98%;text-align:center ">
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" style="width:100%;"/></td>
            <td style="width:60%;">
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                <h5>Pan No.: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</h5>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</header>
