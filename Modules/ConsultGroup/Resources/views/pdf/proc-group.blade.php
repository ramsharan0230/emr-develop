<!DOCTYPE html>
<html>

<head>
    <title>Consultation Plan Report</title>
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
    <li>Extra Procedures</li>
    <li>{{date('Y-m-d H:i')}}</li>
</ul>
<table style="width: 100%;" border="1px" class="content-body">
    <thead>
        <tr>
        <th>SNo</th>
        <th>Group Name</th>
        <th>Procedures</th>


        </tr>
    </thead>
    <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $k=>$r)

                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$r->fldgroupname}}</td>
                        <td>{{$r->fldprocname}}</td>


                    </tr>


            @endforeach


        @endif


    </tbody>
</table>
@php
        $signatures = Helpers::getSignature('procedure-grouping'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
