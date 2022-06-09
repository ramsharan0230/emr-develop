<!DOCTYPE html>
<html>

<head>
    <title>Cashier Group Packages</title>
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
        .content-body td, .content-body th{
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
    <li>Service Groups</li>
    <li>{{date('Y-m-d H:i')}}</li>
</ul>
<table style="width: 100%;" border="1px" class="content-body">
    <thead>
        <tr>
        <th>Category</th>
        <th>Particulars</th>
       
        <th>QTY</th>

        </tr>
    </thead>
    <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $data)
            <tr><td align="center" colspan="3" ><b>{{$data->fldgroup}}</b></td></tr>
                @php
                    $result = \App\ServiceGroup::where('fldgroup',$data->fldgroup)->get();
                @endphp
                @if(isset($result) and count($result) > 0)
                    @foreach($result as $r)
                    <tr>
                        <td align="center">{{$r->flditemtype}}</td>
                        <td align="center">{{$r->flditemname}}</td>
                        <td align="center">{{$r->flditemqty}}</td>
                    </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
    </tbody>
</table>
@php
        $signatures = Helpers::getSignature('cashier-package'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
