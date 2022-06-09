<div class="modal fade" id="text-addition-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Qualitative Test Option na na na lll</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Test</label>
                                 </div>
                                 <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                     <label class="common-modal-first-test"></label>
                                 </div>
                             </div> 
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Sub Test</label>
                                 </div>
                                 <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                     <label class="common-modal-first-sub-test"></label>
                                 </div>
                             </div>
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Option Type</label>
                                 </div>
                                 <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                     <label class="common-modal-first-type"></label>
                                 </div>
                             </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <textarea name="text_addition" class="display-text-addition" id="text_addition"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical insert-test-option" url="{{ route('insert.test.option') }}"><img src="{{asset('assets/images/edit.png')}}" width="16px">&nbsp;&nbsp;Update</button>
                                </div>&nbsp;
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical insert-test-option" url="{{ route('insert.test.option') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</button>
                                </div>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('technologist::modal.dynamic-modal.common-get-function')
