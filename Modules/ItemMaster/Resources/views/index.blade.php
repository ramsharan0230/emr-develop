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
                                                @foreach ($targets as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $target == $key ? 'selected' : '' }}>
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
                                        <th style="width: 8%">Remarks</th>
                                        <th style="width: 8%">Created At</th>
                                        <th style="width: 8%">Created By</th>
                                        <th style="width: 8%">Updated At</th>
                                        <th style="width: 8%">Updated By</th>
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
                                            <td style="width: 8%">{{ $items->first()->flddescription }}</td>
                                            <td style="width: 8%">
                                                {{ $items->first()->created_at ? $items->first()->created_at->diffForHumans() : '' }}
                                            </td>
                                            <td style="width: 8%">{{ $items->first()->createdUser->username ?? '' }}</td>
                                            <td style="width: 8%">
                                                {{ $items->first()->updated_at ? $items->first()->updated_at->diffForHumans() : '' }}
                                            </td>
                                            <td style="width: 8%">{{ $items->first()->updatedUser->username ?? '' }}</td>
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
                                                            data-url="{{ route('itemmaster.show', $items->first()->fldbillitem_id) }}">
                                                            <i class="fa fa-eye"></i>&nbsp;View
                                                        </a>
                                                        <a onclick="loader()" class="dropdown-item bed_exchange"
                                                            href="{{ route('itemmaster.edit', $items->first()->fldbillitem_id) }}">
                                                            <i class="fa fa-edit"></i>&nbsp;Edit
                                                        </a>
                                                        {{-- <a onclick="loader()" class="dropdown-item bed_exchange"
                                                            href="{{ route('itemmaster.status', ['fldbillitem_id' => $items->first()->fldbillitem_id]) }}">
                                                            <iclass="fa fa-check"></i>&nbsp;Active/Inactive
                                                        </a> --}}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
