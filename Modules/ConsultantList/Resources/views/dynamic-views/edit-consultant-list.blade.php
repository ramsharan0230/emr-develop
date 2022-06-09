<form action="javascript:;" id="edit-consult-form">
    <div class="modal-body">
        @php
            $consultantList = Helpers::getConsultantList();
        @endphp
        <div class="form-group row">
            <label for="" class="col-3">Billing Mode</label>
            <?php //dd($consultData); ?>
            <input type="hidden" name="fldid" value="{{ $consultData->fldid }}">
            <select class="form-control col-8" name="billing_mode" id="billing_mode">
                <option value="">--Select--</option>
                @if(count($modes))
                    @foreach($modes as $mode)
                        <option value="{{ $mode->fldsetname }}" {{ $consultData->fldbillingmode == $mode->fldsetname? "selected":'' }}>{{ $mode->fldsetname }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Department</label>
            <select name="department" id="department" class="form-control col-8">
                <option value="">--Select--</option>
                @if(count($departmentConsult))
                    @forelse($departmentConsult as $dept)
                        <option value="{{ $dept->flddept }}" {{ $consultData->fldconsultname == $dept->flddept? "selected":'' }}>{{ $dept->flddept }}</option>
                    @empty
                    @endforelse
                @endif
            </select>
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Date</label>
            @php
                $timestamp = (strtotime($consultData->fldconsulttime));
            @endphp
            <input type="text" name="consult_date_add" class="form-control col-8" id="date_nepali_consult_edit" placeholder="Consult Date">
            <input type="hidden" name="date_eng_edit" id="date_eng_edit" value="{{ date('Y-m-d', $timestamp) }}">
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Time</label>
            <input type="text" name="consult_time_edit" class="form-control col-8" id="consult_timepicker" placeholder="Consult Time" value="{{ date('H:i', $timestamp) }}">
        </div>
        <div class="form-group row">
            <label class="col-3">Consultant</label>

            <select name="consultant_edit" id="consultant_edit" class="form-control col-8">
                <option value="">--Select--</option>
                @if(count($consultantList))
                    @foreach($consultantList as $con)
                        <option value="{{ $con->username }}" {{ $consultData->flduserid == $con->username? "selected":'' }}>{{ $con->firstname .' '.$con->lastname }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Comment</label>
            <textarea name="comment" class="form-control col-8" id="comment_edit" rows="2" placeholder="Comment">{{ $consultData->fldcomment }}</textarea>
        </div>
        <div class="form-group row">
            <label class="col-3">Status:</label>
            <div class="col-sm-8">
                <select class="form-control" name="status">
                    <option value="Planned" {{ $consultData->fldstatus == 'Planned'? "selected":'' }}>Planned</option>
                    <option value="CheckIn" {{ $consultData->fldstatus == 'CheckIn'? "selected":'' }}>CheckIn</option>
                    <option value="Cancelled" {{ $consultData->fldstatus == 'Cancelled'? "selected":'' }}>Cancelled</option>
                    <option value="Done" {{ $consultData->fldstatus == 'Done'? "selected":'' }}>Done</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="consultantList.consultantListUpdate()">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
    $('#date_nepali_consult_edit').val(AD2BS('{{ date('Y-m-d', $timestamp) }}'));
    $('#date_nepali_consult_edit').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 10 // Options | Number of years to show
    });

    $('input#consult_timepicker').timepicker({});

</script>
