<div class="modal fade" id="newProcedure_freetext">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="majorprocedure__modal_title">Procedure</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="majorprocedure__modal_title">Enter Procedure Name</h5>
                            <input type="text" class="form-control" id="newPorcedure_fldreportquali">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="insert_majorProcedure_freetext" url="{{ route('insert.newProcedure.freetext') }}">Save changes</button>
            </div>
        </div>
    </div>
</div>
