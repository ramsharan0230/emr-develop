<div class="modal fade" id="clinical-scale-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Clinicial Scale Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Test</label>
                                 </div>
                                 <div class="col-4">
                                     <label id="clinical-scale-test"></label>
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Sub Test</label>
                                 </div>
                                 <div class="col-4">
                                     <label id="clinical-scale-sub-test"></label>
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Group</label>
                                 </div>
                                 <div class="col-4">
                                    <div class="select-editable">
                                        <select onchange="this.nextElementSibling.value=this.value" id="selected_clinicial_scale_group_options" style="background: none;">
                                        </select>
                                        <input type="text" name="selected_clinicial_scale_group" id="selected_clinicial_scale_group" value=""/>
                                    </div>
                                    {{-- <select name="selected_clinicial_scale_group" id="selected_clinicial_scale_group" class="scale_group form-control">
                                        <option value="">--Select--</option>
                                    </select> --}}
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Parameter</label>
                                 </div>
                                 <div class="col-4">
                                     <input type="text" id="clinical-scale-parameter" class="form-control">
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Value</label>
                                 </div>
                                 <div class="col-4">
                                     <input type="number" id="clinical-scale-value" class="form-control">
                                 </div>
                             </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary insert-clinical-scale" url="{{ route('insert.clinical.scale') }}"><i class="fas fa-plus"></i> Add</button>
                                </div>&nbsp;
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger delete-clinical-scale" url="{{ route('delete.clinical.scale') }}"><i class="fas fa-trash"></i> Delete</button>
                                </div>&nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody id="display-clinical-scale-list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('technologist::modal.dynamic-modal.common-get-function')
