
<!DOCTYPE html>
<html>

<head>
    <title>Audiogram Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body>
    <div class="pdf-container">
        <div class="heading">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 20%;"></td>
                        <td style="width:57%;">
                            <h3 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                            <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                            <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                            <h4 style="text-align: center;">DEPARTMENT OF ENT</h4>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table  width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border:none;">Name: {{ $audiogramRequestData->encounter->patientInfo->getFldfullnameAttribute() }}</th>
                        <th style="padding: 10px; border:none;">Age: {{ $audiogramRequestData->encounter->patientInfo->getFldageAttribute() }}</th>
                        <th style="padding: 10px; border:none;">Sex: {{ $audiogramRequestData->encounter->patientInfo->fldptsex }}</th>
                        <th style="padding: 10px; border:none;">Examined Date: {{ $audiogramRequestData->examined_date }}</th>
                    </tr>
                    <tr>
                        <th style="padding: 10px; border:none;">Unit:</th>
                        <th style="padding: 10px; border:none;">Rank:</th>
                        <th style="padding: 10px; border:none;">No.:</th>
                        <th style="padding: 10px; border:none;">Reg No.:</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="table-content" style="width: 100%;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
                <tbody>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td rowspan="13" style="border: none; padding: 10px;">&nbsp;</td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 10px; text-align: center;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                        <td style="padding: 10px; border: 1px solid;"></td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
                <tbody>
                    <tr>
                        <td colspan="3" style="padding: 5px; border: 1px solid; text-align: center;">Training Fork Test</td>
                        <td rowspan="13" style="border: none; padding: 5px;">&nbsp;</td>
                        <td colspan="3" style="padding: 5px; border: 1px solid; text-align: center;">Audiogram Symbols</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: 1px solid;">RINEE</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">R1</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">R2</td>
                        <td style="padding: 5px; border: 1px solid;"></td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">R1</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">L2</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: 1px solid;">WEBBER</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">R1</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">R2</td>
                        <td style="padding: 5px; border: 1px solid;">Air Conduction</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">&#9675;</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">x</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: none;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="padding: 5px; border: 1px solid;">Base Conduction</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;"><</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: none;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="padding: 5px; border: 1px solid;">Ac With masking</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">&#9651;</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">&#9645;</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: none;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="padding: 5px; border: 1px solid;">BC with masking</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">[</td>
                        <td style="border: 1px solid; padding: 5px; text-align: center;">]</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: none;">Interpretation:</td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="padding: 5px; border: 1px solid;">No Response</td>
                        <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;font-weight: bold;">&#8595;</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; border: none;">ENT Summary:</td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="border: none; padding: 5px;"></td>
                        <td style="padding: 16px; border: 1px solid;">PTA</td>
                        <td style="border: 1px solid; padding: 16px; text-align: center;"></td>
                        <td style="border: 1px solid; padding: 16px; text-align: center;"></td>
                    </tr>
                
                </tbody>
            </table>
        </div>
    </div>
    <h5>TYMPANOGRAM REPORT</h5>
    <div class="sign" style="float: right; padding-right: 14px;">
        <h4 style="border-top: 1px dashed;">Signature of Audiogram</h4>
    </div>
</body>

</html>
