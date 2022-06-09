<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
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
@include('pdf-header-footer.header')

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>Name: {{ $user_data->firstname .' '. $user_data->middlename.' '. $user_data->lastname}}</p>
            <p>Username: {{ $user_data->username }}</p>
            <p>Email: {{ $user_data->email }}</p>
        </td>
        <td>
            <div style="height: 200px; overflow: hidden;">
                <img src="data:image/jpg;base64,{{ $user_data->profile_image }}" style="width: 200px;">
            </div>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" class="content-body">
    <thead>
    <tr>
        <th class="tittle-th" style="width: 150px;">Group</th>
        <th class="tittle-th">Permission</th>
    </tr>

    </thead>
    <tbody>
    @php
        $permissionArray = [];
    @endphp
    @if($user_data->groups)
        @foreach($user_data->groups as $group)
            <tr>
                <td>{{ $group->name }} <br></td>
                <td>
                    @if($group->permission)
                        @foreach($group->permission as $permission)
                            {{ $permission->short_desc }} <br>
                        @endforeach
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

</body>
</html>
