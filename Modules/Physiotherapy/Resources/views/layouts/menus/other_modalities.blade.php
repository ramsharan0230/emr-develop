@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';

    $oldothermodalities = \App\Utils\Physiotherapyhelpers::getExamgeneral($encounter_id, 'Other Modalities');

@endphp
<div id="other_modalities" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="plan-div">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Other Modalities:</h4>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <textarea name="other_modalities_textarea" id="other_modalities_textarea" class="form-control">{!! (isset($oldothermodalities) && $oldothermodalities != NULL) ? $oldothermodalities->flddetail : '' !!}</textarea>
                        </div><br>

                        <div class="form-group  mt-3">
                     
                                <button type="add" id="js-other-modalities-add-btn"  class="btn btn-primary float-right btn-action mr-3 {{ $disableClass }}" type="button" url="{{ route('physiotherapy.TherapeuticExcercises.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>