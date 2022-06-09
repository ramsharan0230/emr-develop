@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Diagnostic Master / Clinical Examination</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="{{ route('examination.updateexamination', encrypt($examination->fldexamid)) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-md-4">
                                    @include('diagnosis::layouts.includes.examinationlisting')
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-2">Test Name</label>
                                        <div class="col-md-10">
                                            <input type="text" name="fldexamid" value="{{ $examination->fldexamid }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-2">Category</label>
                                        <div class="col-md-9">
                                            @php
                                                $pathocategorytype = $categorytype;
                                                $pathocategories = \App\Utils\Diagnosishelpers::getPathoCategory($pathocategorytype);
                                            @endphp
                                            <select name="fldcategory" class="form-control select2categoryname" required>
                                                <option value=""></option>
                                                @forelse($pathocategories as $pathocategory)
                                                    <option value="{{ $pathocategory->flclass }}" data-id="{{ $pathocategory->fldid }}" {{ ((old('fldcategory') && old('fldcategory') == $pathocategory->flclass) || strtolower($pathocategory->flclass) == trim(strtolower($examination->fldcategory))) ? 'selected' : ''}}>{{ $pathocategory->flclass }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" data-toggle="modal" class="btn btn-primary btn-sm-in" data-target="#category_modal"><i class="ri-add-line"></i></a>

                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-2">Sys Constant</label>
                                        <div class="col-md-9">
                                            @php
                                                $sysconstcategory = $categorytype;
                                                $sysconsts = \App\Utils\Diagnosishelpers::getallSysConstant($sysconstcategory);
                                            @endphp
                                            <select name="fldsysconst" class="form-control select2sysconstant">
                                                <option value=""></option>
                                                <option value="Blood_Pressure_Diastolic" {{ ((old('fldsysconst') && old('fldsysconst') == 'Blood_Pressure_Diastolic') || $examination->fldsysconst == 'Blood_Pressure_Diastolic') ? 'selected' : ''}}>Blood_Pressure_Diastolic</option>
                                                <option value="BloodPressue_Systolic" {{ ((old('fldsysconst') && old('fldsysconst') == 'BloodPressue_Systolic')|| $examination->fldsysconst == 'BloodPressue_Systolic') ? 'selected' : ''}}>BloodPressue_Systolic</option>
                                                <option value="Body_Height" {{ ((old('fldsysconst') && old('fldsysconst') == 'Body_Height')|| $examination->fldsysconst == 'Body_Height') ? 'selected' : ''}}>Body_Height</option>
                                                <option value="Body_Weight" {{ ((old('fldsysconst') && old('fldsysconst') == 'Body_Weight')|| $examination->fldsysconst == 'Body_Weight') ? 'selected' : ''}}>Body_Weight</option>
                                                <option value="Glassgow_Coma_Scale" {{ ((old('fldsysconst') && old('fldsysconst') == 'Glassgow_Coma_Scale')|| $examination->fldsysconst == 'Glassgow_Coma_Scale') ? 'selected' : ''}}>Glassgow_Coma_Scale</option>
                                                <option value="Heart_Rate" {{ ((old('fldsysconst') && old('fldsysconst') == 'Heart_Rate')|| $examination->fldsysconst == 'Heart_Rate') ? 'selected' : ''}}>Heart_Rate</option>
                                                <option value="Oxygen_Saturation" {{ ((old('fldsysconst') && old('fldsysconst') == 'Oxygen_Saturation')|| $examination->fldsysconst == 'Oxygen_Saturation') ? 'selected' : ''}}>Oxygen_Saturation</option>
                                                <option value="Pulse_Rate" {{ ((old('fldsysconst') && old('fldsysconst') == 'Pulse_Rate')|| $examination->fldsysconst == 'Pulse_Rate') ? 'selected' : ''}}>Pulse_Rate</option>
                                                <option value="Pulse_Rhythm" {{ ((old('fldsysconst') && old('fldsysconst') == 'Pulse_Rhythm')|| $examination->fldsysconst == 'Pulse_Rhythm') ? 'selected' : ''}}>Pulse_Rhythm</option>
                                                <option value="Respiration_Rate" {{ ((old('fldsysconst') && old('fldsysconst') == 'Respiration_Rate')|| $examination->fldsysconst == 'Respiration_Rate') ? 'selected' : ''}}>Respiration_Rate</option>
                                                <option value="Temparature_System" {{ ((old('fldsysconst') && old('fldsysconst') == 'Temparature_System')|| $examination->fldsysconst == 'Temparature_System') ? 'selected' : ''}}>Temparature_System</option>
                                                @forelse($sysconsts as $sysconst)
                                                    <option value="{{ $sysconst->fldsysconst }}" data-sysconstant="{{ $sysconst->fldsysconst }}" {{ ((old('fldsysconst') && old('fldsysconst') == $sysconst->fldsysconst) || $examination->fldsysconst ==  $sysconst->fldsysconst) ? 'selected' : ''}}>{{ $sysconst->fldsysconst }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="" data-toggle="modal" class="btn btn-primary btn-sm-in" data-target="#sysconstant_modal"><i class="ri-add-line"></i></a>

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label class="col-md-4">Sensitivity</label>
                                                <div class="col-md-8">
                                                    <input type="number" name="fldsensitivity" value="{{ $examination->fldsensitivity }}" step="any" class="form-control" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label class="col-md-4">Specificity</label>
                                                <div class="col-md-8">
                                                    <input type="number" name="fldspecificity" value="{{ $examination->fldspecificity }}" step="any" class="form-control" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label class="col-md-4">Data Type</label>
                                                <div class="col-md-8">
                                                    <select name="fldtype" class="form-control select-3" id="data_type" required>
                                                        <option value=""></option>
                                                        <option value="Qualitative" {{ ((old('fldtype') && old('fldtype') == 'Qualitative') || $examination->fldtype == 'Qualitative') ? 'selected' : ''}}>Qualitative</option>
                                                        <option value="Quantitative" {{ ((old('fldtype') && old('fldtype') == 'Quantitative') || $examination->fldtype == 'Quantitative') ? 'selected' : ''}}>Quantitative</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <button class="col-md-4 btn btn-primary btn-sm-in" type="button"><i class="ri-list-unordered "></i> Comments</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label class="col-md-4">Input Mode</label>
                                                <div class="col-md-8">
                                                    <select name="fldoption" class="form-control select-3" id="input_mode">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <button type="button" class="col-md-4 btn btn-primary btn-sm-in"><i class="ri-information-line "></i> Options</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <p class="border col-sm-4 with-outline">Outliers</p>
                                        <p class="border col-sm-4 with-outline">Ref Range+</p>
                                        <p class="border col-sm-4 with-outline">X Ref Range</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldcritical" value="{{ $examination->fldcritical }}" class="form-control" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="flddetail" class="form-control">{!! $examination->flddetail !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Footnote</label>
                                                <textarea name="fldcomment" class="form-control">{!! $examination->fldcomment !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary"><i class="ri-add-line"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('diagnosis::layouts.modal.sysconstant')
    @include('diagnosis::layouts.modal.category')
    <form id="delete_form" method="POST">
        @csrf
        @method('delete')
    </form>

    <script>
        $(function() {
            function select2loading() {
                setTimeout(function() {
                    $('.select2categoryname').select2({
                        placeholder : 'select category'
                    });

                    $('.select2sysconstant').select2({
                        placeholder : 'select sys constant'
                    });
                }, 4000);
            }

            select2loading();


            // adding category

            $('#categoryaddaddbutton').click(function() {
                var categoryname = $('#categorynamefield').val();
                var fldcategory = $('#fldcategoryfield').val();


                if(categoryname != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('addpathocategory') }}',
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'flclass': categoryname,
                            'fldcategory': fldcategory
                        },
                        success: function (res) {

                            showAlert(res.message);
                            if(res.message == 'Category added successfully.') {
                                $('#categorynamefield').val('');
                                var deleteroutename = "{{ url('/diagnosis/deletecategory') }}/"+res.fldid;
                                $('#categorylistingmodal').append('<li class="category-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="category_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.flclass+'</li>');
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

                                $('.select2categoryname').append('<option value="'+res.flclass+'" data-id="'+res.fldid+'">'+res.flclass+'</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Category Name is required');
                }
            });

            // adding sysconstant

            $('#sysconstantaddbutton').click(function() {
                var fldsysconst = $('#sysconstantnamefield').val();
                var fldcategory = $('#fldcategoryfieldsysconstant').val();

                if(fldsysconst != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('addsysconstant') }}',
                        dataType: 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'fldsysconst': fldsysconst,
                            'fldcategory' : fldcategory
                        },
                        success: function (res) {
                            showAlert(res.message);
                            if(res.message == 'Sys Constant added successfully.') {
                                $('#sysconstantnamefield').val('');
                                var deleteroutenamesysconst = "{{ url('/diagnosis/deletesysconstant') }}/"+encodeURIComponent(fldsysconst);
                                $('#sysconstantlistingmodal').append('<li class="sysconstantlist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sysconst_item" data-href="'+deleteroutenamesysconst+'" data-sysconstant="'+fldsysconst+'">'+fldsysconst+'</li>');
                                $('.select2sysconstant').append('<option value="'+fldsysconst+'" data-fldsysconst="'+fldsysconst+'">'+fldsysconst+'</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Sys Constant Name is required');
                }
            });

            // selecting category item
            $('#categorylistingmodal').on('click', '.category_item', function() {
                $('#categorytobedeletedroute').val($(this).data('href'));
                $('#categoryidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#categorydeletebutton').click(function() {
                var deletecategoryroute = $('#categorytobedeletedroute').val();
                var deletecategoryid = $('#categoryidtobedeleted').val();

                if(deletecategoryroute == '') {
                    alert('no category selected, please select the category.');
                }

                if(deletecategoryroute != '') {
                    var really = confirm("You really want to delete this category?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletecategoryroute,
                            dataType : 'json',
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {

                                if(res.message == 'success') {
                                    showAlert(res.successmessage);
                                    $("#categorylistingmodal").find(`[data-href='${deletecategoryroute}']`).parent().remove();
                                    $(".select2categoryname").find(`[data-id='${deletecategoryid}']`).remove();
                                    $('#categorytobedeletedroute').val('');
                                    $('#categoryidtobedeleted').val('');
                                } else if(res.message ==  'error') {
                                    showAlert(res.errorMessage);
                                }
                            }
                        });
                    }
                }
            });

            //selecting sysconstant item
            $('#sysconstantlistingmodal').on('click', '.sysconst_item', function() {
                $('#sysconstanttobedeletedroute').val($(this).data('href'));
                $('#sysconstanttobedeleted').val($(this).data('sysconstant'));
            });

            // deleting selected sysconstant item
            $('#sysconstantdeletebutton').click(function() {
                var deletesysconstantroute = $('#sysconstanttobedeletedroute').val();
                var deletesysconstant = $('#sysconstanttobedeleted').val();

                if(deletesysconstantroute == '') {
                    alert('no sys constant selected, please select the sysconstant.');
                }

                if(deletesysconstantroute != '') {
                    var really = confirm("You really want to delete this sysconstant?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletesysconstantroute,
                            dataType : 'json',
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {

                                if(res.message == 'success') {
                                    showAlert(res.successmessage)
                                    $("#sysconstantlistingmodal").find(`[data-href='${deletesysconstantroute}']`).parent().remove();
                                    $(".select2sysconstant").find(`[data-sysconstant='${deletesysconstant}']`).remove();
                                    $('#sysconstanttobedeletedroute').val('');
                                    $('#sysconstanttobedeleted').val('');
                                } else if(res.message == 'error') {
                                    showAlert(res.errormessage);
                                }
                            }
                        });
                    }
                }
            });

            // input mode options according to data type

                function datatypechange(datatype) {
                    if(datatype == 'Qualitative') {
                        var options =   `<option value="No Selection" {{ ($examination->fldoption == 'No Selection') ? 'selected' : '' }}>No Selection</option>
                                        <option value="Single Selection" {{ ($examination->fldoption == 'Single Selection') ? 'selected' : '' }}>Single Selection</option>
                                        <option value="Dichotomous"  {{ ($examination->fldoption == 'Dichotomous') ? 'selected' : '' }}>Dichotomous</option>
                                        <option value="Clinical Scale"  {{ ($examination->fldoption == 'Clinical Scale') ? 'selected' : '' }}>Clinical Scale</option>
                                        <option value="Text Addition"  {{ ($examination->fldoption == 'Text Addition') ? 'selected' : '' }}>Text Addition</option>
                                        <option value="Text Reference"  {{ ($examination->fldoption == 'Text Reference') ? 'selected' : '' }}>Text Reference</option>
                                        <option value="Visual Input"  {{ ($examination->fldoption == 'Visual Input') ? 'selected' : '' }}>Visual Input</option>
                                        <option value="Custom Components"  {{ ($examination->fldoption == 'Custom Components') ? 'selected' : '' }}>Custom Components</option>
                                        <option value="Left and Right"  {{ ($examination->fldoption == 'Left and Right') ? 'selected' : '' }}>Left and Right</option>
                                        <option value="Date Time"  {{ ($examination->fldoption == 'Date Time') ? 'selected' : '' }}>Date Time</option>`;

                        $('#input_mode').html(options);
                    }else if(datatype == 'Quantitative') {
                        var options = `<option value="No Selection">No Selection</option>`;

                        $('#input_mode').html(options);
                    }
                }

                $('#data_type').change(function() {
                    var datatype = $(this).val();

                    datatypechange(datatype);

                });

                var olddatatype = '{{ $examination->fldtype }}';

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

            $('.deleteexam').click(function() {
                var really = confirm("You really want to delete this Exam?");
                var href = $(this).data('href');
                if(!really) {
                    return false
                } else {
                    $('#delete_form').attr('action', href);
                    $('#delete_form').submit();
                }
            });

            $('#editfldexamid').click(function() {
                $('#fldexamid').prop("readonly", (_, val) => !val);
                $( "#fldexamid" ).toggleClass( "backgroundtestname", "addOrRemove" );
            });
        })
    </script>
@endsection
