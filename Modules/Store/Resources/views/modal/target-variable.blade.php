<div class="modal fade" id="target-variable">
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
                    <input type="text" id="target_variable_name" class="form-input-big" style="width: 100%;">
            	</div>
                <a href="javascript:;" class="btn btn-default btn-sm" id="insert_target_variable" url="{{ route('insert.target.variable') }}"><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</a>
                <a href="javascript:;" class="btn btn-default btn-sm" id="delete_target_variable" url="{{ route('delete.target.variable') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</a>
                <div class="variables-box-target">
                    <ul class="variables-box-list target-box-list">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>