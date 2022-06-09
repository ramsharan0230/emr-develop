<div class="form-group">
    <label><b> Payment Mode:</b></label>
    <div class="bak-payment p-2">
        <div class="form-group d-flex justify-content-between">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                <label class="custom-control-label" for=""> Cash</label>
                <img src="{{ asset('new/images/cash.png')}}"  alt=""  style="width:71%">
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                <label class="custom-control-label" for=""> Credit </label>
                <img src="{{ asset('new/images/credit.png')}}"  alt="" style="width:81%">
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                <label class="custom-control-label" for=""> Card </label>
                <img src="{{ asset('new/images/swipe-card.png')}}"  alt="" style="width:71%">
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                <label class="custom-control-label" for="">Fonepay </label>
                <img src="{{ asset('new/images/fonepay_logo.png')}}"  alt="">
            </div>
        </div>
    </div>

<!-- <div>
                                                <select name="payment_mode" id="payment_mode" class="form-control">
                                                    <option value="Cash">Cash</option>
                                                    @if(isset($enpatient) && $patientDepartment )
    <option value="Credit" {{ (strtoupper(substr($enpatient->fldencounterval, 0,2)) === "IP") ? "selected" : '' }}>Credit</option>
                                                    @endif
    <option value="Cheque">Cheque</option>
{{-- <option value="Fonepay">Fonepay</option>--}}
    <option value="Other">Other</option>
</select>
</div> -->
</div>
