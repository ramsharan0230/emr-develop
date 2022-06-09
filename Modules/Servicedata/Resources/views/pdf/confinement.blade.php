<!DOCTYPE html>
<html>

<head>
    <title>CONFINEMENT REPORT</title>
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
            <th class="tittle-th">DOReg</th>
            <th class="tittle-th">Mother</th>
            <th class="tittle-th">Address</th>
            <th class="tittle-th">Age</th>
            <th class="tittle-th">PatientNo</th>
            <th class="tittle-th">Guardian</th>
            <th class="tittle-th">DateTime</th>
            <th class="tittle-th">DelMode</th>
            <th class="tittle-th">Result</th>
            <th class="tittle-th">BloodLoss(ml)</th>
            <th class="tittle-th">Weight(g)</th>
            <th class="tittle-th">BabyNo</th>
            <th class="tittle-th">BabySex</th>
            <th class="tittle-th">Consultant</th>
            <th class="tittle-th">Nurse</th>
            <th class="tittle-th">Complication</th>
        </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
    <p>admin, {{date('Y-m-d')}}
    </p>
</main>

</body>

</html>
