<style>
       @page {
            margin-top: 5mm;
            margin-bottom: 5mm;
        }

    </style>

<header class="pdf-container hospital-header-container">
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100" /></td>
                <td style="width:60%;">
                    <h3 class="text-center" style="margin-bottom:8px;"></h3>
                    <h3 class="text-center" style="margin-bottom:8px;">{{Options::get('siteconfig')['system_name']??''}}</h3>
                    <h4 class="text-center" style="margin-top:2px;margin-bottom:0;">{{Options::get('siteconfig')['system_address']??''}}</h4>
                    <h4 class="text-center" style="margin-top:2px;"> Contact No: {{Options::get('system_mobile')??''}}</h4>
                    <h4 class="text-center" style="margin-top:2px;"> Email: {{Options::get('system_feedback_email')??''}}</h4>
                    
                </td>
                <td style="width: 20%;text-align: right;"></td>
            </tr>
        </tbody>
    </table>
</header>