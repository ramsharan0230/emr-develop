<div class="modal fade" id="category_technologist">
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
                    <label class="col-2 ">Category:</label>
                    <div class="col-sm-9">
                        <input type="text" id="technologist_category" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-action btn-primary" id="reload_categories_list"><i class="fa fa-sync"></i></button>
                    </div>
                </div>
                <div class="form-group form-row ">
                    <div class="col-sm-12 text-right">
                        <a href="javascript:;" class="btn btn-action btn-success" id="technologist_save_arrangements" style="display: none;"><i class="fa fa-save"></i> &nbsp;Save arrangements</a>&nbsp;
                        <a href="javascript:;" class="btn btn-action btn-primary" id="technologist_category_insert" url="{{ route('insert.category.technologist') }}"><i class="fa fa-plus"></i> &nbsp;Add</a>&nbsp;
                        <a href="javascript:;" class="btn btn-action btn-danger" id="technologist_category_delete" url="{{ route('delete.Category.technologist') }}"><i class="fas fa-times"></i>&nbsp;Delete</a>
                    </div>
                </div>
                <div class="form-group form-row ">
                    <div class="variables-box-technologist col-sm-12">
                        <ul class="list-group res-table variables-box-list category-box-list">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
