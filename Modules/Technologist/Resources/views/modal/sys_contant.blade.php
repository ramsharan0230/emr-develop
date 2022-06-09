<div class="modal fade" id="sys_contant_technologist">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Variables</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group form-row">
                    <label class="col-3 ">Sys Contant:</label>
                    <div class="col-sm-8">
                        <input type="text" id="technologist_constant" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-action btn-primary" id="reload_constant_list"><i class="fa fa-sync"></i></button>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-sm-12 text-right">
                        <a href="javascript:;" class="btn btn-action btn-primary" id="technologist_constant_insert" url="{{ route('insert.constant.technologist') }}"><i class="fa fa-plus"></i>&nbsp;Add</a>&nbsp;
                        <a href="javascript:;" class="btn btn-action btn-danger" id="technologist_constant_delete" url="{{ route('delete.constant.technologist') }}"><i class="fas fa-times"></i>&nbsp;Delete</a>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-sm-12">
                        <div class="variables-box-technologist">
                            <ul class="list-group res-table variables-box-list constant-box-list">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
