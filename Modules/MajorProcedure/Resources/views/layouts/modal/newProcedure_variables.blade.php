<div class="modal fade" id="newProcedure_variables">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="majorprocedure__modal_title">Variables</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 er-input">
                            <input type="text" class="form-control col-6" id="procedure_flditem">

                            <div class="col-md-6">
                                <a href="javascript:;" id="insert_variables" url="{{ route('insert.newProcedure.variables') }}" class="btn btn-primary btn-sm">Add</a>
                                <a href="javascript:;" id="delete_variables" url="{{ route('delete.newProcedure.variables') }}" class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="modalproctable">
                                <div class="listed-variables">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            {{--<div class="custom-file">
                                <input type="file" class="custom-file-input" id="validatedCustomFile" required="">
                                <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                <div class="invalid-feedback">Example invalid custom file feedback</div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
