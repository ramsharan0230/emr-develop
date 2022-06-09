<div class="modal fade" id="test_name_update">
  <div class="modal-dialog ">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
       <div class="form-group form-row">
        <label class="col-2">TestName</label>

        <div class="col-md-10">
          <input type="text" id="test_name_new_value" class="form-control">
        </div>
      </div>
    </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-action btn-secondary" data-dismiss="modal">Close</button>
        <a href="javascript:;" class="btn btn-action btn-primary float-right btn-sm" id="update_technologist_test_name" url="{{ route('technologist.testName.update') }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
    </div>
  </div>
</div>
</div>