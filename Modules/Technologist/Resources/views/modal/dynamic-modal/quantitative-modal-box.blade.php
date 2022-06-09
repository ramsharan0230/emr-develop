<style>
    .quantitative-box-list-holder-area{
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
                        <div class="col-md-12">
                            <div class="form-group form-row align-items-center">
                                <label class=""></label>
                            </div>

                            <input type="hidden" id="get_previous_test_fldid" value="">

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Method</label>
                                <div class="col-md-7">
                                    <select class="form-control" name="selected_quantitative_method" id="get_quantitative_method">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                   <a href="javascript:;" data-toggle="modal" class="btn btn-primary" data-target="#technologist_quantitative_method_variable"><i class="fa fa-plus"></i></a>
                                </div>
                                @include('technologist::modal.technologist_quantitative_method_variable')
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Valide Range</label>
                                <div class="col-md-3">
                                    <input type="number" id="quantitative_valide_range" class="form-control">
                                </div>
                                <label class="col-md-1">To</label>
                                <div class="col-md-3">
                                    <input type="number" id="quantitative_matric_unit" class="form-control">
                                </div>
                                <label class="col-md-2">Metric Unit</label>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Sensitivity</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_sensitivity">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Specificity</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_specificity">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Age Group</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="quantitative_age_group" id="quantitative_age_group">
                                        <option value="">---Select Age Group---</option>
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
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Gender</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="quantitative_gender" id="quantitative_gender">
                                        <option value="">---Select Gender---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Both Sex">Both Sex</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Conv Factor</label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" id="quantitative_factor" value="0">
                                </div>
                                 <div class="col-sm-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="quantitative_units" class="custom-control-input" value="si">
                                        <label class="custom-control-label" for="quantitative_si_unit">SI Unit</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="quantitative_units" class="custom-control-input" value="mu">
                                        <label class="custom-control-label" for="quantitative_metric">Metric</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="full-width">
                                        <thead>
                                            <tr>
                                                <td colspan="2" class="text-center">Metric Unit</td>
                                                <td>SI Unit</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="p-1">Lower Limit</td>
                                                <td class="p-1"> <input type="text" class="form-control" id="quantitative_lower_mu" value="0"></td>
                                                <td class="p-1"><input type="text" class="form-control" id="quantitative_lower_si" value="0"></td>
                                            </tr>
                                             <tr>
                                                <td class="p-1">Upper Limit</td>
                                                <td class="p-1"><input type="text" class="form-control" id="quantitative_upper_mu" value="0"></td>
                                                <td class="p-1"> <input type="text" class="form-control" id="quantitative_upper_si" value="0"></td>
                                            </tr>
                                             <tr>
                                                <td class="p-1">Normal Value</td>
                                                <td class="p-1"><input type="text" class="form-control" id="quantitative_normal_mu" value="0"></td>
                                                <td class="p-1"><input type="text" class="form-control" id="quantitative_normal_si" value="0"></td>
                                            </tr>
                                             <tr>
                                                <td class="p-1">Unit</td>
                                                <td class="p-1"><input type="text" class="form-control" id="quantitative_unit_mu"></td>
                                                <td class="p-1"> <input type="text" class="form-control" id="quantitative_unit_si"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               <!--  <div class="col-md-4">
                                    <label style="display: block;">&nbsp;</label>
                                    <label style="display: block;">Lower Limit</label>
                                    <label style="display: block;">Upper Limit</label>
                                    <label style="display: block;">Normal Value</label>
                                    <label style="display: block;">Unit</label>
                                </div>
                                <div class="col-md-4">
                                    <p>Metric Unit</p>
                                    <input type="number" class="form-control" id="quantitative_lower_mu" value="0">
                                    <input type="number" class="form-control" id="quantitative_upper_mu" value="0">
                                    <input type="number" class="form-control" id="quantitative_normal_mu" value="0">
                                    <input type="text" class="form-control" id="quantitative_unit_mu">
                                </div>
                                <div class="col-md-4">
                                    <p>Si Unit</p>
                                    <input type="number" class="form-control" id="quantitative_lower_si" value="0">
                                    <input type="number" class="form-control" id="quantitative_upper_si" value="0">
                                    <input type="number" class="form-control" id="quantitative_normal_si" value="0">
                                    <input type="text" class="form-control" id="quantitative_unit_si">
                                </div> -->
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-row float-right mt-2">
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-primary insert-quantitative-test-para" url="{{ route('insert.quantitative.test.para') }}"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-primary edit-quantitative-test-para" url="{{ route('update.quantitative.test.para') }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-danger delete-quantitative-test-para" url="{{ route('delete.quantitative.test.para') }}"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                                        </div>&nbsp;
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="res-table">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead class="thead-light">
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
                <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('technologist::modal.dynamic-modal.common-get-function')
