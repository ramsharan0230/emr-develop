<div class="modal fade" id="newProcedure_procedure">
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
                            <select class="form-control" id="newPorcedure_fldreportqualiSelect">
                                @if(isset($variables))
                                    @foreach($variables as $variable)
                                        <option value="{{ $variable->flditem }}">{{ $variable->flditem }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="insert_majorProcedure_select" url="{{ route('insert.newProcedure.freetext') }}">Save changes</button>
            </div>
        </div>
    </div>
</div>
