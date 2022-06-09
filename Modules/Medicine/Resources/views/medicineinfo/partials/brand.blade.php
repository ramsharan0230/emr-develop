<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="form-group">
                <input type="checkbox" id="showBrandLists"> Show Brand Lists
            </div>
            <div class="table-reponsive table-container" id="brandTableLists" style="display: none">
                <table class="table table-striped table-bordered table-hover ">
                    <thead class="thead-light">
                        <tr>
                            <th>SNo</th>
                            <th>Brand</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>SellP</th>
                            <th>Qty</th>
                            <th>Taxable</th>
                            <th>Taxcode</th>
                            <th>Stat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="brand-table-list">
                    </tbody>
                </table>
            </div>
            <form action="{{ route('medicines.medicineinfo.addbrandinfo') }}" id="brandForm" method="POST">
                <input type="hidden" name="flddrug" id="brand_flddrug">
                <input type="hidden" name="fldcodename" id="brand_fldcodename">
                <input type="hidden" name="fldbrandid" id="brand_fldbrandid">
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Brand Name:</label>
                    <div class="col-sm-4">
                    <input type="text" name="fldbrand" id="fldbrand" value="{{ old('fldbrand') }}" class="form-control" required>
                    </div>
                    {{-- <label for="" class="col-sm-2">Current Stock:</label>
                    <div class="col-sm-4">
                    <input type="number" name="fldcurrstock" id="fldcurrstock" value="{{ old('fldcurrstock') }}" placeholder="0" class="form-control"> --}}
                    {{-- </div> --}}
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Pack Volume:</label>
                    <div class="col-sm-1">
                    <input type="number" step="any" min="0" name="fldpackvol" id="fldpackvol" value="{{ old('fldpackvol') }}" placeholder="0" class="form-control" required>
                    </div>
                    <div class="col-sm-3">
                    <input type="text" name="fldvolunit" id="fldvolunit" value="{{ old('fldvolunit') }}" placeholder="" class="form-control" size="13" required>
                    </div>
                    <label for="" class="col-sm-3">Minimum Stock:</label>
                    <div class="col-sm-3">
                    <input type="number" name="fldminqty" id="fldminqty" value="{{ old('fldminqty') }}" placeholder="0" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Dosage Form:</label>
                    <div class="col-sm-3">
                        <select name="flddosageform" class="form-control select2 select2DosageForms" id="brandDosage" required>
                            <option value=""> Select Dosage </option>
                            @forelse($dosageforms as $dosageform)
                            <option value="{{ $dosageform->flforms }}" data-id="{{ $dosageform->fldid }}" {{ (old('flddosageform') == $dosageform->flforms) ? 'selected' : ''}}>{{ $dosageform->flforms }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#med_category_modal" class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></a>
                        @include('medicine::layouts.modal.dosageform')
                    </div>
                    <label for="" class="col-sm-3">Maximum Stock:</label>
                    <div class="col-sm-3">
                    <input type="number" name="fldmaxqty" id="fldmaxqty" value="{{ old('fldmaxqty') }}" placeholder="0" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Standard:</label>
                    <div class="col-sm-4">
                    <input type="text" name="fldstandard" id="fldstandard" value="{{ old('fldstandard') }}" class="form-control">
                    </div>
                    <label for="" class="col-sm-3">Lead Time (Days):</label>
                    <div class="col-sm-3">
                    <input type="number" name="fldleadtime" id="fldleadtime" value="{{ old('fldleadtime') }}" placeholder="0" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Manufacturer:</label>
                    <div class="col-sm-4">
                    <input type="text" name="fldmanufacturer" id="fldmanufacturer" value="{{ old('fldmanufacturer') }}" class="form-control">
                    </div>
                    <label for="" class="col-sm-2">Preservatives:</label>
                    <div class="col-sm-4">
                    <input type="text" name="fldpreservative" id="fldpreservative" value="{{ old('fldpreservative') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Taxable:</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="fldtaxable" id="fldtaxable">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3">Tax Code:</label>
                    <div class="col-sm-3">
                        <select name="fldtaxcode" id="fldtaxcode" class="form-control select2">
                            <option value=""> Select Tax Code </option>
                            @forelse($tax_codes as $tax_code)
                            <option value="{{ $tax_code->fldgroup }}" {{ (old('fldtaxcode') ==  $tax_code->fldgroup) ? 'selected' : '' }}>{{ $tax_code->fldgroup }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Narcotic Dispensing:</label>
                    <div class="col-sm-3">
                        <select name="fldnarcotic" id="fldnarcotic" class="form-control select2">
                            <option value=""> Select Narcotic Dispensing </option>
                            <option value="No" {{ (old('fldnarcotic') == 'No') ? 'selected' : '' }}>No</option>
                            <option value="Yes" {{ (old('fldnarcotic') == 'Yes') ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3">Allow Table Break:</label>
                    <div class="col-sm-3">
                        <select name="fldtabbreak" id="fldtabbreak" class="form-control select2">
                            <option value=""> Select </option>
                            <option value="No" {{ (old('fldtabbreak') == 'No') ? 'selected' : '' }}>No</option>
                            <option value="Yes" {{ (old('fldtabbreak') == 'Yes') ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>

                <!-- new fields addition -->
                <div class="form-group form-row align-items-center er-input">
                    <label for="fldmrp" class="col-sm-2">MRP:</label>
                    <div class="col-sm-8">
                        <input type="text" name="fldmrp" id="fldmrp" value="{{ old('fldmrp') }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">CC Charge:</label>
                    <div class="col-sm-5">
                        <div class="form-check">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input class="form-check-input fldcccharge" type="radio" name="fldcccharge" id="fldcccharge_amt" value="fldcccharge_amt">
                                    <label class="form-check-label" for="fldcccharge_amt">
                                        Amount
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input fldcccharge" type="radio" name="fldcccharge" id="fldcccharge_percent" value="fldcccharge_percent">
                                        <label class="form-check-label" for="fldcccharge_percent">
                                            Percentage
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <label for="" class="col-sm-2">Value:</label>
                    <div class="col-sm-3">
                        <input type="number" name="fldcccharg_val" id="fldcccharg_val" value="{{ old('fldcccharg_val') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group form-row align-items-center er-input">
                    <label for="flddicountable_item" class="col-sm-2">Discountable Item:</label>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input class="form-check-input" type="radio" name="flddiscountable_item" id="flddiscountable_item_yes" value="1">
                                    <label class="form-check-label" for="flddiscountable_item_yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flddiscountable_item" id="flddiscountable_item_no" value="0" checked>
                                        <label class="form-check-label" for="flddiscountable_item_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>

                <div class="form-group form-row align-items-center er-input">
                    <label for="fldinsurance" class="col-sm-2">Insurance:</label>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input class="form-check-input" type="radio" name="fldinsurance" id="fldinsurance_yes" value="1">
                                    <label class="form-check-label" for="fldinsurance_yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="fldinsurance" id="fldinsurance_no" value="0" checked>
                                        <label class="form-check-label" for="fldinsurance_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group form-row align-items-center er-input">
                    <label for="fldrefundable" class="col-sm-2">Refundable:</label>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input class="form-check-input" type="radio" name="fldrefundable" id="fldrefundable_yes" value="1" checked>
                                    <label class="form-check-label" for="fldrefundable_yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="fldrefundable" id="fldrefundable_no" value="0" >
                                        <label class="form-check-label" for="fldrefundable_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- new fields addition end -->

                
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Default label Name:</label>
                    <div class="col-sm-3">
                        <select name="flddeflabel" id="flddeflabel" class="form-control select2">
                            <option value=""> Select </option>
                            <option value="Both" {{ (old('flddeflabel') == 'Both') ? 'selected' : '' }}>Both</option>
                            <option value="Brand" {{ (old('flddeflabel') == 'Brand') ? 'selected' : '' }}>Brand</option>
                            <option value="Generic" {{ (old('flddeflabel') == 'Generic') ? 'selected' : '' }}>Generic</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3">Current Status:</label>
                    <div class="col-sm-3">
                        <select name="fldactive" id="fldactive" class="form-control select2">
                            <option value="Active" {{ (old('fldactive') == 'Active') ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ (old('fldactive') == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-2">Description:</label>
                    <div class="col-sm-12">
                        <textarea name="flddetail" id="flddetail" class="form-control">
                        </textarea>
                    </div>
                </div>
                <button id="brandSave" class="btn btn-action btn-primary float-right mb-2">Save</button>
                <a id="clearBrand" class="btn btn-action btn-primary float-right text-white mr-2">Clear</a>
            </form>
        </div>
    </div>
</div>
