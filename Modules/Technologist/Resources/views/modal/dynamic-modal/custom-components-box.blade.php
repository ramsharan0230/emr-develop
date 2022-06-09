<div class="modal fade" id="custom-components-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Qualitative Test Option</h4>
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
                                    <input type="text" id="common-modal-first-sub-test" class="form-control">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Option Type</label>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" id="custom_option_type">
                                        <option value="">--Select--</option>
                                        <option value="Single Selection">Single Selection</option>
                                        <option value="Dichotomous">Dichotomous</option>
                                        <option value="Clinical Scale">Clinical Scale</option>
                                        <option value="Text Addition">Text Addition</option>
                                        <option value="Text Reference">Text Reference</option>
                                        <option value="Visual Input">Visual Input</option>
                                        <option value="Left and Right">Left and Right</option>
                                        <option value="Date Time">Date Time</option>
                                        <option value="Multiple Selection">Multiple Selection</option>
                                        <option value="Drug Sensitivity">Drug Sensitivity</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 ">
                                    <a href="javascript:;" data-backdrop="static" data-keyboard="false" class="btn btn-primary dynamic-option-btn-subone" data-toggle="modal">
                                        <i class="ri-edit-2-fill"></i> Option
                                    </a>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Reference</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" id="common-modal-reference" class="form-control">
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Procedure</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="common-modal-procedure">
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
                                    <button type="button" class="btn btn-primary insert-sub-test-option" url="{{ route('insert.subtest.option') }}"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>&nbsp;
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger delete-test-option" url="{{ route('delete.subtest.option') }}"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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
