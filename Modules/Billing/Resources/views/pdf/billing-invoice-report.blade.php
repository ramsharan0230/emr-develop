<!DOCTYPE html>
<html>
<head>
    <title>Billing Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
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

        .content-body {
            font-size: 12px;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    <ul>
        <li>Billing Report </li>
        <li>{{$from_date ? \App\Utils\Helpers::dateToNepali($from_date) : ''}} To {{$to_date ? \App\Utils\Helpers::dateToNepali($to_date) : ''}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">User</th>

            <th class="tittle-th">BillCount</th>
            <th class="tittle-th">ItemAmt</th>
            <th class="tittle-th">DiscAmt</th>
            <th class="tittle-th">TaxAmt</th>
            <th class="tittle-th">TotalAmt</th>
            <th class="tittle-th">RecAmt</th>
        </tr>
        </thead>
        <tbody>

        @if(isset($users))
            @foreach($users as $k=>$u)
                @php
                   $result = \DB::table('tblpatbilldetail as p')
                        ->select('p.fldcomp')
                        ->where('p.flduserid', $u->flduserid)
                        ->distinct()->get();

                @endphp

                    <tr><td colspan="7" style="text-align: center; font-weight: bold;">{{$u->flduserid}}</td></tr>
                    @php
                        $totitmamt = array();
                        $totbillcount = array();
                        $totdiscamt = array();
                        $tottaxamt = array();
                        $totnetamount = array();
                        $totrecamt = array();
                    @endphp
                    @if(isset($result) and count($result) > 0)
                        @foreach($result as $r)

                            @php
                                $datasql = "select COUNT(fldid) as num,SUM(flditemamt) as itmamt,SUM(fldtaxamt) as taxamt,SUM(flddiscountamt) as discamt,SUM(flditemamt+fldtaxamt-flddiscountamt) as netam,SUM(fldreceivedamt) as recvamt from tblpatbilldetail where fldid >='".$fromfldid."' and fldid <='".$tofldid."' and flduserid ='".$u->flduserid."' and fldcomp = '".$r->fldcomp."'";

                                $resultdata = \DB::select($datasql);
                            @endphp

                            @foreach($resultdata as $data)
                                @php
                                    $totitmamt[] = $data->itmamt;
                                    $totbillcount[] = $data->num;
                                    $totdiscamt[] = $data->discamt;
                                    $tottaxamt[] = $data->taxamt;
                                    $totnetamount[] = $data->netam;
                                    $totrecamt[] = $data->recvamt;
                                    $fldcomp = '@'.\App\Utils\Helpers::getDepartmentFromCompID($r->fldcomp);
                                @endphp
                                <tr>
                                    <td>{{$u->flduserid}}{{$fldcomp}}</td>
                                    <td>{{$data->num}}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($data->itmamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($data->discamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($data->taxamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($data->netam) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($data->recvamt) }}</td>
                                </tr>
                            @endforeach

                        @endforeach
                    @endif
                    <tr>
                        <td>{{$u->flduserid}} : Total</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(array_sum($totbillcount)) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(array_sum($totitmamt)) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(array_sum($totdiscamt)) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(array_sum($tottaxamt)) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(array_sum($totnetamount)) }}</td>
                        <td>{{ n\App\Utils\Helpers::numberFormat(array_sum($totrecamt)) }}</td>
                    </tr>
                    <tr>
                        <td>***</td>
                        <td>***</td>
                        <td>***</td>
                        <td>***</td>
                        <td>***</td>
                        <td>***</td>
                        <td>***</td>
                    </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>