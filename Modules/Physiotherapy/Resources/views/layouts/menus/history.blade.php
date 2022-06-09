@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';

    $oldhistory = \App\Utils\Physiotherapyhelpers::getExamgeneral($encounter_id, 'History');

@endphp
<div id="history" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="plan-div">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">History</h4>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <textarea name="history_textarea" id="history_textarea" class="form-control">{!! (isset($oldhistory) && $oldhistory != NULL) ? $oldhistory->flddetail : '' !!}</textarea>
                        </div><br>
                        <div class="form-group  mt-3">
                        
                                <button type="add" id="js-history-add-btn"  class="btn btn-primary float-right btn-action mr-3 {{ $disableClass }}" type="button" url="{{ route('physiotherapy.history.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                                {{--<button type="edit" id="js-plan-edit-btn"  class="btn btn-warning {{ $disableClass }}" type="button">--}}
                                {{--<i class="ri-edit-fill"></i>&nbsp;Edit--}}
                                {{--</button>--}}
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>