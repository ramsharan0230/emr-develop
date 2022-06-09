<div class="tab-pane fade show active" id="purchaseEntry" role="tabpanel" aria-labelledby="home-tab">
    <div class="container">
        <div class="profile-form">
            <div class="row mt-2">
                <div class="col-md-3">
                    <div class="group__box half_box">
                        <div class="col-sm-10">
                            <input type="date" class="f-input-date full-width">
                        </div>
                        <div class="box__icon">
                            <a href="#"><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <div class="box__input__purchase1">
                            <select>
                                <option value="Cash Payment">Cash Payment</option>
                                <option value="Credit Payment">Credit Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <input type="checkbox" name="">&nbsp;&nbsp;
                        <div class="box__input__purchase2">
                            <select readonly="">
                                <option value="" selected=selected>Fluid</option>
                                <option value="" selected=selected></option>
                                <option value="" selected=selected></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="group__box half_box">
                        <div class="radio-1">&nbsp;&nbsp;
                            <input type="checkbox" name="">
                            <label>Purchase Restriction</label>&nbsp;&nbsp;

                            <input type="checkbox" name="">
                            <label>Show All Entry</label>
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <div class="box__input__purchase3">
                            <input type="" name="">
                        </div>
                        <div class="box__input__purchase4">
                            <select>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <div class="box__input__purchase1">
                            <select readonly="">
                                <option value="" selected=selected></option>
                                <option value="" selected=selected></option>
                                <option value="" selected=selected></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="group__box half_box">
                        <div class="radio-1">&nbsp;&nbsp;
                            <input type="radio" name="">
                            <label>Generic</label>&nbsp;&nbsp;

                            <input type="radio" name="">
                            <label>Brand</label>
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <div class="box__input__purchase1">
                            <input type="" name="">
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <div class="box__input__purchase4">
                            <input type="" name="">
                        </div>
                        <div class="col-sm-6">
                            <input type="date" class="f-input-date full-width">
                        </div>
                        <div class="box__icon">
                            <a href="#"><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
             <div class="col-md-3">
                <div class="group__box half_box">
                    <label class="col-5 col-form-label col-form-label-sm">Total Cost:</label>
                    <div class="box__input__purchase5">
                        <input type="" name="">
                    </div>
                    <a href=""><img src="{{asset('assets/images/calculator.png')}}" width="23px;"></a>
                </div>
                <div class="group__box half_box">
                    <label class="col-5 col-form-label col-form-label-sm">Profit %:</label>
                    <div class="box__input__purchase6">
                        <input type="" name="">
                    </div>
                </div>
                <div class="group__box half_box">
                    <label class="col-5 col-form-label col-form-label-sm">Total QTY:</label>
                    <div class="box__input__purchase5">
                        <input type="" name="">
                    </div>
                    <a href=""><img src="{{asset('assets/images/calculator.png')}}" width="23px;"></a>
                </div>
            </div>
            <div class="col-md-3">
             <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">Max R Price:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
            <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">Cash Disc:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
            <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">Cash Bonus %:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">QTY Bonus:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
            <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">Carry Cost:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
            <div class="group__box half_box">
                <label class="col-6 col-form-label col-form-label-sm">Net Unit Cost:</label>
                <div class="box__input__purchase7">
                    <input type="" name="">
                </div>
            </div>
        </div>
        <div class="col-md-3">
         <div class="group__box half_box">
            <label class="col-6 col-form-label col-form-label-sm">Dish Unit Cost:</label>
            <div class="box__input__purchase7">
                <input type="" name="">
            </div>
        </div>
        <div class="group__box half_box">
            <label class="col-6 col-form-label col-form-label-sm">Curr Sell Price:</label>
            <div class="box__input__purchase7">
                <input type="" name="">
            </div>
        </div>
        <div class="group__box half_box">
            <label class="col-6 col-form-label col-form-label-sm">New Sell Price:</label>
            <div class="box__input__purchase7">
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
                <thead></thead>
            </table>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        {{-- next group --}}
        <div class="group__box half_box">
            <div class="box__label__modal">
                <label class="col-12">SubTotal:</label>
            </div>&nbsp;
            <div class="box__input"  style="flex: 0 0 10%;">
                <input type="" name="">
            </div>
            <div class="box__label__modal">
                <label class="col-12">Discount:</label>
            </div>&nbsp;
            <div class="box__input"  style="flex: 0 0 10%;">
                <input type="" name="">
            </div>
            <div class="box__label__modal">
                <label class="col-12">Total Tax:</label>
            </div>&nbsp;
            <div class="box__input"  style="flex: 0 0 10%;">
                <input type="" name="">
            </div>
            <div class="box__label__modal">
                <label class="col-12">Total Amt:</label>
            </div>&nbsp;
            <div class="box__input"  style="flex: 0 0 10%;">
                <input type="" name="">
            </div>
            <div class="box__label__modal">
                <label class="col-12">Ref No:</label>
            </div>&nbsp;
            <div class="box__input"  style="flex: 0 0 10%;">
                <input type="" name="">
            </div>&nbsp;&nbsp;
            <a href="#" class="btn default-btn f-btn-icon-r"><i class="fas fa-code"></i></a>
        </div>
    </div>
</div>
</div>
</div>