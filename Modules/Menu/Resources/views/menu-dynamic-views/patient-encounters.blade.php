<table class="table">
        <thead>
        <tr>
            <th>S.N</th>
            <th>Encounter</th>
            <th>DORec</th>
            <th>Status</th>
            <th>LastLocat</th>
            <th>Consultant</th>
            <th></th>
        </tr>
        </thead>
       
  
    <tbody >
@if(count($encounters))
    @foreach($encounters as $k => $con)
        <tr>
            <td>{{$k+1}}</td>
            <td>{{ $con->fldencounterval }}</td>
            <td>{{ $con->fldregdate }}</td>
            <td>{{ $con->fldadmission }}</td>
            <td>{{ $con->fldcurrlocat }}</td>
            <td>{{ $con->flduserid }}</td>
            <td><a href="javascript:;" ><img src="http://localhost/cogenthealth/public/assets/images/tick.png" alt=""></a></td>

        </tr>
    @endforeach
@endif
</tbody>
</table>
