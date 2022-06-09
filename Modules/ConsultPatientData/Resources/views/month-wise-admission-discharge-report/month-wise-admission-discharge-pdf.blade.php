<!DOCTYPE html>
<html>
<head>
    <title>Month Wise Patient Test Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        @page {
            margin: 24mm 0 11mm;
        }


        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }


        .content-body tr td {
            padding: 5px;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body table {
            page-break-inside: auto
        }

        .content-body tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body td, .content-body th{
            text-align: right;
        }

        .content-body td:nth-child(1), .content-body th:nth-child(1){
            text-align: left;
        }

        .content-body {
            font-size: 12px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;            
            border-collapse: collapse;
            border-spacing: 0;
        }

        .table tr th, .table tr td {
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .header {
            display: flex;
            justify-content: center;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>
    <h4 class="header">Month Wise Patient Admission and Discharge Report</h4>
    <table style="width: 100%">
        <tr>
            <td style="text-align: left; width:50%;">
                @if(isset($_GET['from_date']))
                    <p><b>Date:</b>  {{isset($_GET['from_date']) ?$_GET['from_date']:''}}  to
                        {{isset($_GET['to_date']) ? $_GET['to_date']: ''}} 
                    </p>
                @endif
            </td>
            <td style="text-align: right; width:50%;">
                <p><b>Printed Date:</b> {{date('Y-m-d')}} </p>
            </td>
        </tr>
    </table>
    

    <table class="table" id="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Admission Patient</th>
            <th>Discharge Patient</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>BAISHAKH</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(1)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(1)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>JESTHA</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(2)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(2)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>ASAR</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(3)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(3)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>SHRAWAN</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(4)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(4)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>BHADRA</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(5)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(5)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>ASOJ</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(6)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(6)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>KARTIK</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(7)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(7)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>MANGSIR</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(8)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(8)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>POUSH</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(9)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(9)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>MAGH</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(10)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(10)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>FALGUN</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(11)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(11)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>CHAITRA</td>
            <td>
                {{\App\Utils\Helpers::monthWiseAdmission(12)[0]['admission_data']??0}}
            </td>
            <td>
                {{\App\Utils\Helpers::monthWiseDischarge(12)[0]['discharge_data']??0}}
            </td>
        </tr>
        <tr>
            <td>Grand Total</td>
            <td>
                {{$admission_count->admission_data??0}}
            </td>
            <td>
                {{$discharge_count->discharge_data??0}}
            </td>
        </tr>
        
        </tbody>
    </table>              
    @php
        // $signatures = Helpers::getSignature('billing-user-collection-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
