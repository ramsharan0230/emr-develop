@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';

    $specialtest = \App\Utils\Physiotherapyhelpers::getExamgeneral($encounter_id, 'Special Test');

@endphp
<div id="special_test" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="plan-div">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Special Test:</h4>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <textarea name="special_test_textarea" id="special_test_textarea" class="form-control">{!! (isset($specialtest) && $specialtest != NULL) ? $specialtest->flddetail : '' !!}</textarea>
                        </div><br>

                        <div class="form-group  mt-3">
                        
                                <button type="add" id="js-special-test-add-btn"  class="btn btn-primary float-right btn-action mr-3 {{ $disableClass }}" type="button" type="button" url="{{ route('physiotherapy.specialtest.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>