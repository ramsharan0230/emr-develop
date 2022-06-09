<div class="modal fade" id="anaesthesia_variables">
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
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="anaesthesia_flditem" style="margin-bottom: 9px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="javascript:;" id="insert_anasethesia_variables" url="{{ route('insert.anaeshtesia.variables') }}" class="btn btn-primary btn-sm">Add</a>
                                    <a href="javascript:;" id="delete_anasethesia_variables" url="{{ route('delete.anaeshtesia.variables') }}" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="anasethesia-variables">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
