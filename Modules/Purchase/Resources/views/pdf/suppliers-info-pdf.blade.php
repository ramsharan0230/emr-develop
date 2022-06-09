<!DOCTYPE html>
<html>
<head>
    <title>Supplier Information</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>Supplier</th>
            <th>Address</th>
            <th>Status</th>
            <th>PAID</th>
            <th>TO PAY</th>
            <th>NET</th>
        </tr>
        </thead>
        <tbody>
        @if(count($get_supplier_info))
            @php $total_balance = 0; @endphp
            @foreach($get_supplier_info as $info)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $info->fldsuppname }}</td>
                    <td>{{ $info->fldsuppaddress }}</td>
                    <td>{{ $info->fldactive }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($info->fldpaiddebit)) }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($info->fldleftcredit)) }}</td>
                    @php $tot = $info->fldleftcredit - $info->fldpaiddebit @endphp
                    <td>{{  \App\Utils\Helpers::numberFormat(($tot)) }}</td>
                    @php $total_balance += $tot @endphp
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>All</td>
                <td></td>
                <td></td>
                <td>{{  \App\Utils\Helpers::numberFormat(($total_debit_sum)) }}</td>
                <td>{{  \App\Utils\Helpers::numberFormat(($total_credit_sum)) }}</td>
                <td>{{  \App\Utils\Helpers::numberFormat(($total_balance)) }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</main>
</body>
</html>
