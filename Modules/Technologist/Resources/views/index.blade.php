@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        .select-editable {position:relative; background-color:white; border:solid grey 1px;  width:125px; height:25px;}
        .select-editable select {position:absolute; top:0px; left:0px; font-size:14px; border:none; width:120px; margin:0;}
        .select-editable input {position:absolute; top:0px; left:0px; width:100px; padding:1px; font-size:12px; border:none;}
        .select-editable select:focus, .select-editable input:focus {outline:none;}
        .select_td {
            background: #f2f2f2;
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
                        <h4 class="card-title">
                            Diagnostic Master / Lab Diagnostics
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-row">
                        <div class="col-lg-5 col-md-12">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <select class="form-control select-input-clinical-exam"
                                            name="technologist_filter_categories"
                                            id="technologist_filter_categories"
                                            url="{{ route('technologist.sort.category') }}">
                                        <option value="">---Select Category---</option>
                                        @if($get_variable_categories)
                                            @foreach($get_variable_categories as $category)
                                                <option
                                                    value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="myInput" onkeyup="myFunctionSearchGroup()" placeholder="Search ...">
                                </div>
                            </div>
                            <div class="res-table" style="max-height: 737px;">
                                <table class="table table-bordered table-hovered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name <span id="arrange_btn" class="float-right" style="display: none;"><button class="btn btn-primary">Save arrangement</button></span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-scroll-technologist" class="get-selected-test-data">
                                        @if($get_test)
                                        @foreach($get_test as $test)
                                        <tr rel="{{ $test->col }}">
                                            <td class="fixed-side">{{ $test->col }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{--<nav aria-label="...">
                                    <ul class="pagination">
                                        <li class="page-item disabled">
                                            <a class="page-link"
                                            href="#"
                                            tabindex="-1"
                                            aria-disabled="true">Previous</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item active" aria-current="page">
                                            <a class="page-link" href="#"
                                            >2 <span class="sr-only">(current)</span></a
                                            >
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>--}}
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <form action="" class="form-horizontal">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Test Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="" id="technologist_test_name">
                                    </div>
                                    <div class="col-sm-2">
                                        {{-- data-toggle="modal" data-target="#test_name_update" --}}
                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" id="edit_technologist_test_name">
                                        <i class="fa fa-edit"></i>&nbsp;Edit</a>
                                    </div>
                                </div>

                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Order</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="technologist_order"
                                               id="technologist_order">
                                    </div>
                                    {{--                                    <div class="col-sm-2">--}}
                                    {{--                                        --}}{{-- data-toggle="modal" data-target="#test_name_update" --}}
                                    {{--                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" id="edit_technologist_test_name">--}}
                                    {{--                                            <i class="fa fa-edit"></i>&nbsp;Edit</a>--}}
                                    {{--                                    </div>--}}
                                </div>

                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Categories</label>
                                    <div class="col-sm-7">
                                        <select class="form-control select-input-clinical-exam" name="technologist_categories" id="get_technologist_categories">
                                            <option value="">---Select Category---</option>
                                            @if($get_variable_categories)
                                            @foreach($get_variable_categories as $category)
                                            <option value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#category_technologist">
                                        <i class="fa fa-plus"></i>&nbsp;Add
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Sys Constant</label>
                                    <div class="col-sm-7">
                                        <select class="form-control select-input-clinical-exam" name="technologist_constant" id="get_technologist_constant">
                                            <option value="">---Select Constant---</option>
                                            @if($get_variable_constant)
                                            @foreach($get_variable_constant as $constant)
                                            <option value="{{ $constant->col }}">{{ $constant->col }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#sys_contant_technologist">
                                        <i class="fa fa-plus"></i>&nbsp;Add</a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Specimen</label>
                                    <div class="col-sm-7">
                                        <select class="form-control select-input-clinical-exam" name="technologist_specimen" id="get_technologist_specimen">
                                            <option value="">---Select Specimen---</option>
                                            @if($get_variable_specimen)
                                            @foreach($get_variable_specimen as $specimen)
                                            <option value="{{ $specimen->col }}">{{ $specimen->col }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#specimen_technologist">
                                        <i class="fa fa-plus"></i>&nbsp;Add</a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Collection</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="technologist_collection">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Vial</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-input-clinical-exam" name="technologist_vial" id="technologist_vial">
                                            <option value="">---Select Vial---</option>
                                            <option value="Blue-Top Tube">Blue-Top Tube</option>
                                            <option value="Lavender-Top Tube">Lavender-Top Tube</option>
                                            <option value="Red-Top Tube">Red-Top Tube</option>
                                            <option value="Navy Blue-Top Tube">Navy Blue-Top Tube</option>
                                            <option value="Serum Separator Tube (SST®)">Serum Separator Tube (SST®)</option>
                                            <option value="Green-Top Tube">Green-Top Tube</option>
                                            <option value="Grey-Top Tube">Grey-Top Tube</option>
                                            <option value="Yellow-Top Tube">Yellow-Top Tube</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Sensitivity</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="technologist_sensitivity">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Specificity</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control input-clinicalexam" id="technologist_specificity">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Data Type</label>
                                    <div class="col-sm-5">
                                        <select class="form-control select-clinical-exam" name="technologist_datatype" id="onChangeDataType">
                                            <option value="">---select---</option>
                                            <option value="Qualitative">Qualitative</option>
                                            <option value="Quantitative">Quantitative</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-4">
                                        <button type="button" class="btn btn-sm-in btn-primary" data-dismiss="modal"><i class="ri-information-fill"></i> Comments</button>
                                    </div> --}}
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Input mode</label>
                                    <div class="col-sm-5">
                                        <select class="select-clinical-exam form-control" name="technologist_input_mode" id="getChangeDataType">
                                            <option value="No Selection" selected>---No Selection---</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 ">
                                        <a href="javascript:;" data-backdrop="static" data-keyboard="false" class="btn btn-sm-in btn-primary dynamic-option-btn" data-toggle="modal">
                                            <i class="ri-edit-2-fill"></i> Option
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <div class="col-sm-3">
                                         <p class="border with-outline">Outliers</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="border with-outline">Ref Range+</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="border with-outline">
                                        X Ref Range</p>
                                    </div>
                                    <div class="col-sm-3">
                                       <input type="number" class="form-control  input-clinicalexam" id="technologist_critical">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control textarea-clinical-exam" id="technologist_description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Footnote</label>
                                    <textarea class="form-control textarea-clinical-exam" id="technologist_comment"></textarea>
                                </div>
                                <div class="form-group form-row float-right">
                                    <button type="button" class="btn btn-action btn-primary" url="{{ route('technologist.insert') }}" id="technologist_save"><i class="fas fa-check"></i> Save</button>&nbsp;
                                    <button type="button" class="btn btn-action btn-primary" url="{{ route('technologist.update') }}" id="technologist_update"><i class="ri-edit-2-fill"></i> Update</button>&nbsp;
                                    <button type="button" class="btn btn-action btn-danger" url="{{ route('technologist.delete') }}" id="technologist_delete"><i class="ri-delete-bin-5-fill"></i> Delete</button>&nbsp;
                                    <div id="confirmation_dialog_technologist"></div>
                                </div>
                                {{-- <div class="form-group form-row float-right">
                                    <button type="button" class="btn btn-action btn-primary  mr-1" id="technologist_add_reset"> <i class="fas fa-plus"></i>&nbsp;ADD</button>
                                    <button type="button" class="btn btn-action btn-primary" id="technologist_save" style="display: none;" url="{{ route('technologist.insert') }}">
                                        <i class="fas fa-check"></i>&nbsp;&nbsp;Save
                                    </button>

                                    <button type="button" class="btn btn-action btn-primary" id="technologist_edit_reset">
                                        <i class="ri-edit-2-fill"></i> Edit
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-action btn-primary" id="technologist_update" style="display: none;" url="{{ route('technologist.update') }}"><i class="ri-edit-2-fill"></i> Update</button>
                                    <button type="button" class="btn btn-action btn-danger" id="technologist_delete" url="{{ route('technologist.delete') }}"><i class="ri-delete-bin-5-fill"></i> Delete</button>&nbsp;
                                    <div id="confirmation_dialog_technologist"></div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('technologist::modal.test_name_update')
@include('technologist::modal.category')
@include('technologist::modal.sys_contant')
@include('technologist::modal.specimen')
@include('technologist::modal.dynamic-modal.common-modal-box-one')
@include('technologist::modal.dynamic-modal.clinical-scale-box')

@include('technologist::modal.dynamic-modal.custom-components-box')
@include('technologist::modal.dynamic-modal.fixed-components-box')
@include('technologist::modal.dynamic-modal.text-addition-box')

@include('technologist::modal.dynamic-modal.visual-input-box')
@include('technologist::modal.dynamic-modal.common-get-function')
@include('technologist::modal.dynamic-modal.quantitative-modal-box')
@endsection

@push('after-script')
<!-- script here -->
<script src="{{ asset('js/technologist.js') }}"></script>
<script>
    function myFunctionSearchGroup() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-scroll-technologist");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    $(document).ready(function () {
        $('.category-box-list').sortable({
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });
                $('#technologist_save_arrangements').show();
                // saveNewCategoryPositions();
            }
        });


        $('.get-selected-test-data').sortable({
            update: function (event, ui) {
                var cat = $('#technologist_filter_categories').val();
                if (cat == '') {
                    showAlert('Please select category', 'error');
                    return false;
                }
                $(this).children().each(function (index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });
                $('#arrange_btn').show();
                // saveTestsPositions();
            }
        });

    });

    $('#arrange_btn').click( function () {
        saveTestsPositions();
    });

    $('#technologist_save_arrangements').click( function () {
        saveNewCategoryPositions();
    });

    function saveNewCategoryPositions() {
        var positions = [];
        $('.updated').each(function () {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position'), $(this).attr('data-class')]);
            $(this).removeClass('updated');
        });

        $.ajax({
            url: '{{ route('order.category.technologist') }}',
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                update: 1,
                positions: positions
            }, success: function (response) {
                if(response.message){
                    showAlert(response.message);
                }
                $.get("categorie/variables/list", function (data) {
                    // console.log(data);
                    // return false;
                    $('#category_box').empty();
                    $("#get_technologist_categories").append("<option value=''>---Select---</option>");
                    $.each(data, function (index, getVariables) {
                        $("#get_technologist_categories").append('<option value="' + getVariables.flclass + '">' + getVariables.flclass + '</option>');
                        $("#category_box").append('<li data-class="'+ getVariables.flclass +'" class="list-group-item"><input type="radio" name="selected_category_technologist" id="category-variable-' + getVariables.fldid + '" value="' + getVariables.fldid + '">&nbsp;&nbsp;<label for="category-variable-' + getVariables.fldid + '">'+(getVariables.order_by ? (getVariables.order_by+'-') : '' )+ getVariables.flclass + '</label></li>');
                    });
                });
            }
        });
    }

    function saveTestsPositions() {
        var positions = [];
        $('.updated').each(function () {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position'), $(this).attr('data-class')]);
            $(this).removeClass('updated');
        });
        var category = $('#technologist_filter_categories').val();
        $.ajax({
            url: '{{ route('technologist.order.test') }}',
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                update: 1,
                positions: positions,
                category:category,
            }, success: function (response) {
                if (response.html) {
                    $('#table-scroll-technologist').empty().append(response.html);
                }
                if (response.html==="") {
                    $('#table-scroll-technologist').empty().append('<tr><td>No data available</td></tr>');
                }
                if(response.message){
                    showAlert(response.message);
                }
                if(response.error){
                    showAlert(response.error,'error');
                }
            }
        });
    }

</script>
@endpush
