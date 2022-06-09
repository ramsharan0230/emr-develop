<div class="border table-sticky-th" style="height: 678px; overflow: auto;">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-light">
        <tr>
            <th>EncID</th>
            <th>Bill No</th>
            <th>Name</th>
            <th>Department</th>
            <th>Userid</th>
            <th>Datetime</th>
        </tr>
        </thead>
        <tbody id="js-sampling-patient-tbody" class="js-lab-module-name-search-tbody">
        @if(isset($patients))
            @foreach($patients as $pat)
                <tr data-encounterid="{{ $pat->fldencounterval }}">
                    <td>{{ $pat->fldencounterval }}</td>
                    <td>{{ ($pat->fldbillno) ? $pat->fldbillno : $pat->fldtempbillno }}</td>
                    <td class="js-patient-name">@if($pat->encounter && $pat->encounter->patientInfo){{ $pat->encounter->patientInfo->fldrankfullname }}@endif</td>
                    <td>{{ ($pat->encounter && $pat->encounter->consultant) ? $pat->encounter->consultant->fldconsultname : '' }}</td>
                    <td>{{ $pat->fldorduserid }}</td>
                    @if(!request()->get('rejected'))
                    <td>{{ $pat->fldordtime }}</td>
                    @else
                    <td>{{ $pat->fldtime }}</td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div id="js-sampling-patient-pagination">
    @if(isset($patients))
        {{ $patients->links() }}
    @endif
</div>
