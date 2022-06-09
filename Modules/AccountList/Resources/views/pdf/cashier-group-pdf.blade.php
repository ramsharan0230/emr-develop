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
    <li>Cashier Packages</li>
    <li>{{$group}}</li>
</ul>
<table style="width: 100%;" border="1px" class="content-body">
    <thead>
        <tr>
        <th>SNo</th>
        <th>Item Type</th>
        <th>Item Name</th>
        <th>QTY</th>

        </tr>
    </thead>
    <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $k=>$r)

                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$r->flditemtype}}</td>
                        <td>{{$r->flditemname}}</td>
                        <td>{{$r->flditemqty}}</td>

                    </tr>


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
