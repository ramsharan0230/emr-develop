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
    <li>Departmental Examination</li>
    <li>{{$department}}</li>
</ul>
<table style="width: 100%;" border="1px" class="content-body">
    <thead>
        <tr>
        <th>SNo</th>
        <th>Variable</th>
        <th>Examination</th>
        <th>SysConst</th>
        <th>Option</th>

        </tr>
    </thead>
    <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $k=>$r)

                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$r->fldtype}}</td>
                        <td>{{$r->fldexamid}}</td>
                        <td>{{$r->fldsysconst}}</td>

                        <td>{{$r->fldtanswertype}}</td>

                    </tr>


            @endforeach


        @endif


    </tbody>
</table>

@php
        $signatures = Helpers::getSignature('departmental-examination'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
