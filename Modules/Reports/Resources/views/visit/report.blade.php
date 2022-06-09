<!DOCTYPE html>
<html>

<head>
    <title>IP status report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th,.table td {
            border: 1px solid black;
        }
        .table thead {
            background-color: #cccccc;
        }
    </style>
</head>

<body>
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;"><img src="{{ asset('images/cogent-logo.png') }}" alt="" width="100" height="100"/></td>
            <td style="width: 300px;">
                <h2 style="text-align: center;">Data Repository</h2>
                <h3 style="text-align: center;">MnS Tower Pulchowk Road Patan</h3>
                <h3 style="text-align: center;">General OPD</h3>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>

    <ul>
        <li>IP Status</li>
        <li>{{ $date_range }}</li>
    </ul>
    <table class="table">
        <tr>
            <th>&nbsp;</th>
            <th>Index</th>
            <th>EncId</th>
            <th>DateTIme</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>DOA</th>
            <th>LastLocation</th>
            <th>LastStatus</th>
            <th>Consult</th>
        </tr>
        @foreach($all_data as $key => $data)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $key+1 }}</td>
            <td>{{ $data->fldencounterval }}</td>
            <td>{{ $data->fldtime }}</td>
            <td>{{ Options::get('system_patient_rank')  == 1 && (isset($data)) && (isset($data->fldrank) ) ?$data->fldrank:''}} {{ $data->fldptnamefir }} {{ $data->fldmidname }} {{ $data->fldptnamelast }}</td>
            <td>{{ $data->age }}</td>
            <td>{{ $data->fldptsex }}</td>
            <td>{{ $data->fldregdate }}</td>
            <td>{{ $data->fldbed }}</td>
            <td>{{ $data->fldadmission }}</td>
            <td>&nbsp;</td>
        </tr>
        @endforeach
    </table>

</body>

</html>
