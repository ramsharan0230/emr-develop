<style>
    @page {
        margin: 22mm auto 12mm;
    }
    table tr td h2, h4{
        line-height: 0.5rem;
    }
    body {
        margin: 0 auto;
        padding: 10px;
    }

    /*header {
        position: fixed;
        top: 2.5rem;
        left: 2.5rem;
        right: 0cm;
    }*/

    /** Define the footer rules **/
    /*footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2rem;

    }*/
    @media screen {
        @page{
            margin: 22mm auto 12mm;
        }
    }
</style>
<header>
    <table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 15%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
        <td style="width:70%;">
            <h2 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h2>
            <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
            <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>
</header>
