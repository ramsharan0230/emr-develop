<div class="col-sm-2">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Refer In</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="form-group">
                <label for=""></label>
                <div class="append-icon">
                    <select id="fldlocation_emergency" class="form-control">
                        @if(isset($refeere_location))
                            <option>--Select--</option>
                            @foreach($refeere_location as $referre)
                                <option value="{{ $referre->fldlocation }}" @if(isset($enpatient) && $enpatient->fldreferfrom == $referre->fldlocation) selected="selected" @endif>{{ $referre->fldlocation }}</option>
                            @endforeach
                        @endif
                    </select>&nbsp;
                    <a href="javascript:;" id="save_referre_location_emergency" url="{{ route('insert.referre.location.emergency') }}" class="btn btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
