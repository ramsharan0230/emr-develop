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
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">Index</th>
            <th class="tittle-th">EncID</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">Age</th>
            <th class="tittle-th">Gender</th>
            <th class="tittle-th">DateTime</th>
            <th class="tittle-th">Procedure</th>
            <th class="tittle-th">Status</th>
            <th class="tittle-th">Summary</th>
        </tr>
        </thead>
        <tbody>
        {!! $view !!}
        </tbody>
    </table>
    <p>admin, {{date('Y-m-d')}}
    </p>
</main>

</body>

</html>
