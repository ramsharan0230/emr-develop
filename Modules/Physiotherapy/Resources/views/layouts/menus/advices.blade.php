@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';

    $oldadvice = \App\Utils\Physiotherapyhelpers::getExamgeneral($encounter_id, 'Advices');

@endphp
<div id="advices" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="plan-div">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Advices:</h4>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <textarea name="advices_textarea" id="advices_textarea" class="form-control">{!! (isset($oldadvice) && $oldadvice != NULL) ? $oldadvice->flddetail : '' !!}</textarea>
                        </div><br>

                        <div class="form-group  mt-3">
                       
                                <button type="add" id="js-advices-add-btn "  class="btn btn-primary float-right btn-action mr-3 {{ $disableClass }}" type="button" url="{{ route('physiotherapy.Advices.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>