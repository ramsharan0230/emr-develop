<!DOCTYPE html>
<html>

<head>
    <title>Datewise Report</title>
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
        <li>SUMMARY: {{$date_from}} TO {{$date_to}}</li>
        <li>TOTAL : {{$total}}</li>
    </ul>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>Variables</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($result) and count($result)>0)
            @foreach($result as $k=>$r)

                @php
                    $percentage = ($r->total/$total)*100;
                    $i = 1;
                    $m=0;
                @endphp
                @if($r->$field !='')
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$r->$field}}</td>
                        <td>{{$r->total}}</td>
                        <td>{{($percentage)}}</td>
                    </tr>
                @else
                    @php
                        $m = $m+1;
                    @endphp
                @endif

            @endforeach
            @php
                $i++;
                $missingper = ($m/$total)*100;
            @endphp
            <tr>
                <td></td>
                <td>****</td>
                <td>{{$total}}</td>
                <td>100</td>
            </tr>
            @if($m == 0)
                <tr>
                    <td></td>
                    <td>Missing</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            @else
                <tr>
                    <td></td>
                    <td>Missing</td>
                    <td>{{$m}}</td>
                    <td>missingper</td>
                </tr>
            @endif
        @endif


        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('consultation-datewise');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
