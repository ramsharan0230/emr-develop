<div class="modal fade" id="addGroup" tabindex="-1" role="dialog" aria-labelledby="addGroupLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <form action="{{ route('billing.show.add.group') }}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGroupLabel">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @csrf
                    <input type="hidden" name="__billing_mode" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif">
                    <input type="hidden" name="__encounter_id" id="__encounter_id" value="{{ isset($enpatient)?$enpatient->fldencounterval:'' }}">
                    <input type="hidden" name="__patient_id" id="__patient_id" value="{{ isset($enpatient)?$enpatient->fldpatientval:'' }}">
                    <input type="hidden" name="discountMode" id="discountMode" value="{{ isset($enpatient)?$enpatient->flddisctype:'' }}">

                    @if($addGroup)
                        @foreach($addGroup as $group)
                            <label>
                                <input type="checkbox" name="groupTest" id="add-group-{{ $group->fldid }}" value="{{ $group->fldgroup }}"> {{ $group->fldgroup }}</label>
                            <br>
                        @endforeach
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-action">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
