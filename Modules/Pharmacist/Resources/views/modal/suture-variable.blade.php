<div class="modal fade" id="suture-variable">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Variables</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-type-label">suture</label>
                </div>
            	<div class="form-group">
                    <input type="text" id="surgical_name_variable" class="form-input-big" style="width: 100%;">
            	</div>
                <a href="javascript:;" class="btn btn-default btn-sm" id="insert_surgical_name_variable" url="{{ route('insert.surgical.name.variable') }}"><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</a>
                <a href="javascript:;" class="btn btn-default btn-sm" id="delete_surgical_name_variable" url="{{ route('delete.surgical.name.variable') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</a>
                <div class="variables-box-surgical">
                    <ul class="variables-box-list surgical-name-list">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>