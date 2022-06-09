<form action="{{ route('patient.discount.mode.update') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Discount label</label>
                <div class="col-md-6">
                    <input type="hidden" name="old_fldtype" class="form-control" value="{{ $discountData->fldtype }}"/>
                    <input type="text" name="fldtype" class="form-control" value="{{ $discountData->fldtype }}" required/>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Discount mode</label>
                <div class="col-md-6">
                    <select name="fldmode" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="FixedPercent" {{ $discountData->fldmode == "FixedPercent"? "selected":"" }}>Fixed Percent</option>
                        <option value="CustomValues" {{ $discountData->fldmode == "CustomValues"? "selected":"" }}>Custom Values</option>
                        <option value="None" {{ $discountData->fldmode == "None"? "selected":"" }}>None</option>
                        <option value="Flexible" {{ $discountData->fldmode == "Flexible"? "selected":"" }}>Flexible</option>
                        <option value="FlexibleWithLimit" {{ $discountData->fldmode == "FlexibleWithLimit"? "selected":"" }}>Flexible With Limit</option>
                    </select>
                </div>
            </div>
            <!-- flexible starts -->
            @if($discountData->fldmode == "FlexibleWithLimit")
            <div class="form-group form-row flexible-with-limit">
                <label for="flddiscountlimit" class="col-md-6 control-label">Discount Limit</label>
                <div class="col-md-6">
                    <input type="number" id="flddiscountlimit" class="form-control" step="0.25" name="flddiscountlimit" placeholder="Limit should be in range (0-100)" value="{{ $discountData->flddiscountlimit }}">
                </div>
            </div>
            @endif
            <!-- flexible ends -->

            {{--</div>
            <div class="col-md-3">--}}
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Disc Atm/Year</label>
                <div class="col-md-6">
                    <input type="text" name="fldamount" placeholder="0" class="form-control" value="{{ $discountData->fldamount }}" />
                </div>
            </div>
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Year Start</label>
                <div class="col-md-6 padding-none">
                    {{-- <input type="date" name="fldyear" class="form-control" value="{{ $discountData->fldyear?date("Y-m-d", strtotime($discountData->fldyear)):'' }}"/> --}}
                    <input type="text" name="fldyear" id="nepaliDatePicker" class="form-control nepaliDatePicker" value="{{ $discountData->fldyear?date("Y-m-d", strtotime($discountData->fldyear)):'' }}"/>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Billing Mode</label>
                <div class="col-md-6">
                    <select name="fldbillingmode" class="form-control" required>
                        <option value="%">%</option>
                        @if(isset($billingset))
                            @foreach($billingset as $b)
                                <option value="{{$b->fldsetname}}" {{ $discountData->fldbillingmode == $b->fldsetname? "selected":"" }}>{{$b->fldsetname}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Fix Disc %</label>
                <div class="col-md-6">
                    <input type="text" name="fldpercent" placeholder="0" class="form-control" value="{{ $discountData->fldpercent }}" />
                </div>
            </div>
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Credit AMT</label>
                <div class="col-md-6">
                    <input type="text" name="fldcredit" placeholder="0" class="form-control" value="{{ $discountData->fldcredit }}" />
                </div>
            </div>
            <div class="form-group form-row">
                <label for="" class="col-md-6 control-label">Department</label>
                <div class="col-md-6">
                    <select name="request_department_pharmacy" class="form-control"  id="request_department_pharmacy">
                        <option value="">--Select--</option>
                        @if(count($departments))
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ $discountData->fldmode == $department? "selected":"" }}>{{ $department }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mt-3">
            <div class="form-group text-right">
                <button class="btn btn-info"><i class="fa fa-edit"></i> Update</button>
            </div>
        </div>
    </div>
</form>
