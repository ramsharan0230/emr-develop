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
    <li>Examination</li>
    <li>{{date('Y-m-d H:i')}}</li>
</ul>
<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>
        <th>SNo</th>
        <th>Group Name</th>
        <th>Examination</th>
    </tr>
    </thead>
    <tbody>
    {!! $html !!}
    </tbody>
</table>
    @php
        $signatures = Helpers::getSignature('examination'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>

</html>
