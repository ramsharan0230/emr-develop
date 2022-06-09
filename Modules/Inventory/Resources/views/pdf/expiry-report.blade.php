<!DOCTYPE html>
<html>
<head>
    <title>Expiry REPORT</title>
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
        <li>Expiry List :</li>
        <li>{{$date}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">Location</th>
            <th class="tittle-th">Category</th>
            <th class="tittle-th">Particulars</th>
            <th class="tittle-th">Batch</th>
            <th class="tittle-th">Expiry</th>
            <th class="tittle-th">Order</th>
            <th class="tittle-th">QTY</th>
            <th class="tittle-th">Sell</th>
            <th class="tittle-th">Amt</th>
            
        </tr>
        </thead>
        <tbody>
        @if(count($result))
            @foreach($result as $k=>$data)
           
                @php
                    $amt = $data->fldqty * $data->fldsellpr;
                @endphp
                <tr>
                    <td>{{$data->fldcomp}}</td>
                    <td>{{$data->fldcategory}}</td>
                    <td>{{$data->fldstockid}}</td>
                    <td>{{$data->fldbatch}} </td>
                    <td>{{$data->fldexpiry}}</td>
                    <td>{{$data->fldstatus}}</td>
                    <td>{{$data->fldqty}}</td>
                    <td>Rs.{{$data->fldsellpr}}.00</td>
                    
                    <td>Rs.{{$amt}}.00</td>
                    
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('inventory-expiry-report'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
