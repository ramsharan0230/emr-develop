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
                            Diagnostic Master / Radio Diagnostics
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-row">
                        <div class="col-lg-5 col-md-12">
                            @php $radios = \App\Utils\Diagnosishelpers::getAlltheRadio(); @endphp
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="myInput" onkeyup="myFunctionSearchGroup()" placeholder="Search ...">
                                </div>
                            </div>
                            <div class="res-table" style="max-height: 737px;">
                                <table class="table table-bordered table-hovered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-scroll-technologist" class="get-selected-test-data">
                                        @if($radios)
                                        @forelse($radios as $radio)
                                        <tr rel="{{ $radio->fldexamid }}">
                                            <td class="fixed-side">{{ $radio->fldexamid }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- <form action="{{ route('radiodiagnostic.add') }}" method="post" enctype="multipart/form-data">
                                <div class="iq-search-bar custom-search">
                                </div>
                                <div class="dietarytable">
                                    @include('diagnosis::layouts.includes.radiologylisting')
                                </div>
                            </form> --}}
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <form action="" class="form-horizontal">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3 ">Test Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="fldexamid" value="{{ old('fldexamid') }}" class="form-control" placeholder="" id="technologist_test_name" required>
                                    </div>
                                    <div class="col-sm-2">
                                        {{-- data-toggle="modal" data-target="#test_name_update" --}}
                                        <a href="javascript:;" class="btn btn-sm-in btn-primary" id="edit_radiology_test_name">
                                        <i class="fa fa-edit"></i>&nbsp;Edit</a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Category</label>
                                    <div class="col-sm-7">
                                        @php
                                        $pathocategorytype = $categorytype;
                                        $pathocategories = \App\Utils\Diagnosishelpers::getPathoCategory($pathocategorytype);
                                        @endphp
                                        <select name="fldcategory" id="fldcategory" class="form-select-dietary form-control select2categoryname" required>
                                            <option value=""></option>
                                            @forelse($pathocategories as $pathocategory)
                                            <option value="{{ $pathocategory->flclass }}" data-id="{{ $pathocategory->fldid }}" {{ (old('fldcategory') && old('fldcategory') == $pathocategory->flclass) ? 'selected' : ''}}>{{ $pathocategory->flclass }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:void(0)" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#category_modal">  <i class="fa fa-plus"></i>&nbsp;Add</a>
                                    </div>
                                </div>
                                {{-- <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Sys Constant</label>
                                    <div class="col-sm-7">
                                        @php
                                        $sysconstcategory = $categorytype;
                                        $sysconsts = \App\Utils\Diagnosishelpers::getallSysConstant($sysconstcategory);
                                        @endphp
                                        <select name="fldsysconst" class="form-select-dietary form-control select2sysconstant">
                                            <option value=""></option>
                                            @forelse($sysconsts as $sysconst)
                                            <option value="{{ $sysconst->fldsysconst }}" data-sysconstant="{{ $sysconst->fldsysconst }}" {{ (old('fldsysconst') && old('fldsysconst') == $sysconst->fldsysconst) ? 'selected' : ''}}>{{ $sysconst->fldsysconst }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:void(0)" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#sysconstant_modal">
                                            <i class="fa fa-plus"></i>&nbsp;Add
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Sensitivity</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="fldsensitivity" value="{{ old('fldsensitivity') }}" step="any" class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Specificity</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="fldspecificity" value="{{ old('fldspecificity') }}" class="form-control" placeholder="0">
                                    </div>
                                </div> --}}
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Data Type</label>
                                    <div class="col-sm-5 col-lg-6">
                                        <select name="fldtype" class="select-4 form-control" id="onChangeDataType" required>
                                            <option value="">---select---</option>
                                            <option value="Qualitative" {{ (old('fldtype') && old('fldtype') == 'Qualitative') ? 'selected' : ''}}>Qualitative</option>
                                            <option value="Quantitative" {{ (old('fldtype') && old('fldtype') == 'Quantitative') ? 'selected' : ''}}>Quantitative</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-4 col-lg-3">
                                        <button class="btn btn-sm-in btn-primary">
                                            <i class="fa fa-list"></i>&nbsp;comments
                                        </button>
                                    </div> --}}
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-3">Input mode</label>
                                    <div class="col-sm-6">
                                        <select name="fldoption" class="select-4 form-control" id="getChangeDataType">
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="javascript:;" data-backdrop="static" data-keyboard="false" class="btn btn-sm-in btn-primary dynamic-option-btn" data-toggle="modal">
                                            <i class="ri-information-fill"></i>&nbsp;Options
                                        </a>
                                    </div>
                                </div>
                                {{-- <div class="form-group form-row align-items-center">
                                    <p class="border col-sm-3 with-outline">Outliers</p>&nbsp;
                                    <p class="border col-sm-3 with-outline">Ref Range+</p>
                                    <div class="col-sm-2">
                                        <input type="number" step="any" name="fldcritical" value="{{ old('fldcritical') }}" class="form-control" placeholder="0">
                                    </div>
                                    <p class="border col-sm-3 with-outline">
                                        X Ref Range
                                    </p>
                                </div> --}}
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="flddetail" id="flddetail" class="form-control">{!! old('flddetail') !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Footnote</label>
                                    <textarea name="fldcomment" id="fldcomment" class="form-control">{!! old('fldcomment') !!}</textarea>
                                </div>
                                <div class="form-group form-row float-right">
                                    <button type="button" class="btn btn-action btn-primary" id="radio_save"><i class="fas fa-check"></i> Save</button>&nbsp;
                                    <button type="button" class="btn btn-action btn-primary" id="radio_update"><i class="ri-edit-2-fill"></i> Update</button>&nbsp;
                                    <button type="button" class="btn btn-action btn-danger" id="radio_delete"><i class="ri-delete-bin-5-fill"></i> Delete</button>&nbsp;
                                    <div id="confirmation_dialog_technologist"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exam_name_update">
    <div class="modal-dialog ">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Update</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
         <div class="form-group form-row">
          <label class="col-2">Exam Name</label>

          <div class="col-md-10">
            <input type="text" id="exam_name_new_value" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-action btn-secondary" data-dismiss="modal">Close</button>
          <a href="javascript:;" class="btn btn-action btn-primary float-right btn-sm" id="update_technologist_test_name" url="{{ route('radiodiagnostic.examName.update') }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
      </div>
    </div>
  </div>
</div>

@include('diagnosis::layouts.modal.category')
@include('diagnosis::layouts.modal.common-modal-box-one')
@include('diagnosis::layouts.modal.clinical-scale-box')
@include('diagnosis::layouts.modal.custom-components-box')
@include('technologist::modal.dynamic-modal.fixed-components-box')
@include('diagnosis::layouts.modal.visual-input-box')
@include('diagnosis::layouts.modal.common-get-function')

<div class="modal fade" id="quantitative-modal-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Quantitative Test Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row align-items-center">
                                <label class=""></label>
                            </div>

                            <input type="hidden" id="get_previous_test_fldid" value="">

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Method</label>
                                <div class="col-md-7">
                                    <select class="form-control" name="selected_quantitative_method" id="get_quantitative_method">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                   <a href="javascript:;" data-toggle="modal" class="btn btn-primary" data-target="#technologist_quantitative_method_variable"><i class="fa fa-plus"></i></a>
                                </div>
                                @include('technologist::modal.technologist_quantitative_method_variable')
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Valide Range</label>
                                <div class="col-md-3">
                                    <input type="number" id="quantitative_valide_range" class="form-control">
                                </div>
                                <label class="col-md-1">To</label>
                                <div class="col-md-3">
                                    <input type="number" id="quantitative_matric_unit" class="form-control">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Sensitivity</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_sensitivity">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Specificity</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_specificity">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Age Group</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="quantitative_age_group" id="quantitative_age_group">
                                        <option value="">---Select Age Group---</option>
                                        <option value="Neonate">Neonate</option>
                                        <option value="Infant">Infant</option>
                                        <option value="Toddler">Toddler</option>
                                        <option value="Children">Children</option>
                                        <option value="Adolescent">Adolescent</option>
                                        <option value="Adult">Adult</option>
                                        <option value="Elderly">Elderly</option>
                                        <option value="All Age">All Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Gender</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="quantitative_gender" id="quantitative_gender">
                                        <option value="">---Select Gender---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Both Sex">Both Sex</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Lower Limit</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_lower" value="0">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Upper Limit</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_upper" value="0">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Normal Value</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quantitative_normal" value="0">
                                </div>
                            </div>

                            <div class="form-group form-row align-items-center">
                                <label class="col-md-3">Unit</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="quantitative_unit">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-row float-right mt-2">
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-primary insert-quantitative-test-para" url="{{ route('insert.quantitative.test.para') }}"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-primary edit-quantitative-test-para" url="{{ route('update.quantitative.test.para') }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</button>
                                        </div>&nbsp;
                                        <div class="box__btn__clinical">
                                            <button type="button" class="btn btn-action btn-danger delete-quantitative-test-para" url="{{ route('delete.quantitative.test.para') }}"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                                        </div>&nbsp;
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="res-table">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>Gender</th>
                                                    <th>AgeGroup</th>
                                                    <th>Mean</th>
                                                    <th>Lower</th>
                                                    <th>Upper</th>
                                                    <th>Unit</th>
                                                    <th>Method</th>
                                                    <th>Sens</th>
                                                    <th>Spec</th>
                                                </tr>
                                            </thead>
                                            <tbody class="display-quantitative-test-para">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="text-addition-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Options</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <input type="hidden" id="text-addition-fldid" name="fldid">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Test</label>
                                </div>
                                <div class="col-4">
                                    <label class="common-modal-first-test"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Sub Test</label>
                                </div>
                                <div class="col-4">
                                    <label class="common-modal-first-sub-test"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Option Type</label>
                                </div>
                                <div class="col-4">
                                    <label class="common-modal-first-type"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class=""></label>
                                </div>
                                <div class="col-9">
                                    <textarea name="text_addition" class="form-control display-text-addition" id="text_addition"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary insert-text-addition" url="{{ route("radiodiagnostic.insert.text-addition") }}"><i class="fas fa-edit"></i> Update</button>
                                </div>&nbsp;
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger delete-text-addition" url="{{ route('radiodiagnostic.delete.text-addition') }}"><i class="fas fa-trash"></i> Delete</button>
                                </div>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="delete_form" method="POST">
    @csrf
    @method('delete')
</form>
@endsection

@push('after-script')
<script src="{{ asset('js/radiodiagnostic.js') }}"></script>
<script>
function select2loading() {
    setTimeout(function () {
        $('.select2categoryname').select2({
            placeholder: 'select category'
        });

        $('.select2sysconstant').select2({
            placeholder: 'select sys constant'
        });
    }, 4000);
}

$('#categoryaddaddbutton').click(function () {
    var categoryname = $('#categorynamefield').val();
    var fldcategory = $('#fldcategoryfield').val();
    if (categoryname != '') {
        $.ajax({
            type: 'post',
            url: '{{ route('addpathocategory') }}',
            dataType: 'json',
            data: {
                '_token': '{{ csrf_token() }}',
                'flclass': categoryname,
                'fldcategory': fldcategory
            },
            success: function (res) {

                showAlert(res.message);
                if (res.message == 'Category added successfully.') {
                    $('#categorynamefield').val('');
                    var deleteroutename = "{{ url('/diagnosis/deletecategory') }}/" + res.fldid;
                    $('#categorylistingmodal').append('<li class="category-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="category_item" data-href="' + deleteroutename + '" data-id="' + res.fldid + '">' + res.flclass + '</li>');
                    $('.select2categoryname').append('<option value="' + res.flclass + '" data-id="' + res.fldid + '">' + res.flclass + '</option>');
                    select2loading();
                }

            }
        });
    } else {
        alert('Category Name is required');
    }
});

// adding sysconstant

$('#sysconstantaddbutton').click(function () {
    var fldsysconst = $('#sysconstantnamefield').val();
    var fldcategory = $('#fldcategoryfieldsysconstant').val();

    if (fldsysconst != '') {
        $.ajax({
            type: 'post',
            url: '{{ route('addsysconstant') }}',
            dataType: 'json',
            data: {
                '_token': '{{ csrf_token() }}',
                'fldsysconst': fldsysconst,
                'fldcategory': fldcategory
            },
            success: function (res) {
                showAlert(res.message);
                if (res.message = 'Sys Constant added successfully.') {
                    $('#sysconstantnamefield').val('');
                    var deleteroutenamesysconst = "{{ url('/diagnosis/deletesysconstant') }}/" + encodeURIComponent(fldsysconst);
                    $('#sysconstantlistingmodal').append('<li class="sysconstantlist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sysconst_item" data-href="' + deleteroutenamesysconst + '" data-sysconstant="' + fldsysconst + '">' + fldsysconst + '</li>');
                    $('.select2sysconstant').append('<option value="' + fldsysconst + '" data-fldsysconst="' + fldsysconst + '">' + fldsysconst + '</option>');
                    select2loading();
                }

            }
        });
    } else {
        alert('Sys Constant Name is required');
    }
});

// selecting category item
$('#categorylistingmodal').on('click', '.category_item', function () {
    $('#categorytobedeletedroute').val($(this).data('href'));
    $('#categoryidtobedeleted').val($(this).data('id'));
});

// deleting selected category item
$('#categorydeletebutton').click(function () {
    var deletecategoryroute = $('#categorytobedeletedroute').val();
    var deletecategoryid = $('#categoryidtobedeleted').val();

    if (deletecategoryroute == '') {
        alert('no category selected, please select the category.');
    }

    if (deletecategoryroute != '') {
        var really = confirm("You really want to delete this category?");
        if (!really) {
            return false
        } else {
            $.ajax({
                type: 'delete',
                url: deletecategoryroute,
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function (res) {

                    if (res.message == 'success') {
                        showAlert(res.successmessage);
                        $("#categorylistingmodal").find(`[data-href='${deletecategoryroute}']`).parent().remove();
                        $(".select2categoryname").find(`[data-id='${deletecategoryid}']`).remove();
                        $('#categorytobedeletedroute').val('');
                        $('#categoryidtobedeleted').val('');
                    } else if (res.message == 'error') {
                        alert(res.errorMessage);
                    }
                }
            });
        }
    }
});

//selecting sysconstant item
$('#sysconstantlistingmodal').on('click', '.sysconst_item', function () {
    $('#sysconstanttobedeletedroute').val($(this).data('href'));
    $('#sysconstanttobedeleted').val($(this).data('sysconstant'));
});

// deleting selected sysconstant item
$('#sysconstantdeletebutton').click(function () {
    var deletesysconstantroute = $('#sysconstanttobedeletedroute').val();
    var deletesysconstant = $('#sysconstanttobedeleted').val();

    if (deletesysconstantroute == '') {
        alert('no sys constant selected, please select the sysconstant.');
    }

    if (deletesysconstantroute != '') {
        var really = confirm("You really want to delete this sysconstant?");
        if (!really) {
            return false
        } else {
            $.ajax({
                type: 'delete',
                url: deletesysconstantroute,
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldcategory': "Radio"
                },
                success: function (res) {
                    if (res.message == 'success') {
                        showAlert(res.successmessage);
                        $("#sysconstantlistingmodal").find(`[data-href='${deletesysconstantroute}']`).parent().remove();
                        $(".select2sysconstant").find(`[data-sysconstant='${deletesysconstant}']`).remove();
                        $('#sysconstanttobedeletedroute').val('');
                        $('#sysconstanttobedeleted').val('');
                    } else if (res.message == 'error') {
                        showAlert(res.errormessage);
                    }
                }
            });
        }
    }
});

$('#table-scroll-technologist').on('click','.fixed-side',function(){
    var examid = $(this).closest('tr').attr('rel');
    var url = "{{route('radiodiagnostic.edit')}}";
    $.ajax({
        url: url,
        type: "GET",
        data:  {
                    examid: examid
                },
        success: function(response) {
            if(response.data.status){
                $('#technologist_test_name').val(response.data.radioData.fldexamid);
                $('#fldcategory').val(response.data.radioData.fldcategory).change();
                $('#onChangeDataType').val(response.data.radioData.fldtype).change();
                $('#getChangeDataType').val(response.data.radioData.fldoption).change();
                $('#flddetail').val(response.data.radioData.flddetail);
                $('#fldcomment').val(response.data.radioData.fldcomment);
            }else{
                showAlert("Something went wrong...","error");
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
});

$(document).on('click','#radio_delete',function(){
    var testName = $('#technologist_test_name').val();
    if(testName != ""){
        var really = confirm("You really want to delete?");
        if(!really) {
            return false
        }
        var url = "{{route('radiodiagnostic.delete')}}";
        $.ajax({
            url: url,
            type: "GET",
            data:  {
                        testName: testName
                    },
            success: function(response) {
                if(response.data.status){
                    $('#table-scroll-technologist tr[rel="'+testName+'"]').remove();
                    clearForm();
                    showAlert("Deleted Successfuly!");
                }else{
                    showAlert(response.data.msg,"error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert("Please select test name first!");
    }
});

function clearForm(){
    $('#technologist_test_name').val("");
    $('#fldcategory').prop('selectedIndex',0).change();
    $('#onChangeDataType').prop('selectedIndex',0).change();
    $('#getChangeDataType').prop('selectedIndex',0).change();
    $('#flddetail').val("");
    $('#fldcomment').val("");
}

$(document).on('click','#radio_save',function(){
    var testName = $('#technologist_test_name').val();
    var url = "{{route('radiodiagnostic.add')}}";
    $.ajax({
        url: url,
        type: "POST",
        data:  {
                    fldexamid: testName,
                    fldcategory: $('#fldcategory').val(),
                    fldtype: $('#onChangeDataType').val(),
                    fldoption: $('#getChangeDataType').val(),
                    flddetail: $('#flddetail').val(),
                    fldcomment: $('#fldcomment').val(),
                    _token: "{{ csrf_token() }}"
                },
        success: function(response) {
            if(response.data.status){
                clearForm();
                $('#table-scroll-technologist').append("<tr rel='"+testName+"'><td class='fixed-side'>"+testName+"</tr>");
                showAlert("Radio Diagnostic Added Successfully!");
            }else{
                if(Object.keys(response.data.errors).length > 0){
                    var msg = "";
                    $.each( response.data.errors, function( key, value ) {
                        msg += value[0];
                        msg += ".";
                    });
                    showAlert(msg,"error");
                }else{
                    showAlert("Something went wrong...","error");
                }
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
});

$(document).on('click','#radio_update',function(){
    if($('#technologist_test_name').val() != ""){
        var url = "{{route('radiodiagnostic.update')}}";
        $.ajax({
            url: url,
            type: "POST",
            data:  {
                        fldexamid: $('#technologist_test_name').val(),
                        fldcategory: $('#fldcategory').val(),
                        fldtype: $('#onChangeDataType').val(),
                        fldoption: $('#getChangeDataType').val(),
                        flddetail: $('#flddetail').val(),
                        fldcomment: $('#fldcomment').val(),
                        _token: "{{ csrf_token() }}"
                    },
            success: function(response) {
                if(response.data.status){
                    showAlert("Radio Diagnostic Updated Successfully!");
                }else{
                    if(Object.keys(response.data.errors).length > 0){
                        var msg = "";
                        $.each( response.data.errors, function( key, value ) {
                            msg += value[0];
                            msg += ".";
                        });
                        showAlert(msg,"error");2
                    }else{
                        showAlert(response.data.message,"error");
                    }
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert("Please select test first!");
    }
});
</script>
@endpush
