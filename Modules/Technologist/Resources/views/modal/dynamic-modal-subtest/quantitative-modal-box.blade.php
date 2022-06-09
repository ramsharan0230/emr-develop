<style>
    .quantitative-box-list-holder-area {
        height: 240px;
        width: 100%;
        background-color: #fff;
        overflow: auto;
    }
</style>
<div class="modal fade" id="quantitative-modal-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Quantitative Test Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label">
                                    <label class="get_test_name_here"></label>
                                </div>
                            </div>

                            <input type="hidden" id="get_previous_test_fldid" value="">

                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Method</label>
                                </div>
                                <div class="box__input">
                                    <select class="select-input-clinical-exam" name="selected_quantitative_method" id="get_quantitative_method">
                                    </select>
                                </div>
                                <div class="box__icon">
                                    <a href="javascript:;" data-toggle="modal" data-target="#technologist_quantitative_method_variable"><i class="fa fa-plus"></i></a>
                                </div>
                                @include('technologist::modal.technologist_quantitative_method_variable')
                            </div>
                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label>Valide Range</label>
                                </div>
                                <div class="box__input">
                                    <input type="number" id="quantitative_valide_range">
                                </div>
                            </div>
                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">To</label>
                                </div>
                            </div>
                            <div class="group__box half_box">
                                <div class="box__input">
                                    <input type="number" id="quantitative_matric_unit">
                                </div>
                                <div class="box__label">
                                    <label>Metric Unit</label>
                                </div>
                            </div>

                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Sensitivity</label>
                                </div>
                                <div class="box__input">
                                    <input type="number" class="input-clinicalexam" id="quantitative_sensitivity">
                                </div>
                            </div>

                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Specificity</label>
                                </div>
                                <div class="box__input">
                                    <input type="number" class="input-clinicalexam" id="quantitative_specificity">
                                </div>
                            </div>

                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Age Group</label>
                                </div>
                                <div class="box__input">
                                    <select class="select-input-clinical-exam" name="quantitative_age_group" id="quantitative_age_group">
                                        <option>---Select Age Group---</option>
                                        <option value="Neonate">Neonate</option>
                                        <option value="Infant">Infant</option>
                                        <option value="Toddler">Toddler</option>
                                        <option value="Children">Children</option>
                                        <option value="Adolescent">Adolescent</option>
                                        <option value="Adult">Adult</option>
                                        <option value="Elderly">Elderly</option>
                                        <option value="All Age">All Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Gender</label>
                                </div>
                                <div class="box__input">
                                    <select class="select-input-clinical-exam" name="quantitative_gender" id="quantitative_gender">
                                        <option>---Select Gender---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Both Sex">Both Sex</option>
                                    </select>
                                </div>
                            </div>
                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small">Conv Factor</label>
                                </div>
                                <div class="box__input">
                                    <input type="number" class="input-clinicalexam" id="quantitative_factor" value="0">
                                </div>
                            </div>

                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small" for="quantitative_si_unit">SI Unit</label>
                                </div>
                                <div class="box__input">
                                    <input type="radio" name="quantitative_units" class="input-clinicalexam" value="si">
                                </div>
                            </div>
                            <div class="group__box half_box">
                                <div class="box__label">
                                    <label class="clinicalexam-label-small" for="quantitative_metric">Metric</label>
                                </div>
                                <div class="box__input">
                                    <input type="radio" name="quantitative_units" class="input-clinicalexam" value="mu">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="clinicalexam-label-small"></label>
                                    <div class="box__label">
                                        <label class="clinicalexam-label-small">Lower Limit</label>
                                    </div>
                                    <div class="box__label">
                                        <label class="clinicalexam-label-small">Upper Limit</label>
                                    </div>
                                    <div class="box__label">
                                        <label class="clinicalexam-label-small">Normal Value</label>
                                    </div>
                                    <div class="box__label">
                                        <label class="clinicalexam-label-small">Unit</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    Metric Unit
                                    <input type="number" class="input-clinicalexam" id="quantitative_lower_mu" value="0">
                                    <input type="number" class="input-clinicalexam" id="quantitative_upper_mu" value="0">
                                    <input type="number" class="input-clinicalexam" id="quantitative_normal_mu" value="0">
                                    <input type="text" class="input-clinicalexam" id="quantitative_unit_mu">
                                </div>
                                <div class="col-md-4">
                                    Si Unit
                                    <input type="number" class="input-clinicalexam" id="quantitative_lower_si" value="0">
                                    <input type="number" class="input-clinicalexam" id="quantitative_upper_si" value="0">
                                    <input type="number" class="input-clinicalexam" id="quantitative_normal_si" value="0">
                                    <input type="text" class="input-clinicalexam" id="quantitative_unit_si">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="group__box half_box">
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-primary insert-quantitative-test-para" url="{{ route('insert.quantitative.test.para') }}"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-success edit-quantitative-test-para" url="{{ route('update.quantitative.test.para') }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-danger delete-quantitative-test-para" url="{{ route('delete.quantitative.test.para') }}"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                                        </div>&nbsp;
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive mt-3 table-scroll">
                                        <table class="table table-bordered cg_table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Gender</th>
                                                <th>AgeGroup</th>
                                                <th>Mean</th>
                                                <th>Lower</th>
                                                <th>Upper</th>
                                                <th>Unit</th>
                                                <th>Method</th>
                                                <th>Sens</th>
                                                <th>Spec</th>
                                            </tr>
                                            </thead>
                                            <tbody class="display-quantitative-test-para">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('technologist::modal.dynamic-modal.common-get-function')
