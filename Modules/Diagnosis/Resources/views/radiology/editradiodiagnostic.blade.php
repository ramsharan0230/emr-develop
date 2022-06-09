@extends('frontend.layouts.master')
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
                    <form action="{{ route('radiodiagnostic.update', encrypt($radio->fldexamid)) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="iq-search-bar custom-search">
                                    {{--<form action="#" class="searchbox">
                                        <input
                                        type="text"
                                        class="text search-input"
                                        placeholder="Type here to search..."
                                        />
                                        <a class="search-link" href="#"
                                        ><i class="ri-search-line"></i
                                            ></a>
                                        </form>--}}
                                    </div>
                                    <div class="dietarytable">
                                        @include('diagnosis::layouts.includes.radiologylisting')
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <form action="" class="form-horizontal">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Test Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="fldexamid" id="fldexamid" value="{{  $radio->fldexamid }}" class="form-control backgroundtestname" placeholder="" style="width:72%" required readonly>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Category</label>
                                            <div class="col-sm-8">
                                                @php
                                                $pathocategorytype = $categorytype;
                                                $pathocategories = \App\Utils\Diagnosishelpers::getPathoCategory($pathocategorytype);
                                                @endphp
                                                <select name="fldcategory" class="form-select-dietary select2categoryname form-control" required style="width: 63%;">
                                                    <option value=""></option>
                                                    @forelse($pathocategories as $pathocategory)
                                                    <option value="{{ $pathocategory->flclass }}" data-id="{{ $pathocategory->fldid }}" {{ ((old('fldcategory') && old('fldcategory') == $pathocategory->flclass) || $pathocategory->flclass == $radio->fldcategory) ? 'selected' : ''}}>{{ $pathocategory->flclass }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <a href="javascript:void(0)" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#category_modal"> Add</a>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Sys Constant</label>
                                            <div class="col-sm-8">
                                                @php
                                                $sysconstcategory = $categorytype;
                                                $sysconsts = \App\Utils\Diagnosishelpers::getallSysConstant($sysconstcategory);
                                                @endphp
                                                <select name="fldsysconst" class="form-select-dietary select2sysconstant form-control" style="width: 63%;">
                                                    <option value=""></option>
                                                    @forelse($sysconsts as $sysconst)
                                                    <option value="{{ $sysconst->fldsysconst }}" data-sysconstant="{{ $sysconst->fldsysconst }}" {{ ((old('fldsysconst') && old('fldsysconst') == $sysconst->fldsysconst) || $sysconst->fldsysconst == $radio->fldsysconst) ? 'selected' : ''}}>{{ $sysconst->fldsysconst }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <a href="javascript:void(0)" class="btn btn-sm-in btn-primary" data-toggle="modal" data-target="#sysconstant_modal">
                                                 Add
                                             </a>
                                         </div>
                                     </div>
                                     <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Sensitivity</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="fldsensitivity" value="{{ old('fldsensitivity') }}" value="{{ $radio->fldsensitivity }}" step="any" class="form-control" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Specificity</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="fldspecificity" value="{{ $radio->fldspecificity }}" class="form-control" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Data Type</label>
                                        <div class="col-sm-5">
                                            <select name="fldtype" class="select-4 form-control" id="data_type" required>
                                                <option value=""></option>
                                                <option value="Qualitative" {{ ((old('fldtype') && old('fldtype') == 'Qualitative') || $radio->fldtype == 'Qualitative') ? 'selected' : ''}}>Qualitative</option>
                                                <option value="Quantitative" {{ ((old('fldtype') && old('fldtype') == 'Quantitative') || $radio->fldtype == 'Quantitative') ? 'selected' : ''}}>Quantitative</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-sm-in btn-primary" type="button">
                                                <i class="fa fa-list"></i> comments
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Input mode</label>
                                        <div class="col-sm-7">
                                            <select name="fldoption" class="select-4 form-control" id="input_mode">
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-sm-in btn-primary" disabled="">
                                                <i class="ri-information-fill"></i>&nbsp;Options
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <p class="border col-sm-3 with-outline">Outliers</p>
                                        <p class="border col-sm-3 with-outline">Ref Range+</p>
                                        <div class="col-sm-3">
                                            <input type="number" step="any" name="fldcritical" value="{{ old('fldcritical') }}" class="form-control" placeholder="0">
                                        </div>
                                        <p class="border col-sm-3 with-outline">
                                            X Ref Range
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <textarea name="flddetail" class="form-control">{!! $radio->flddetail !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Footnote</label>
                                        <textarea name="fldcomment" class="form-control">{!! $radio->fldcomment !!}</textarea>
                                    </div>
                                    <div class="diagnosis-btn">
                                        <button
                                        class="btn btn-action btn-primary"
                                        >
                                        <i class="fa fa-plus"></i>&nbsp;Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@include('diagnosis::layouts.modal.category')
@include('diagnosis::layouts.modal.sysconstant')
<form id="delete_form" method="POST">
    @csrf
    @method('delete')
</form>
@endsection

@push('after-script')
<script>
    $(function () {

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

        select2loading();


            // adding category

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
                                // var selectcategoryoptions = '<option value=""></option>';
                                {{--setTimeout(function() {--}}
                                    {{--    @php--}}
                                    {{--        $categorytype = 'Test';--}}
                                    {{--        $pathocategoriesafteradd = \App\Utils\Diagnosishelpers::getPathoCategory($categorytype);--}}
                                    {{--    @endphp--}}
                                    {{--        @forelse($pathocategoriesafteradd as $pathocategoryadd)--}}
                                    {{--        selectcategoryoptions += '<option value="{{ $pathocategoryadd->flclass }}">{{ $pathocategoryadd->flclass }}</option>';--}}
                                    {{--    @empty--}}
                                    {{--    @endforelse--}}
                                    {{--    console.log(selectcategoryoptions);--}}
                                    {{--    $('.select2categoryname').html(selectcategoryoptions);--}}
                                    {{--}, 5000);--}}

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

            // input mode options according to data type

            function datatypechange(datatype) {
                if (datatype == 'Qualitative') {
                    var options = `<option value="No Selection" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>No Selection</option>
                    <option value="Single Selection" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Single Selection</option>
                    <option value="Dichotomous" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Dichotomous</option>
                    <option value="Clinical Scale" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Clinical Scale</option>
                    <option value="Text Addition" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Text Addition</option>
                    <option value="Text Reference" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Text Reference</option>
                    <option value="Visual Input" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Visual Input</option>
                    <option value="Custom Components" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Custom Components</option>
                    <option value="Left and Right" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Left and Right</option>
                    <option value="Date Time" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>Date Time</option>`;
                    $('#input_mode').html(options);
                } else if (datatype == 'Quantitative') {
                    var options = `<option value="No Selection">No Selection</option>`;

                    $('#input_mode').html(options);
                }
            }

            $('#data_type').change(function () {
                var datatype = $(this).val();
                datatypechange(datatype);

            });

            var olddatatype = '{{ old('fldtype') }}';

            datatypechange(olddatatype);

            // end input mode options according to data type

            // validation error message

            @if($errors->any())
            var validation_error = '';

            @foreach($errors->all() as $error)
            validation_error += '{{ $error }} \n';
            @endforeach

            showAlert(validation_error);
            @endif


            @if(Session::has('success_message'))
            var successmessage = '{{ Session::get('success_message') }}';
            showAlert(successmessage);
            @endif

            @if(Session::has('error_message'))
            var errormessage = '{{ Session::get('error_message') }}';
            showAlert(errormessage);
            @endif

            $('.deleteradiodiagnostictest').click(function () {
                var really = confirm("You really want to delete this radio diagnostic?");
                var href = $(this).data('href');
                if (!really) {
                    return false
                } else {
                    $('#delete_form').attr('action', href);
                    $('#delete_form').submit();
                }
            });
        })
    </script>
    @endpush
