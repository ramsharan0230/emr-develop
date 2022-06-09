<div class="modal fade" id="specimen_technologist">
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
                    <label class="col-2 ">Specimen:</label>
                    <div class="col-sm-9">
                        <input type="text" id="technologist_specimen"  class="form-control"> 
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-action btn-primary" id="reload_specimen_list"><i class="fa fa-sync"></i></button>
                  </div>
              </div>
                <div class="form-group form-row">
                   <div class="col-sm-12 text-right">
                        <a href="javascript:;" class="btn-primary btn btn-action" id="technologist_specimen_insert" url="{{ route('insert.specimen.technologist') }}"><i class="fa fa-plus"></i>&nbsp;Add</a>&nbsp;
                        <a href="javascript:;" class="btn-danger btn btn-action" id="technologist_specimen_delete" url="{{ route('delete.specimen.technologist') }}"><i class="fas fa-times"></i>&nbsp;Delete</a>
                   </div>
                </div>
                <div class="form-group form-row">
                   <div class="col-sm-12">
                        <div class="variables-box-technologist">
                            <ul class="list-group res-table variables-box-list specimen-box-list">
                            </ul>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>