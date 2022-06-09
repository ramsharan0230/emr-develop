@extends('frontend.layouts.master')

@section('content')
<section class="cogent-nav">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#fixed" role="tab" aria-controls="transfer" aria-selected="true"><span></span>Fixed Assets</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="fixed" role="tabpanel" aria-labelledby="fixed-tab">
            <div class="container-fluid">
                <div class="profile-form">
                    <div class="row" style="margin-bottom: 10px;">
                        <form id="js-purchase-fixed-assetsentry-form" class="form-group">
                            <div class="col-md-4">
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Particular:</label>
                                    <div class="box__input" style="flex: 0 0 63%;">
                                        <select name="flditemname" id="js-purchase-fixed-items-select">
                                            <option value="">-- Select --</option>
                                            @foreach($items as $item)
                                            <option data-fldid="{{ $item->fldid }}" data-fldgroup="{{ $item->fldgroup }}" data-fldledger="{{ $item->fldledger }}" value="{{ $item->flditemname }}">{{ $item->flditemname }}</option>
                                            @endforeach
                                        </select>
                                    </div>&nbsp;
                                      <a href="javascript:;" id="js-purchase-fixed-sync-btn"><i class="fas fa-sync"></i></a>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Group:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldgroup" id="js-purchase-fixed-group-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Ledger:</label>
                                    <div class="box__input" style="flex: 0 0 61%;">
                                        <input type="text" name="fldledger" id="js-purchase-fixed-ledger-input">
                                    </div>
                                    <div class="image-upload">
                                        <label style="width: 30px;">
                                            <img src="{{ asset('assets/images/add.png')}}" alt="" width="15px;">
                                        </label>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Specification:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldspecs" id="js-purchase-fixed-specification-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Remarks:</label>
                                    <div class="box__input">
                                        <textarea name="fldcomment" style="height: 106px; width: 105%;" id="js-purchase-fixed-remarks-input"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Manufacturer:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldmanufacturer" id="js-purchase-fixed-manufacturer-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Supplier:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <select name="fldsuppname" id="js-purchase-fixed-supplier-input">
                                            <option value="">-- Select --</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->fldsuppname }}">{{ $supplier->fldsuppname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Location:</label>
                                    <div class="box__input" style="flex: 0 0 61%;">
                                        <span id="js-purchase-fixed-input-parent">
                                            <input type="text" name="fldcomp" id="js-purchase-fixed-location-input">
                                        </span>
                                    </div>
                                    <div class="image-upload">
                                        <label for="js-purchase-fixed-textfile-input" style="width: 30px;">
                                            <img src="{{ asset('assets/images/add.png')}}" alt="" width="15px;">
                                        </label>
                                        <input id="js-purchase-fixed-textfile-input" type="file" style="display: none;" accept=".txt">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Purchase:</label>
                                    <div class="box__input" style="flex: 0 0 61%;">
                                        <input type="date" name="fldpurdate" class="f-input-date" id="js-purchase-fixed-purchase-input">
                                    </div>
                                    <div class="box__icon">
                                        <a href=""><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Repair:</label>
                                    <div class="box__input" style="flex: 0 0 61%;">
                                        <input type="date" name="fldrepairdate" class="f-input-date" id="js-purchase-fixed-repair-input">
                                    </div>
                                    <div class="box__icon">
                                        <a href=""><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-3 col-form-label col-form-label-sm">Discount:</label>
                                    <div class="box__input" style="flex: 0 0 29%;">
                                        <input type="text" name="flddiscamt" value="0" id="js-purchase-fixed-discount-input">
                                    </div>
                                    <label class="col-2 col-form-label col-form-label-sm">Tax:</label>
                                    <div class="box__input" style="flex: 0 0 30%;">
                                        <input type="text" name="fldtaxamt" value="0" id="js-purchase-fixed-tax-input">
                                    </div>
                                </div>
                                <!-- next_group -->
                                <div class="group__box half_box">
                                    <div class="box__label" style="flex: 0 0 32%;">
                                        <button class="default-btn full-width f-btn-icon-r"><i class="fas fa-code"></i>&nbsp;&nbsp;Group</button>
                                    </div>&nbsp;
                                     <div class="box__label" style="flex: 0 0 32%;">
                                        <button class="default-btn full-width f-btn-icon-r"><i class="fas fa-code"></i>&nbsp;&nbsp;Location</button>
                                    </div>&nbsp;
                                     <div class="box__label" style="flex: 0 0 34%;">
                                        <button class="default-btn full-width f-btn-icon-r"><i class="fas fa-code"></i>&nbsp;&nbsp;Export</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Code No:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldcode" id="js-purchase-fixed-code-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Model No:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldmodel" id="js-purchase-fixed-model-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Serial No:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldserial" id="js-purchase-fixed-serial-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Condition:</label>
                                    <div class="box__input" style="flex: 0 0 68%;">
                                        <input type="text" name="fldcondition" id="js-purchase-fixed-condition-input">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-4 col-form-label col-form-label-sm">Quantity:</label>
                                    <div class="box__input" style="flex: 0 0 38%;">
                                        <input type="text" name="fldqty" id="js-purchase-fixed-qty-input" value="0">
                                    </div>
                                    <div class="box__input" style="flex: 0 0 30%;">
                                        <input type="text" name="fldunit" id="js-purchase-fixed-unit-input">
                                    </div>
                                </div>
                                 <div class="group__box half_box">
                                    <label class="col-2 col-form-label col-form-label-sm">Rate:</label>
                                    <div class="box__input" style="flex: 0 0 29%;">
                                        <input type="text" name="flditemrate" id="js-purchase-fixed-rate-input" value="0">
                                    </div>
                                    <label class="col-2 col-form-label col-form-label-sm">Total:</label>
                                    <div class="box__input" style="flex: 0 0 39%;">
                                        <input type="text" name="fldditemamt" id="js-purchase-fixed-total-input" value="0">
                                    </div>
                                </div>
                                <!-- next_group -->
                                <div class="group__box half_box" style="margin-left: 49%;">
                                    <div class="box__label" style="flex: 0 0 43%;">
                                        <button type="button" id="js-purchase-fixed-assetsentry-save-btn" class="default-btn full-width f-btn-icon-g"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                    </div>&nbsp;
                                     <div class="box__label" style="flex: 0 0 57%;">
                                        <button type="button" id="js-purchase-fixed-assetsentry-update-btn" class="default-btn full-width"><img src="{{asset('assets/images/edit.png')}}" width="18px;">&nbsp;&nbsp;Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                   <div class="col-md-12">
                       <div class="table-scroll-md table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Particular</td>
                                        <td>Manufacturer</td>
                                        <td>Ledger</td>
                                        <td>Model</td>
                                        <td>Serial</td>
                                        <td>QTY</td>
                                        <td>Amount</td>
                                        <td>Location</td>
                                    </tr>
                                </thead>
                                <tbody id="js-purchase-fixed-assetsentry-tbody"></tbody>
                            </table>
                        </div>
                   </div> 
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade show" id="js-purchase-fixed-add-item-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="js-purchase-fixed-modal-form">
                    <div class="group__box half_box">
                        <label class="col-2 col-form-label col-form-label-sm">Name:</label>
                        <div class="box__input">
                            <input type="text" name="flditemname">
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <label class="col-2 col-form-label col-form-label-sm">Ledger:</label>
                        <div class="box__input">
                            <input type="text" name="fldledger">
                        </div>
                    </div>
                    <div class="group__box half_box">
                        <label class="col-2 col-form-label col-form-label-sm">Group:</label>
                        <div class="box__input">
                            <input type="text" name="fldgroup">
                        </div>
                    </div>
                </form>
                <div>
                    <button style="float: left;" id="js-purchase-fixed-add-btn-modal"><img src="{{asset('assets/images/plus.png')}}" width="16px" alt=""> &nbsp;Add</button>
                    <button style="float: right;" id="js-purchase-fixed-delete-btn-modal"><img src="{{asset('assets/images/cancel.png')}}" width="16px" alt=""> &nbsp;Delete</button>
                </div>
                <div>
                    <table id="js-purchase-fixed-table-modal"></table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@push('after-script')
    <script src="{{ asset('js/purchase_form.js') }}"></script>
@endpush