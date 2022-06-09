<div class="modal fade" id="technologist_quantitative_method_variable">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Variables</h4>
                <button type="button" class="close hide_this_modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            	<div class="form-group form-row">
                    <label class="col-sm-1">Test</label>
                    <div class="col-sm-5">
                        <input type="text" id="technologist_method_var" class="form-control">
                        </div>
                    <div class="col-sm-6">
                          <a href="javascript:;" class="btn btn-primary btn-action" id="technologist_method_insert" url="{{ route('insert.method.technologist') }}"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</a>&nbsp;
                          <a href="javascript:;" class="btn btn-danger btn-action" id="technologist_method_delete" url="{{ route('delete.method.technologist') }}"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</a>
                    </div>
                </div>
                <div class="variables-box-technologist">
                    <ul class="list-group res-table variables-box-list method-box-list">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>