@if($foods)
    <table class="table">
        <tbody>

        @foreach($foods as $key => $dates)
            <tr>
                <td colspan="21" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>{{ $data->flditem }}</td>
                    <td>{{ $data->fldreportquanti }}</td>
                    <td>{{ $data->fldfluid }}</td>
                    <td>{{ $data->fldenergy }}</td>
                    <td>{{ $data->fldprotein }}</td>
                    <td>{{ $data->fldsugar }}</td>
                    <td>{{ $data->fldlipid }}</td>
                    <td>{{ $data->fldmineral }}</td>
                    <td>{{ $data->fldfibre }}</td>
                    <td>{{ $data->fldcalcium }}</td>
                    <td>{{ $data->fldphosphorous }}</td>
                    <td>{{ $data->fldiron }}</td>
                    <td>{{ $data->fldcarotene }}</td>
                    <td>{{ $data->fldthiamine }}</td>
                    <td>{{ $data->fldriboflavin }}</td>
                    <td>{{ $data->fldniacin }}</td>
                    <td>{{ $data->fldpyridoxine }}</td>
                    <td>{{ $data->fldfreefolic }}</td>
                    <td>{{ $data->fldtotalfolic }}</td>
                    <td>{{ $data->fldvitaminc }}</td>
                </tr>
            @endforeach
        @endforeach

        </tbody>
    </table>
@endif
