<div class="modal fade" id="common-modal-box-one" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Options</h4>
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
                                     <label id="common-modal-first-test"></label>
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Sub Test</label>
                                 </div>
                                 <div class="col-4">
                                     <label id="common-modal-first-sub-test"></label>
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Option Type</label>
                                 </div>
                                 <div class="col-4">
                                     <label id="common-modal-first-type"></label>
                                 </div>
                             </div>
                             <div class="form-group form-row">
                                 <div class="col-3">
                                     <label class="clinicalexam-label-small">Option</label>
                                 </div>
                                 <div class="col-4">
                                     <input type="text" class="form-control" id="common-modal-first-option">
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
                                    <button type="button" class="btn btn-primary insert-test-option" url="{{ route('insert.test.option') }}"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>&nbsp;
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger delete-test-option" url="{{ route('delete.test.option') }}" ><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                </div>&nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody class="display-test-option-list">
                                        
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
