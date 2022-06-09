@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        #table_filter,
        #table_filter label {
            width: 100%;
        }
        #table_filter input {
            width: 100%;
            padding: 5px 40px 5px 15px;
            margin-left: 0;
            border-radius: 10px;
            border: none;
            background: var(--search-bar-color);
        }
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
            white-space: nowrap;
            overflow-x: hidden;
        }
        .dataTables_scrollBody {
            overflow-x: hidden !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Inventory Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>

             <div class="col-md-12" id="myDIV">
                <form id="item_filter_data">
                    @csrf
                        <div class="col-md-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height pt-2 row">
                                <div class="iq-card-body">
                                    <div class="form-group form-row">
                                                                                                                   
                                        <div class="col-md-6 border-right">
                                            <div class="col-md-12 d-flex flex-row align-items-start row">
                                                <div class="col-md-2">
                                                    <h6>Category</h6>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="form-group form-row">                                                    
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="brand" id="generic" value="generic" onclick="filterData()" checked>
                                                            <label class="form-check-label" for="generic">Generic</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="brand" id="brand" value="brand" onclick="filterData()">
                                                            <label class="form-check-label" for="brand">Brand</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="medType" id="med" value="med" onclick="filterData()" checked>
                                                            <label class="form-check-label" for="med">Medicine</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="medType" id="surg" value="surg" onclick="filterData()">
                                                            <label class="form-check-label" for="surg">Surgical</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="medType" id="extra" value="extra" onclick="filterData()">
                                                            <label class="form-check-label" for="extra">Extra</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex flex-row align-items-center mt-3 row">
                                                <div class="col-md-2">
                                                    <h6>Type</h6>
                                                </div>
                                                <div class="d-flex flex-row align-items-center col-md-10">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="buyType" id="purchase" value="purchase" checked>
                                                        <label class="form-check-label" for="purchase">Purchase</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="buyType" id="dispensing" value="dispensing">
                                                        <label class="form-check-label" for="dispensing">Dispensing</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="buyType" id="used" value="used">
                                                        <label class="form-check-label" for="used">Consumed</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="buyType" id="transfer" value="transfer">
                                                        <label class="form-check-label" for="transfer">Transfer</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 d-flex flex-row align-items-center mt-4 row">
                                                <div class="col-md-2">
                                                    <h6>Medicine</h6>
                                                </div>
                                                <div class="d-flex flex-row align-items-center col-md-10">
                                                    <input type="hidden" name="selectedItem" id="selectedItem">
                                                    <!-- <div class="form-group"> -->
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="itemRadio" id="select_item" value="select_item" checked>
                                                            <label class="form-check-label" for="select_item">Select Item</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="itemRadio" id="all_items" value="all_items">
                                                            <label class="form-check-label" for="all_items">All Item</label>
                                                        </div>
                                                    <!-- </div>  -->
                                                </div>
                                            </div>

                                            <div class="iq-card-body mt-3">                                                
                                                <div class="form-group" style="max-height:600px;">
                                                    <table id="table">
                                                        <thead>
                                                            <th>Medicines</th>
                                                        </thead>
                                                        <tbody id="item-listing-table" class="table">
                                                        @if($medicines)
                                                            @forelse($medicines as $medicine)
                                                                <tr>
                                                                    <td class="p-2">
                                                                        <div class="form-check form-check-inline" style="width: 100%;">
                                                                            <input class="form-check-input" type="radio" name="item_name" id="{{ $loop->iteration }}-item-id" value="{{ $medicine->flddrug }}">
                                                                            <label class="form-check-label" for="{{ $loop->iteration }}-item-id" style="width: 100%;">{{ $medicine->flddrug }}</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                            @endforelse
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>    

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="form-group form-row justify-content-between">
                                                    <label for="" class="col-md-3">From:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                                        <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                    <div class="form-group form-row justify-content-between">
                                                    <label for="" class="col-md-3">To:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                                        <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group form-row justify-content-between">
                                                    <label for="" class="col-md-3">Department:</label>
                                                    <div class="col-md-8">
                                                        <select name="comp" id="comp" class="form-control department">
                                                            <option value="%">%</option>
                                                            @foreach($hospital_departments as $dept)
                                                                <option value="{{ $dept->fldcomp }}">{{ $dept->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                                                                                                
                                            <div class="col-md-12">
                                                <div class="form-group form-row justify-content-between">
                                                    <label for="" class="col-md-3">Supplier:</label>
                                                    <div class="col-md-8">
                                                        <select name="supplier" id="supplier" class="form-control mt-2 select2">
                                                            <option value="%">%</option>
                                                            @if($supplierName)
                                                                @forelse($supplierName as $supplier)
                                                                    <option value="{{ $supplier->fldsuppname }}" class="form-control">{{ $supplier->fldsuppname }}</option>
                                                                @empty
                                                                @endforelse
                
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group float-right">
                                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getInventoryData()"><i class="fas fa-filter"></i>&nbsp;
                                                        Filter</a>&nbsp;                                                    
                                                </div>
                                            </div>                                            
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                </form>
            </div>

            <div class="col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs align-items-center justify-content-between" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" type="button" class="btn btn-primary float-right" onclick="exportGeneratedReport()"><i class="fa fa-code"></i>&nbsp;Export</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">

                                <div class="append-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        var datatable;
        $(document).ready(function () {
            $(document).on('click', '.ajax-pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page-ajax=')[1];
                getInventoryData(page);
            });

            datatable = $('#table').DataTable({
                "ordering": false,
                "info":     false,
                "lengthChange": false,
                "pageLength": 100,
                "scrollY": "190px",
                "scrollCollapse": true,
                "language": { 
                    "search": '',
                    "searchPlaceholder": "Type here to search...",
                    "paginate": {
                        "previous": "<<",
                        "next": ">>"
                    }
                },
            });

            $("input[name='itemRadio']").click(function() {
                if($('input[name="itemRadio"]:checked').val() == "all_items") {
                    $("#item-listing-table input").prop('checked', false);
                    $("#item-listing-table input, #item-listing-table label").prop('disabled', true).addClass('disabled');
                } else {
                    $("#item-listing-table input, #item-listing-table label").prop('disabled', false).removeClass('disabled');
                }
            });

            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_from_date').val(BS2AD($('#from_date').val()));
                }
            });
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_to_date').val(BS2AD($('#to_date').val()));
                }
            });
        });

        function getInventoryData(page = 1) {
            if(!$('input[name="itemRadio"]').is(":checked")) {
                showAlert('Please select items type','error');
                return;
            }
            if($('input[name="itemRadio"]:checked').val() == "select_item" && !$('input[name="item_name"]').is(":checked")) {
                showAlert('Please select items','error');
                return;
            }

            $.ajax({
                url: "{{ route('inventory.list.data') }}" + "?page-ajax=" + page,
                method: 'post',
                data: $("#item_filter_data").serialize(),
                success: function (data) {
                    $('.append-table').empty().append(data);
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }

        function filterData() {
            const type = $('input[name="medType"]:checked').val();
            const brand = $('input[name="brand"]:checked').val();

            $.ajax({
                url: "{{ route('inventory.report.filter.data') }}",
                method: 'GET',
                data: {type: type, brand: brand },
                success: function (data) {
                    if(data.error){
                        showAlert(data.error,'errror');
                    }

                    datatable.destroy();
                    $('#item-listing-table').empty().append(data.html);

                    datatable = $('#table').DataTable({
                        "ordering": false,
                        "info":     false,
                        "lengthChange": false,
                        "pageLength": 100,
                        "scrollY": "190px",
                        "scrollCollapse": true,
                        "language": { 
                            "search": '',
                            "searchPlaceholder": "Type here to search...",
                            "paginate": {
                                "previous": "<<",
                                "next": ">>"
                            }
                        },
                    });

                    if($('input[name="itemRadio"]:checked').val() == "all_items") {
                        $("#item-listing-table input").prop('checked', false);
                        $("#item-listing-table input, #item-listing-table label").prop('disabled', true).addClass('disabled');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }

        function exportGeneratedReport() {
            window.open("{{ route('inventory.report.generate') }}?" + $("#item_filter_data").serialize(), '_blank');
        }

        // function exportDetailReport(){
        //     window.open("{{ route('inventory.report.excel.generate') }}?" + $("#item_filter_data").serialize(), '_blank');
        // }

    </script>
@endpush



