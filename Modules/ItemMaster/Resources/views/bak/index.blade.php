@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        #myTable_filter {
            width: 100%;
            padding: 14px 0;
            background: #f8f9fa;
        }

        #myTable_filter label {
            width: 100%;
            text-align: left;
        }

        #myTable_filter label input {
            width: 74%;
            margin-left: 55px;
        }

        #myTable_paginate {
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
        }

        #myTable_paginate span {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        td.scrollable {
            white-space: nowrap;
            overflow-x: auto;
            max-width: 150px;
            height: 20px;
        }

        td.scrollable::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        td.scrollable::-webkit-scrollbar-track {
            border-radius: 10px;
            background: #e5e5e5;
        }

        td.scrollable::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: #c4c4c4;
        }

        .title {
            width: 15%;
        }

        .desc {
            width: 85%;
            font-weight: 600;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            border: 2px solid #35fc74;
        }

        .res-table {
            width: 100%;
            overflow: auto;
            max-height: unset;
        }

    </style>
@endpush
@section('content')
    <div class="container-fluid ">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" style="background-color: unset;" aria-current="page"
                    href="{{ route('itemmaster.create') }}">Add Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" style="background-color: unset;" href="javascript:void(0)">Manage</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">

                        <!-- Filter  -->
                        <form onsubmit="loader()">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 style="margin: 5px 0 5px 0;">Filter</h5>
                                <a href="{{ route('itemmaster.index') }}" class="btn btn-primary">
                                    <i class="fa fa-sync"></i> Reset
                                </a>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Category</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="flditemtype">
                                                <option value="">-- All --</option>
                                                @foreach ($categories as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $category == $key ? 'selected' : '' }}>{{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Target</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldtarget">
                                                <option value="">-- All --</option>
                                                @foreach ($targets as $value)
                                                    <option value="{{ $value }}"
                                                        {{ $target == $value ? 'selected' : '' }}>
                                                        {{ ucfirst($value) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Department</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldreport">
                                                <option value="">-- All --</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->fldsection }}"
                                                        {{ $target == $section->fldsection ? 'selected' : '' }}>
                                                        {{ $section->fldsection }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Account Ledger</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="account_ledger">
                                                <option value="">-- All --</option>
                                                @foreach ($accountLedgers as $ledger)
                                                    <option value="{{ $ledger->AccountNo }}"
                                                        {{ $accountLedger == $ledger->AccountNo ? 'selected' : '' }}>
                                                        {{ $ledger->AccountName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Tax Type</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="fldcode">
                                                <option value="">-- All --</option>
                                                <option value="TDS" {{ $tax == 'TDS' ? 'selected' : '' }}>TDS</option>
                                                <option value="VAT" {{ $tax == 'VAT' ? 'selected' : '' }}>VAT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="d-flex flex-row justify-content-end align-items-center col-md-12 col-lg-12">
                                    {{-- <button type="button" class="btn btn-secondary mr-2">Close</button> --}}
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i>&nbsp;Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">

                        <!-- Table  -->
                        <h5>Item list</h5>
                        <div class="table-responsive">
                            <table id="myTable"
                                class="table expandable-table custom-table table-bordered table-striped mt-c-15"
                                data-show-columns="true" data-search="true" data-show-toggle="true" data-pagination="true"
                                data-resizable="true">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 3%">S.N.</th>
                                        <th style="width: 25%">Item Name</th>
                                        <th style="width: 15%">Billing Modes</th>
                                        <th style="width: 12%">Fraction Category</th>
                                        <th style="width: 8%">Created At</th>
                                        <th style="width: 8%">Created By</th>
                                        <th style="width: 8%">Updated At</th>
                                        <th style="width: 8%">Updated By</th>
                                        <th style="width: 8%">Remarks</th>
                                        <th style="width: 5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 0; @endphp
                                    @foreach ($itemGroup as $items)
                                        @php $categories = $items->first()->category ?? []; @endphp
                                        <tr>
                                            <td style="width: 3%">{{ ++$count }}</td>
                                            <td style="width: 25%;" class="scrollable">
                                                <div>
                                                    <div>{{ $items->first()->fldbillitem }}</div>
                                                </div>
                                            </td>
                                            <td style="width: 15%" class="scrollable">
                                                <div>
                                                    @foreach ($items as $item)
                                                        <div>{{ $item->fldgroup }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td style="width: 12%" class="scrollable">
                                                <div>
                                                    @foreach ($categories as $category)
                                                        <div>{{ $category }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td style="width: 8%">
                                                {{ $items->first()->created_at ? $items->first()->created_at->diffForHumans() : '' }}
                                            </td>
                                            <td style="width: 8%">{{ $items->first()->flduserid }}</td>
                                            <td style="width: 8%">
                                                {{ $items->first()->updated_at ? $items->first()->updated_at->diffForHumans() : '' }}
                                            </td>
                                            <td style="width: 8%">{{ $items->first()->updated_by }}</td>
                                            <td style="width: 8%">{{ $items->first()->flddescription }}</td>
                                            <td style="width: 5%">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                        type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a onclick="view(this)" class="dropdown-item bed_exchange"
                                                            href="javascript:void(0)"
                                                            data-url="{{ route('itemmaster.show', base64_encode($items->first()->fldbillitem ?? '')) }}">
                                                            <i class="fa fa-eye"></i>&nbsp;View
                                                        </a>
                                                        <a onclick="loader()" class="dropdown-item bed_exchange"
                                                            href="{{ route('itemmaster.edit', base64_encode($items->first()->fldbillitem ?? '')) }}">
                                                            <i class="fa fa-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <a onclick="loader()" class="dropdown-item bed_exchange"
                                                            href="{{ route('itemmaster.status', ['encrypt_fldbillitem' => base64_encode($items->first()->fldbillitem ?? '')]) }}">
                                                            <iclass="fa fa-check"></i>&nbsp;Active/Inactive
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade preview-modal" id="editBox" tabindex="-1" role="dialog" aria-labelledby="ItemModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>

    <!-- View Modal  -->
    {{-- @foreach ($itemGroup as $items)
        @php $categories = $items->first()->category ?? []; @endphp
        <div class="modal fade preview-modal" id="view-modal-{{ $items->first()->fldid }}" tabindex="-1" role="dialog"
            aria-labelledby="ItemModal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Item Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="preview-modal-content">
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <h6 class="mb-2">Category: {{ $items->first()->flditemtype }}</h6>
                                @if ($items->first()->fldstatus == 'Active')
                                    <div class=""><i class="fa fa-circle"
                                            style="color: green"></i>&nbsp;Active</div>
                                @else
                                    <div class=""><i class="fa fa-circle"
                                            style="color: red"></i>&nbsp;Inactive</div>
                                @endif
                            </div>
                            <table style="width: 100%">
                                <tr>
                                    <td class="title">Item Name</td>
                                    <td class="desc">{{ $items->first()->fldbillitem }}</td>
                                </tr>
                                <tr>
                                    <td class="title">Account Ledger</td>
                                    <td class="desc">{{ $items->first()->account_ledger }}</td>
                                </tr>
                                <tr>
                                    <td class="title">Department</td>
                                    <td class="desc">{{ $items->first()->fldreport }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="title">target</td>
                                    <td class="desc">{{ $items->first()->fldtarget }}</td>
                                </tr>
                                <tr>
                                    <td class="title">Tax Type:</td>
                                    <td class="desc"></td>
                                </tr>
                            </table>
                            <hr>
                            <h6 class="mb-2">Price Setups</h6>
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 20%">Billing Mode</th>
                                        <th style="width: 50%">Item Name</th>
                                        <th style="width: 15%">Price</th>
                                        <th style="width: 15%">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    @foreach ($items as $item)
                                        <tr>
                                            <td style="width: 20%">{{ $item->fldgroup }}</td>
                                            <td style="width: 50%">{{ $item->flditemname }}</td>
                                            <td style="width: 15%">{{ $item->flditemcost }}</td>
                                            <td style="width: 15%">{{ $item->fldstatus }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <h6>Fraction Category</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @foreach ($categories as $category)
                                                            <div>{{ $category }}</div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 50%">
                                        <table style="width: 100%">
                                            <tr>
                                                <td colspan="2">
                                                    <h6>Description</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{!! $items->first()->flddescription !!}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <h6>Editable</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-row">
                                                        <div class="col-3">
                                                            <input class="magic-checkbox" type="checkbox" name="rate"
                                                                value="1" {{ $items->first()->rate ? 'checked' : '' }}
                                                                disabled>
                                                            <label for="">Rate</label>
                                                        </div>
                                                        <div class="col-4">
                                                            <input class="magic-checkbox" type="checkbox" name="discount"
                                                                value="1"
                                                                {{ $items->first()->discount ? 'checked' : '' }}
                                                                disabled>
                                                            <label for="">Discount</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}
@endsection

@push('after-script')
    <script>
        $(function() {
            $('#myTable').bootstrapTable()
        })

        $(".modal").on("hidden.bs.modal", function() {
            $(".modal-content").html("");
        });

        function view(currentElement) {
            var url = $(currentElement).data('url');
            $('.modal-content').load(url, function() {
                $('#editBox').modal({
                    show: true
                });
            })
        };

        function loader() {
            $('.loader-ajax-start-stop-container').show();
        }
    </script>
@endpush
