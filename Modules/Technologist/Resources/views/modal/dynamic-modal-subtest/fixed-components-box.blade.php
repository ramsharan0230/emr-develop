<div class="modal fade" id="fixed-components-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="clinicalexam-label-small">Test</label>
                                </div>
                                <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                    <label id="common-modal-first-test"></label>
                                </div>
                            </div>
                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="clinicalexam-label-small">Sub Test</label>
                                </div>
                                <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                    <label id="common-modal-first-sub-test"></label>
                                </div>
                            </div>
                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="clinicalexam-label-small">Option</label>
                                </div>
                                <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                    <select name="sub-test-option" id="sub-test-option">

                                        <option value="Single Selection">Single Selection</option>
                                        <option value="Dichotomous">Dichotomous</option>
                                        <option value="Text Addition">Text Addition</option>
                                        <option value="Text Reference">Text Reference</option>
                                        <option value="Left and Right">Left and Right</option>
                                        <option value="Percent Sum">Percent Sum</option>
                                    </select>
                                </div>
                                <a href="javascript:;" class=" btn default-btn f-btn-icon-r dynamic-option-btn" data-toggle="modal" data-target="" style="text-decoration: none;"><img src="{{asset('assets/images/edit.png')}}" width="16px">&nbsp;&nbsp;Option</a>
                            </div>
                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="clinicalexam-label-small">Reference</label>
                                </div>
                                <div class="box__input">
                                    <label id="common-modal-reference-label"><input type="text" name="common-modal-reference" id="common-modal-reference" value=""></label>
                                </div>
                            </div>

                            <div class="group__box half_box clinical__exam__top">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="clinicalexam-label-small">Procedure</label>
                                </div>
                                <div class="box__input">
                                    <label id="common-modal-procedure-label"><input type="text" name="common-modal-procedure" id="common-modal-procedure" value=""></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical insert-test-option" url="{{ route('insert.subtest.option') }}"><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</button>
                                </div>&nbsp;
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical delete-test-option" url="{{ route('delete.subtest.option') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</button>
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@include('technologist::modal.dynamic-modal.common-get-function')
