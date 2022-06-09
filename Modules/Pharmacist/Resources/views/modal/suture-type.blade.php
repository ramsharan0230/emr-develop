<div class="modal fade" id="suture-type">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Suture Codes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <label class="from-group-label">Type</label>
                        <input type="text" id="surgical_type_name" class="form-control">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label class="from-group-label">Code</label>
                        <input type="text" id="surgical_type_code" class="form-control">
                    </div>
                    <div class="col-md-12 col-sm-12 my-2 text-right">
                        <a href="javascript:void(0)" class="btn btn-primary " id="insert_surgical_name_type"  url="{{ route('insert.surgical.name.type') }}"><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</a>
                        <a href="javascript:void(0)" class="btn btn-primary " id="delete_surgical_name_type" url="{{ route('delete.surgical.name.type') }}"><i class="fa fa-times"></i> &nbsp;<u>D</u>elete</a>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="variables-box-surgical">
                            <div class="variables-box-list res-table">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Code</th>
                                        </tr>
                                    </thead>
                                    <tbody class="surgical-type-code-list"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
