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
        <li>QUOTA ALLOCATION</li>
        <li>{{date('Y-m-d')}}</li>
    </ul>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>Method</th>
            <th>Timing</th>
            <th>Date</th>
            <th>Mode</th>
            <th>Department</th>
            <th>Username</th>
            <th>Limit</th>
            <th>Reason</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $k=>$r)

                <tr>
                    <td>{{$k+1}}</td>
                    <td>{{$r->fldmethod}}</td>
                    <td>{{$r->fldselect}}</td>
                    <td>{{$r->flddate}}</td>

                    <td>{{$r->fldbillingmode}}</td>
                    <td>{{$r->flddept}}</td>
                    <td>{{$r->flduserid}}</td>
                    <td>{{$r->fldquota}}</td>
                    <td>{{$r->fldreason}}</td>
                </tr>


            @endforeach


        @endif


        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('departmental-consult'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')

</main>
</body>

</html>
