@extends('frontend.layouts.master')
@push('after-styles')
<style>
  ul {
    list-style-type: none;
    display: contents;
  }
</style>
@endpush
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">Extra-item Information</h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="form-group form-row align-items-center">
            <input type="hidden" name="fldid" id="extraItemFldId">
            <label for="" class="col-sm-2 col-lg-2">Item Name:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="fldextraid" id="fldextraid">
            </div>
            <div class="col-sm-6">
              <a href="#" id="extraItemClear" class="btn btn-action btn-primary">Clear</a>
              <a href="#" id="extraItemSave" class="btn btn-action btn-primary">Add</a>
              <a href="#" id="extraItemDelete" class="btn btn-action btn-primary">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @if(Session::get('success_message'))
          <div class="alert alert-success containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            {{ Session::get('success_message') }}
          </div>
          @endif
          @if(Session::get('error_message'))
          <div class="alert alert-success containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            {{ Session::get('error_message') }}
          </div>
          @endif
          <div class="row">
            <div class="col-lg-4 col-md-12">
              <select name="fldextraid" id="selectExtraItems" class="form-control select2">
                <option value="">-- Select Item Names --</option>
                @foreach($extras as $extra)
                <option value="{{ $extra->fldextraid }}">{{ $extra->fldextraid }}</option>
                @endforeach
              </select>
              <div class="res-table" style="max-height: 750px;" id="extraItemListingTable">
                  <ul class="list-group mt-2" id="extraitemlistingtable">
                  </ul>
              </div>
            </div>
            <div class="col-lg-8 col-md-12">
              <ul class="nav nav-tabs" role="tablist" id="main-tab" style="display: none">
                <li class="nav-item">
                  <a class="nav-link active" id="brand_tab" data-toggle="tab" href="#brand" role="tab" aria-controls="brand" aria-selected="false">Brand Information</a>
                </li>
              </ul>
              <div class="tab-content" style="display: none">
                <div class="tab-pane fade show active" id="brand" role="tabpanel" aria-labelledby="brand">
                    <div class="row">
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
                                    <form action="{{ route('insert.extra.item') }}" method="post" id="brandForm">
                                        @csrf
                                        <div class="iq-card-body">
                                            <div class="form-group form-row align-items-center er-input">
                                                <input type="hidden" name="fldbrandid" id="brandid">
                                                <input type="hidden" name="fldextraid" id="extra_id">
                                                <label for="" class="col-sm-3">Brand Name:</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="fldbrand" id="fldbrand" value="{{ old('fldbrand') }}" placeholder="" class="form-control" required>
                                                </div>
                                                <label for="" class="col-sm-3">Category:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control select2" name="fldstandard" id="fldstandard" required>
                                                        <option value=""></option>
                                                        <option value="Consumer">Consumer</option>
                                                        <option value="Non-consumer">Non-consumer</option>
                                                        <option value="Miscellaneous">Miscellaneous</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center er-input">
                                                <label for="" class="col-sm-3">Pack Volume:</label>
                                                <div class="col-sm-1">
                                                    <input type="number" step="any" min="0" name="fldpackvol" id="fldpackvol" value="{{ old('fldpackvol') }}" placeholder="0" class="form-control" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" name="fldvolunit" id="fldvolunit" value="{{ old('fldvolunit') }}" placeholder="" class="form-control" size="13" required>
                                                </div>
                                                <label for="" class="col-sm-3">Manufacturer:</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="fldmanufacturer" id="fldmanufacturer" value="{{ old('fldmanufacturer') }}" placeholder="" class="form-control">
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
                                                    <input type="text" name="fldmrp" id="fldmrp" value="{{ old('fldmrp') }}" class="form-control">
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
                                                    <input type="text" name="fldcccharg_val" id="fldcccharg_val" value="{{ old('fldcccharg_val') }}" class="form-control">
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
                                                                    <input class="form-check-input" type="radio" name="flddiscountable_item" id="flddiscountable_item_no" value="0">
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
                                                                    <input class="form-check-input" type="radio" name="fldinsurance" id="fldinsurance_no" value="0">
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
                                                                <input class="form-check-input" type="radio" name="fldrefundable" id="fldrefundable_yes" value="1">
                                                                <label class="form-check-label" for="fldrefundable_yes">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="fldrefundable" id="fldrefundable_no" value="0">
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
                                                <label for="" class="col-sm-3">Department:</label>
                                                <div class="col-sm-3">
                                                    @php
                                                    $department = \App\Utils\Helpers::getDepartmentAndComp();
                                                    @endphp
                                                    <select class="form-control select2" name="flddepart" id="flddepart">
                                                    <option value=""></option>
                                                    @foreach ($department as $hosp_dept)
                                                    <option value="{{ $hosp_dept->id }}">{{ $hosp_dept->name }} @if(isset($hosp_dept->branchData)) ({{$hosp_dept->branchData->name}}) @endif</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                                <label for="" class="col-sm-3">Lead Time(Days):</label>
                                                <div class="col-sm-3">
                                                    <input type="number" min="0" name="fldleadtime" id="fldleadtime" value="{{ old('fldleadtime') }}" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center er-input">
                                                <label for="" class="col-sm-3">Status:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control select2" name="fldactive" id="fldactive">
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
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('after-script')
@include('pharmacist::extra-items-js')
@endpush
