<!DOCTYPE html>
<html>
<head>
    <title>Under Stock REPORT</title>
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
        <li>{{date('Y-m-d H:i:s')}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">Particulars</th>
            <th class="tittle-th">Manufacturer</th>
            <th class="tittle-th">Standard</th>
            <th class="tittle-th">MinQTY</th>]
            <th class="tittle-th">CurrentQTY</th>
            <th class="tittle-th">Comment</th>
            
            
        </tr>
        </thead>
        <tbody>
            <tr><td colspan="6" style="text-align: center;"><b>Medicines</b></td></tr>
            @if(count($medicines))
                @foreach($medicines as $k=>$m)
               
                    <tr>
                        <td>{{$m->fldbrandid}}</td>
                        <td>{{$m->fldmanufacturer}}</td>
                        <td>{{$m->fldstandard}}</td>
                        <td>{{$m->fldminqty}}</td>
                        <td>{{ Helpers::getCurrentQty($m->fldbrandid) }}</td>
                       <td></td>
                        
                    </tr>
                @endforeach
            @endif
            <tr><td colspan="6" style="text-align: center;"><b>Surgicals</b></td></tr>
            @if(count($surgical))
                @foreach($surgical as $k=>$s)
                    
                    <tr>
                        <td>{{$s->fldbrandid}}</td>
                        <td>{{$s->fldmanufacturer}}</td>
                        <td>{{$s->fldstandard}}</td>
                        <td>{{$s->fldminqty}}</td>
                        <td>{{Helpers::getCurrentQty($s->fldbrandid)}}</td>
                       <td></td>
                        
                    </tr>
                @endforeach
            @endif
            <tr><td colspan="6" style="text-align: center;"><b>Extra</b></td></tr>
            @if(count($extra))
                @foreach($extra as $k=>$e)
                    <tr>
                        <td>{{$s->fldbrandid}}</td>
                        <td>{{$s->fldmanufacturer}}</td>
                        <td>{{$s->fldstandard}}</td>
                        <td>{{$s->fldminqty}}</td>
                        <td>{{Helpers::getCurrentQty($e->fldbrandid)}}</td>
                       <td></td>
                        
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
  @php
        $signatures = Helpers::getSignature('under-stock-report'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
