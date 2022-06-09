<div class="tab-pane fade @if(!\App\Utils\Permission::checkPermissionFrontendAdmin( 'signin-otchecklists' ) && !\App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ) && !\App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' )) show active @endif" id="newproc" role="tabpanel" aria-labelledby="newproc-tab">
    <div class="form-group-second form-row">
        <div class="col-sm-6 er-input">
            <input type="hidden" id="newProcedure_fldid" value="">
            <label for="" class="col-sm-3 col-lg-2">Proced:</label>
            <input type="text" class="form-control" value="" id="newProcedure_proced">
        </div>
        <div class="col-sm-6 er-input">
            <label for="" class="col-sm-5 col-lg-3">Refer:</label>
            <div class="col-md-5 col-lg-8 p-0">
                <select name="" class="form-control" id="newProcedure_refer">
                    <option value=""></option>
                    @if(isset($new_proc_refere))
                    @foreach($new_proc_refere as $refer)
                    <option value="{{ $refer->flduserid }}">{{ $refer->fullname ?? null }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 col-lg-1">
                <button class="btn btn-sm-in btn-primary" type="button">
                    <i class="fa fa-user"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="form-group-second form-row">
        <div class="col-sm-6 er-input">
            <label for="" class="col-sm-3 col-lg-2">Payable:</label>
            <div class="col-sm-7 col-lg-8 p-0">
                <select name="" class="form-control" id="newProcedure_payable">
                    <option value=""></option>
                    @if(isset($new_proc_payable))
                    @foreach($new_proc_payable as $payable)
                    <option value="{{ $payable->flduserid }}">{{ $payable->fullname ?? null }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 col-lg-1">
                <button class="btn btn-sm-in btn-primary" type="button">
                    <i class="fa fa-user"></i>
                </button>
            </div>
        </div>
        <div class="col-sm-6 er-input">
            <label for="" class="col-sm-5 col-lg-3">Date:</label>
            <input type="text" placeholder="YYYY-MM-DD" class="nepaliDatePicker form-control" id="newPorcedure_fldnewdate">
        </div>
    </div>
    <div class="form-group-second form-row">
        <div class="col-sm-6 er-input">
            <label for="" class="col-sm-3 col-lg-2">Status:</label>
            <div class="col-sm-5 col-lg-7 padding-none">
                <select class="form-control" id="fldreportquali-newprocedure">
                    <option value=""></option>
                    <option value="Planned">Planned</option>
                    <option value="Referred">Referred</option>
                    <option value="Canceled">Canceled</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Done">Done</option>
                </select>
            </div>
            <div class="col-sm-5 col-lg-3">
                <button type="button" class="btn btn-primary" id="update-newPorcedure"
                url="{{ route('update.newProcedure.newProcedure') }}"><i class="fa fa-edit"></i>&nbsp;Edit
            </button>
        </div>
    </div>
    <div class="col-sm-6 er-input">
        <label for="" class="col-sm-5 col-lg-3">Components:</label>
        <div class="col-sm-7 col-lg-9 padding-none">
            <a href="javascript:;" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#newProcedure_variables" onclick="getProcedureVariables()" type="button" title="Variables">
                <i class="fa fa-plus"></i>
            </a>&nbsp;&nbsp;

            <a href="javascript:;" data-toggle="modal" data-target="#newProcedure_freetext" class="btn btn-sm-in btn-primary" type="button" title="Procedure">
                <i class="fa fa-plus"></i>
            </a>

            <a href="javascript:;" data-toggle="modal" data-target="#newProcedure_procedure" class="btn btn-sm-in btn-primary" type="button" title="Select Procedure">
                <i class="fa fa-plus"></i>
            </a>

            <a href="javascript:;" data-toggle="modal" data-target="#newProcedureExcel" class="btn btn-sm-in btn-primary" type="button">
                <i class="fa fa-plus"></i>
            </a>

        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-sm-6">
        <div class=" major-table3 dietarytable">
            <table class="table table-hovered table-striped newprocedure-table-components">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="res-table">
            <table class="newprocedure-table2 list-clinical-indication table table-hovered table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>S/N</th>
                        <th>Target Data</th>
                        <th>Procedure</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="getRelatedDataNewProcedure">
                    @if(isset($proceduremajor))
                    @foreach($proceduremajor as $majorProced)
                    <tr rel="{{ $majorProced->fldid }}" rel1="{{ $majorProced->fldreportquali }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $majorProced->fldnewdate }}</td>
                        <td>{{ $majorProced->flditem }}</td>
                        <td>{{ $majorProced->fldreportquali }}</td>
                        <td><input type="checkbox"></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="iq-card-header d-flex justify-content-between mt-2">
            <h5 class="card-title">Summary</h5>
            <a href="javascript:;" id="insertFlddetailNewProcedure" url="{{ route('insert.flddetail.newProcedure') }}" class="btn btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></a>
        </div>
        <div class="form-group mb-0">
            <textarea class="form-control" name="newprocedure_detail" id="newprocedure_detail">{{ isset($proceduremajor[0]->flddetail)?$proceduremajor[0]->flddetail:"" }}</textarea>
        </div>
    </div>
</div>
</div>
<div id="confirm_delete_procedure_variables"></div>
