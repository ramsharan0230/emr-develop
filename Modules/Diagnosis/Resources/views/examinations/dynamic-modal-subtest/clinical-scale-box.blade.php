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
                            <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Test</label>
                                 </div>
                                 <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                     <label id="clinical-scale-test"></label>
                                 </div>
                             </div>
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Sub Test</label>
                                 </div>
                                 <div style="flex: 0 0 75%;height:28px;border:1px solid #9999">
                                     <label id="clinical-scale-sub-test"></label>
                                 </div>
                             </div>
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Group</label>
                                 </div>
                                 <div class="box__input">
                                    <select name="selected_clinicial_scale_group" id="selected_clinicial_scale_group" class="scale_group"></select>
                                 </div>
                             </div>
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Parameter</label>
                                 </div>
                                 <div class="box__input">
                                     <input type="text" id="clinical-scale-parameter">
                                 </div>
                             </div>
                             <div class="group__box half_box clinical__exam__top">
                                 <div class="box__label" style="flex: 0 0 25%;">
                                     <label class="clinicalexam-label-small">Value</label>
                                 </div>
                                 <div class="box__input">
                                     <input type="number" id="clinical-scale-value">
                                 </div>
                             </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical insert-clinical-scale" url="{{ route('examination.insert.clinical.scale') }}"><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</button>
                                </div>&nbsp;
                                <div class="box__btn__clinical">
                                    <button type="button" class="f-btn f-btn-sm f-btn-icon-r btn-clinical delete-clinical-scale" url="{{ route('examination.delete.clinical.scale') }}"><img src="{{asset('assets/images/cancel.png')}}" width="16px">&nbsp;&nbsp;Delete</button>
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
@include('diagnosis::examinations.dynamic-modal-subtest.common-get-function')
