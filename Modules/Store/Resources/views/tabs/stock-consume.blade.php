<div class="tab-pane fade" id="stockconsume" role="tabpanel" aria-labelledby="stockconsume-tab">
    <div class="container">
        <div>
            <div class="profile-form">
                <div class="row top-req">
                    <div class="col-md-3">
                        <div class="group__box half_box">
                            <label>Target Comp</label>
                            <div class="box__icon">
                                <a href="javascript:;" data-toggle="modal" data-target="#target-variable" id="get_item_variables"><img src="{{asset('assets/images/plus.png')}}" width="18px;"></a>
                                @include('store::modal.target-variable')
                            </div>
                        </div>
                        <div class="group__box half_box">
                            <input type="checkbox" name="">&nbsp;&nbsp;
                            <div class="box__input" style="flex: 0 0 92%;">
                                <select id="get_categroy_stock_consume">
                                    <option value="anal/vaginal">anal/vaginal</option>
                                    <option value="extra">extra</option>
                                    <option value="eye/ear">eye/ear</option>
                                    <option value="fluid">fluid</option>
                                    <option value="injection">injection</option>
                                    <option value="liquid">liquid</option>
                                    <option value="msurg">msurg</option>
                                    <option value="oral">oral</option>
                                    <option value="ortho">ortho</option>
                                    <option value="resp">resp</option>
                                    <option value="suture">suture</option>
                                    <option value="topical">topical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="group__box half_box">
                            <div class="box__input" style="flex: 0 0 45%;">
                                <select id="get_target_variables" name="get_target_variables">
                                </select>
                            </div>
                            <div class="box__icon">
                                <a href="#"><i class="fas fa-sync"></i></a>
                            </div>
                            <div class="box__input" style="flex: 0 0 37%;">
                                <input type="date" class="f-input-date full-width">
                            </div>
                            <div class="box__icon">
                                <a href="#"><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                            </div>
                        </div>
                        <div class="group__box half_box">
                            <div class="box__input" style="flex: 0 0 70%;">
                                <input type="text" class="f-input-date full-width" id="med_stock_consume_value">
                            </div>
                            <div class="box__icon">
                                <a href="javascript:;" data-toggle="modal" data-target="#med_box_stock_consume" id="get_related_med_stock_consume"><img src="{{asset('assets/images/plus.png')}}" width="18px;"></a>
                                @include('store::modal.med_box_stock_consume')
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <select readonly="">
                                    <option value="" selected=selected></option>
                                    <option value="" selected=selected></option>
                                    <option value="" selected=selected></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group__box half_box" style="margin-left: 49%;">
                            <div class="radio-1">&nbsp;&nbsp;
                                <input type="radio" name="">
                                <label>Generic</label>&nbsp;&nbsp;

                                <input type="radio" name="">
                                <label>Brand</label>
                            </div>
                        </div>
                        <div class="group__box half_box">
                            <div class="box__input" style="flex: 0 0 15%;">
                                <input type="" name="" disabled="">
                            </div>
                            <div class="col-sm-5">
                                <input type="date" class="f-input-date full-width" disabled="">
                            </div>
                             <div class="box__input" style="flex: 0 0 20%;">
                                <input type="" name="" disabled="">
                            </div>
                            <div class="box__input" style="flex: 0 0 25%;">
                                <input type="" name="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-scroll-md table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="tittle-th"></th>
                                    <th class="tittle-th">Catogery</th>
                                    <th class="tittle-th">Particulars</th>
                                    <th class="tittle-th">Batch</th>
                                    <th class="tittle-th">Expiry</th>
                                    <th class="tittle-th">QTY</th>
                                    <th class="tittle-th">Cost</th>
                                    <th class="tittle-th">Vendor</th>
                                    <th class="tittle-th">Refn</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row top-req">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    {{-- next group --}}
                    <div class="group__box half_box">
                        <div class="radio-1">
                            <input type="checkbox" name="">&nbsp;&nbsp;
                            <label>Print Report</label>
                        </div>&nbsp;&nbsp;
                        <div class="box__label" style="flex: 0 0 21%;">
                        <button class="default-btn f-btn-icon-g full-width"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                        </div>&nbsp;
                         <div class="box__label" style="flex: 0 0 30%;">
                            <button class="default-btn f-btn-icon-r full-width"><i class="fas fa-code"></i>&nbsp;&nbsp;Export</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
