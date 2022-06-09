<!DOCTYPE html>
<html>
<head>
    <title>Kitchen Attendance REPORT</title>
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
        <li>Kitchen Dietitian Report : %</li>
        <li>{{$from}} To {{$to}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">Diet Description</th>
           @if(isset($billing_mode) and count($billing_mode) > 0)
                @foreach($billing_mode as $b)
                    <th class="tittle-th">{{$b->fldsetname}}</th>
                @endforeach
            @endif
            
            
        </tr>
        </thead>
        <tbody>
            @if(!is_null($dietgroup) and count($dietgroup) > 0)
                @foreach($dietgroup as $k=>$dg)
                    @php
                    $sn = $k+1;
                    @endphp
                    <tr>
                        <td>{{$sn}}</td>
                        <td>{{$dg->fldcategory}}</td>
                        
                            @if(isset($billing_mode) and count($billing_mode) > 0)
                                @foreach($billing_mode as $b)
                                    <td>{{Helpers::getTotalDiet($b->fldsetname, $dg->fldcategory, $from, $to)}}</td>
                                @endforeach
                            @endif
                        
                    </tr>
                @endforeach
            @endif
            
            <tr>
                <td>&nbsp;</td>
                <td>Total</td>
                
                @if(isset($billing_mode) and count($billing_mode) > 0)
                    @foreach($billing_mode as $b)
                        <td>{{Helpers::getSumTotalDiet($b->fldsetname, $from, $to)}}</td>
                    @endforeach
                @endif
            </tr>

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('dietitian-report'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
