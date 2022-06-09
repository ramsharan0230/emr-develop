<div class="modal fade" id="item-name-variable">
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
            		<div class="form-inner">
                        <input type="text" id="items_name_variable" class="form-input-big">
                    </div>
            	</div>
                <a href="javascript:;" class="btn btn-default btn-sm" id="insert_item_name_variable" url="{{ route('insert.item.name.variable') }}"><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</a>
                <a href="javascript:;" class="btn btn-default btn-sm" id="delete_item_name_variable" url="{{ route('delete.item.name.variable') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</a>
                <div class="variables-box-extra-items">
                    <ul class="variables-box-list item-name-list">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>