<table class="table" id="table">
    <thead>
    <tr>
        <th>S.N</th>
        <th>Patient Id</th>
        <th>Encounter Id</th>
        <th>Name</th>
        <th>Sample Id</th>
        {{-- <th>fldptnamelast</th> --}}
        <th>Test Id</th>
        <th>Sample by</th>
        <th>Sample Time</th>
        <th>Reported By</th>
        <th>Reported Time</th>
        <th>Verified By</th>
        <th>Verified Time</th>
    </tr>
    </thead>
    <tbody>
        @if(!$records->isEmpty())
            <?php 
                $count = 1; 
            ?>
            @foreach ($records as $key => $list)
                <tr data-node="treetable-{{$list->fldpatientval}}">
                    <td>{{$count++}}</td>
                    <td>{{$list->fldpatientval??''}}</td>
                    <td>{{$list->fldencounterval??''}}</td>
                    <td>{{strtoupper($list->fldptnamefir)??''}} {{strtoupper($list->fldptnamelast)??''}}</td>
                    <td>{{$list->fldsampleid ?? ''}}</td>
                    <td>{{$list->fldtestid??''}}</td>
                    <td>{{$list->flduserid_sample??''}}</td>
                    <td>{{$list->fldtime_sample??''}}</td>
                    <td>{{$list->flduserid_report??''}}</td>
                    <td>{{$list->fldtime_report??''}}</td>
                    <td>{{$list->flduserid_verify??''}}</td>
                    <td>{{$list->fldtime_verify??''}}</td>
                </tr>
            @php
            $patient_wise_log=  \App\Utils\Helpers::patientTestLogReport($list->fldpatientval,$list->fldtestid);
            @endphp
                @if(!$patient_wise_log->isEmpty())
                    @foreach ($patient_wise_log as $pwg)
                        <tr  data-pnode="treetable-parent-{{$list->fldpatientval}}">
                            <td></td> 
                            <td>{{$pwg->fldpatientval??''}}</td>
                            <td>{{$pwg->fldencounterval??''}}</td>
                            <td>{{strtoupper($pwg->fldptnamefir)??''}} {{strtoupper($pwg->fldptnamelast)??''}}</td>
                            <td>{{$pwg->fldsampleid ?? ''}}</td>
                            <td>{{$pwg->fldtestid??''}}</td>
                            <td>{{$pwg->flduserid_sample??''}}</td>
                            <td>{{$pwg->fldtime_sample??''}}</td>
                            <td>{{$pwg->flduserid_report??''}}</td>
                            <td>{{$pwg->fldtime_report??''}}</td>
                            <td>{{$pwg->flduserid_verify??''}}</td>
                            <td>{{$pwg->fldtime_verify??''}}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
    </tbody>
</table>