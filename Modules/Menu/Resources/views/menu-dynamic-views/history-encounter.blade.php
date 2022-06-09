<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Encounter</th>
            <th>DORec</th>
            <th>Status</th>
            <th>LastLocat</th>
            <th>Consultant</th>
            <th></th>
        </tr>
    </thead>

    @if($encounterData)
    @foreach($encounterData as $con)

    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $con->fldencounterval }}</td>
        <td>{{ $con->fldregdate }}</td>
        <td>{{ $con->fldadmission }}</td>
        <td>{{ $con->fldcurrlocat }}</td>
        <td>{{ $con->flduserid }}</td>
        <td><a href="javascript:;" class="btn btn-primary"><i class="fa fa-check"></i></a></td>
    </tr>
    @endforeach
    @endif
</table>