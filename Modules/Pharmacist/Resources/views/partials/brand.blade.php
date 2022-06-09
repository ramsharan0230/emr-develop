<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
          <form action="{{ route('insert.surg.brand') }}" method="post" id="brandForm">
            @csrf
            <input type="hidden" name="fldsurgid" id="brand-fldsurgid">
            <input type="hidden" name="fldid" id="brand-fldid">
            <input type="hidden" name="fldbrandid" id="brand_fldbrandid">
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
            <div class="iq-card-body">
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Brand Name:</label>
                    <div class="col-sm-3">
                      <input type="text" name="fldbrand" id="fldbrand" value="{{ old('fldbrand') }}" placeholder="" class="form-control" required>
                    </div>
                    <label for="" class="col-sm-3">Standard:</label>
                    <div class="col-sm-3">
                      <input type="text" name="fldstandard" id="fldstandard" value="{{ old('fldstandard') }}" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Minimum Stock:</label>
                    <div class="col-sm-3">
                      <input type="number" min="0" name="fldminqty" id="fldminqty" value="{{ old('fldminqty') }}" placeholder="" class="form-control">
                    </div>
                    <label for="" class="col-sm-3">Maximum Stock:</label>
                    <div class="col-sm-3">
                      <input type="number" min="0" name="fldmaxqty" id="fldmaxqty" value="{{ old('fldmaxqty') }}" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Manufacturer:</label>
                    <div class="col-sm-3">
                      <input type="text" name="fldmanufacturer" id="fldmanufacturer" value="{{ old('fldmanufacturer') }}" placeholder="" class="form-control">
                    </div>
                    <label for="" class="col-sm-3">Volume Unit:</label>
                    <div class="col-sm-3">
                      <input type="text" name="fldvolunit" id="fldvolunit" value="{{ old('fldvolunit') }}" placeholder="" class="form-control">
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
                    <label for="" class="col-sm-3">Lead Time(Days):</label>
                    <div class="col-sm-3">
                      <input type="number" min="0" name="fldleadtime" id="fldleadtime" value="{{ old('fldleadtime') }}" placeholder="" class="form-control">
                    </div>
                    <label for="" class="col-sm-3">Status:</label>
                    <div class="col-sm-3">
                      <select class="form-control select2" name="fldactive" id="fldactive" required>
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                      </select>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                  <label for="" class="col-sm-12">Descriptions:</label>
                  <div class="col-sm-12">
                      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                          <textarea name="flddetail" id="flddetail" class="form-control">
                          </textarea>
                      </div>
                  </div>
                </div>
                <button id="brandSave" class="btn btn-action btn-primary float-right">Save</button>
                <a id="clearBrand" class="btn btn-action btn-primary float-right text-white mr-1">Clear</a>
            </div>
          </form>
        </div>
    </div>
</div>
