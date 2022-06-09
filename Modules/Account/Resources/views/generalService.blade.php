@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class=" col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="js-generalService-add-form">
                        <div class="form-row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Item Code</label>
                                    <div class="col-lg-9 col-sm-6">
                                        <select class="form-control select2" name="fldbillitem" id="js-generalService-billitem-input" data-variable="tblbillitem">
                                            <option value="">-- Select --</option>
                                            @foreach ($billitems as $billitem)
                                            <option value="{{ $billitem->fldbillitem }}">{{ $billitem->fldbillitem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row align-items-center">

                                        <label class="col-lg-3 col-sm-3">Code<span class="text-danger">*</span></label>
                                        <div class="col-lg-3 col-sm-3">
                                            <input type="text" class="form-control" name="flditemcode" id="flditemcode" required readonly>
                                        </div>
                                        <label class="col-lg-3 col-sm-3">Price<span class="text-danger">*</span></label>
                                        <div class="col-lg-3 col-sm-3">
                                            <input type="text" class="form-control" name="flditemcost" id="flditemcost" required>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Bill Mode<span class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-sm-8">
                                        <select class="form-control" name="fldgroup" id="js-generalService-bill-mode-select" required>
                                            <option value="">-- Select --</option>
                                            <option value="%">%</option>
                                            @foreach($billingset as $b)
                                                <option value="{{$b->fldsetname}}" @if(isset($enpatient) && ($enpatient->fldbillingmode ==$b->fldsetname) ) selected="selected" @endif >{{$b->fldsetname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <select name="fldstatus" class="form-control" id="js-generalService-status-input" required>
                                            <option value="">-- Select --</option>
                                            <option value="Active" {{ (request('fldstatus') == 'Active') ? 'selected="selected"' : '' }}>Active</option>
                                            <option value="Inactive" {{ (request('fldstatus') == 'Inactive') ? 'selected="selected"' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-primary" id="js-generalService-search-btn">
                                            <i class="fa fa-sync"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Rate For</label>
                                    <div class="col-lg-9 col-sm-8">
                                        <select name="fldtarget" class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="Day">Day</option>
                                            <option value="Hour">Hour</option>
                                            <option value="Minute">Minute</option>
                                            <option value="Unit">Unit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Section</label>
                                    <div class="col-lg-9 col-sm-6">
                                        <select name="fldreport" id="js-generalService-section-input" class="form-control select2" data-variable="tblbillsection">
                                            <option value="">-- Select --</option>
                                            @foreach ($sections as $section)
                                            <option value="{{ $section->fldsection }}">{{ $section->fldsection }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Code</label>
                                    <div class="col-lg-9 col-sm-8">
                                        <select name="fldcode" class="form-control">
                                            <option value="">--Select--</option>
                                            @php
                                                $taxGroup = Helpers::taxGroup();
                                            @endphp
                                            @if($taxGroup)
                                                @foreach($taxGroup as $group)
                                                    <option value="{{ $group->fldgroup }}">{{ $group->fldgroup }}</option>
                                                @endforeach
                                            @endif
                                        </select>
{{--                                        <input type="text" class="form-control" name="fldcode">--}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Item Name<span class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-sm-8">
                                        <input type="text" class="form-control" name="flditemname" id="js-generalService-item-name-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-lg-3 col-sm-4">Account Ledger</label>
                                        <div class=" col-lg-9 col-sm-8">
                                            <select id="select-accountledger" class="form-control select2"  name="accountledger" required>
                                            <option value=""></option>
                                            @foreach ($accountLedger as $key => $ledger)
                                                    <option value="{{ $ledger->AccountNo }}">{{ ucfirst($ledger->AccountName) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            {{-- category --}}
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Category</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <select id="select-category" class="form-control select2" multiple name="category[]" required>
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <div class="col-md-3">
                                        <input class="magic-checkbox" type="checkbox" name="rate" value="1">
                                        <label for="">Rate</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input class="magic-checkbox" type="checkbox" name="discount" value="1">
                                        <label for="">Discount</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">HI Code</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <input id="hi_code" type="number" max="100" min="0" class="form-control" name="hi_code">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Hospital Share %</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <input id="hospital_share" type="number" max="100" min="0" class="form-control" name="hospital_share">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-lg-3 col-sm-4">Other Share %</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <input id="other_share" type="number" max="100" min="0" class="form-control" name="other_share">
                                    </div>
                                </div>
                            </div>
                        </div>
                <div class="form-group text-right">
                    <button type="button" id="js-generalService-save-btn" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                    <button type="button" id="js-generalService-update-btn" class="btn btn-info"><i class="fa fa-edit"></i> Update</button>
                    <button type="button" id="js-generalService-clear-btn" class="btn btn-warning"> Clear</button>
                    <a href="{{ route('account.generalService.exportItems') }}" target="_blank" id="js-generalService-export-btn" class="btn btn-warning"><i class="fa fa-file-pdf" aria-hidden="true"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="res-table table-sticky-th">
                <div class="form-group form-row align-items-center">
                    <label for="" class="col-sm-1">Search:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="search" id="js-generalService-search-input">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn-primary" title="Search in DB" id="seach_indb"><i class="ri-search-line"></i></button>
                    </div>
                </div>
                <!-- <div class="form-group form-row mt-3">
                    <label class="col-md-1">CSV File:</label>
                    <div class="col-md-4">
                        <input type="text" name="" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Import</button>
                    </div>
                </div> -->
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Particulars</th>
                            <th>Fee</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>BillMode</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody id="js-generalService-item-tbody">
                        @foreach($all_items as $item)
                        @php $billitemcode = \App\BillItem::where('fldbillitem',$item->fldbillitem)->pluck('fldbillitemcode')->first(); @endphp
                        <tr fldid="{{ $item->fldid }}" flddocshare="{{ $item->other_share }}" fldhospitalshare="{{ $item->hospital_share }}" accountledger="{{$item->account_ledger}}"  fldcategory="{{ json_encode($item->category) }}" fldbillitem="{{ $item->fldbillitem }}" flditemcost="{{ $item->flditemcost }}" fldtarget="{{ $item->fldtarget }}" fldgroup="{{ $item->fldgroup }}" fldreport="{{ $item->fldreport }}" fldstatus="{{ $item->fldstatus }}" fldcode="{{ $item->fldcode }}" flditemname="{{ $item->flditemname }}" fldbillitemcode="{{ $billitemcode }}" >
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->flditemname }}</td>
                            <td>{{ $item->flditemcost }}</td>
                            <td>{{ $item->fldtarget }}</td>
                            <td>{{ $item->fldstatus }}</td>
                            <td>{{ $item->fldgroup }}</td>
                            <td>{{ $item->fldreport }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="bottom_anchor">

                </div>
            </div>
            <nav aria-label="..." class="mt-2">
                {{ $all_items->links() }}
{{--                <ul class="pagination mb-0">--}}
{{--                    <li class="page-item">--}}
{{--                        <a class="page-link" href="#" aria-label="Previous">--}}
{{--                        <span aria-hidden="true">«</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                    <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                    <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                    <li class="page-item">--}}
{{--                        <a class="page-link" href="#" aria-label="Next">--}}
{{--                        <span aria-hidden="true">»</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
            </nav>
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="js-generalService-add-item-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="text" id="js-generalService-flditem-input-modal" style="width: 100%;">
                    <input type="hidden" id="js-generalService-type-input-modal">
                    <input type="hidden" id="js-generalService-category-input-modal" value="General Services">
                </div>
                <div>
                    <button class="btn btn-default" id="js-generalService-add-btn-modal"><i class="fa fa-plus"></i> &nbsp;Add</button>
                    <button class="btn btn-default" style="float: right;" id="js-generalService-delete-btn-modal"><i class="fa fa-trash"></i> &nbsp;Delete</button>
                </div>
                <br>
                <div class="table-responsive table-sroll-lab">
                    <table id="js-generalService-table-modal" class="table table-bordered table-hover"></table>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <input type="file" id="js-generalService-modal-file-iput" class="form-control" accept=".txt">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="js-generalService-modal-import-btn">Import</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script src="{{ asset('js/generalService_form.js')}}"></script>
<script>
    $(function() {
        $("#js-generalService-update-btn").attr('disabled', true);
        $("#hospital_share").on('keyup', function(event) {
            let e = $(this);
            let h_share_value = e.val();
            let doc_share = $("#other_share");

            if (h_share_value > 100) {
                e.val(100);
                doc_share.val(0);
            }

            if (h_share_value <= 100) {
                doc_share.val(100 - h_share_value);
            }
        });

        $("#other_share").on('keyup', function(event) {
            let e = $(this);
            let doc_share_value = e.val();
            let hopspital_share = $("#hospital_share");
            if (doc_share_value > 100) {
                e.val(100);
                hopspital_share.val(0);
            }

            if (doc_share_value <= 100) {
                hopspital_share.val(100 - doc_share_value);
            }
        });

        $("#seach_indb").click(function () {
            var status = $('#js-generalService-status-input').val();
            var query = $('#js-generalService-search-input').val();

            $.ajax({
                url: '{{ route('account.generalService.search') }}',
                type: "POST",
                data:{
                    status :status,
                    input : query,
                },
                success: function (response) {
                    if(response.item){
                        $('#js-generalService-item-tbody').empty().append(response.item);
                    }else {
                        $('#js-generalService-item-tbody').empty().append('<tr><td colspan="8"> Nothing found! </td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });


    });
</script>
@endpush
