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

        // input mode options according to data type

        function datatypechange(datatype) {
            if (datatype == 'Qualitative') {
                var options = `<option value="No Selection" {{ (old('fldoption') == 'No Selection') ? 'selected' : '' }}>No Selection</option>
                                <option value="Single Selection" {{ (old('fldoption') == 'Single Selection') ? 'selected' : '' }}>Single Selection</option>
                                <option value="Dichotomous" {{ (old('fldoption') == 'Dichotomous') ? 'selected' : '' }}>Dichotomous</option>
                                <option value="Clinical Scale" {{ (old('fldoption') == 'Clinical Scale') ? 'selected' : '' }}>Clinical Scale</option>
                                <option value="Text Addition" {{ (old('fldoption') == 'Text Addition') ? 'selected' : '' }}>Text Addition</option>
                                <option value="Text Reference" {{ (old('fldoption') == 'Text Reference') ? 'selected' : '' }}>Text Reference</option>
                                <option value="Visual Input" {{ (old('fldoption') == 'Visual Input') ? 'selected' : '' }}>Visual Input</option>
                                <option value="Custom Components" {{ (old('fldoption') == 'Custom Components') ? 'selected' : '' }}>Custom Components</option>
                                <option value="Fixed Components" {{ (old('fldoption') == 'Fixed Components') ? 'selected' : '' }}>Fixed Components</option>
                                <option value="Left and Right" {{ (old('fldoption') == 'Left and Right') ? 'selected' : '' }}>Left and Right</option>
                                <option value="Date Time" {{ (old('fldoption') == 'Date Time') ? 'selected' : '' }}>Date Time</option>`;
                $('#getChangeDataType').html(options);
            } else if (datatype == 'Quantitative') {
                var options = `<option value="No Selection">No Selection</option>`;

                $('#getChangeDataType').html(options);
            }
        }

        $('#onChangeDataType').change(function () {
            // var datatype = $(this).val();
            // datatypechange(datatype);
            qualitativeQuantitativeDataPopulate();

        });

        function qualitativeQuantitativeDataPopulate() {
            var selected = $("#onChangeDataType option:selected").val();
            var Qualitative, Quantitative;
            Qualitative = [
                'Single Selection',
                'Dichotomous',
                'Clinical Scale',
                'Text Addition',
                'Text Reference',
                'Visual Input',
                'Custom Components',
                'Fixed Components',
                'Left and Right',
                'Date Time'
            ];
            Quantitative = "No Selection";
            $("#getChangeDataType").empty();
            if (selected == 'Qualitative') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '');
                $('.dynamic-option-btn').attr('data-toggle', 'modal');
                $('.dynamic-option-btn').removeClass('onclick-get-quantitative');
                $("#getChangeDataType").append('<option>No Selection</option>');
                $.each(Qualitative, function (name, value) {
                    $("#getChangeDataType").append('<option value="' + value + '">' + value + '</option>');
                });
            }
            if (selected == 'Quantitative') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-toggle', '');
                $('.dynamic-option-btn').addClass('onclick-get-quantitative');
                $("#getChangeDataType").append('<option value="' + Quantitative + '">' + Quantitative + '</option>');
            }
        }

        // var olddatatype = '{{ old('fldtype') }}';

        // datatypechange(olddatatype);

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

        $('#getChangeDataType').on('change', function (e) {
            sectedAsPerModalType();
        });

        function sectedAsPerModalType() {
            var testid = $('#technologist_test_name').val();
            var selected = $("#getChangeDataType").children(":selected").text();

            if (selected === 'Single Selection' || selected === 'Dichotomous' || selected === 'Left and Right' || selected === 'Date Time' || selected === 'Text Reference') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '#common-modal-box-one');
            }
            if (selected === 'Clinical Scale') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '#clinical-scale-box');
            }
            if (selected === 'Text Addition') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '#text-addition-box');
            }
            if (selected === 'Visual Input') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '#visual-input-box');
            }
            if (selected === 'Custom Components') {
                // Dynamic Option data-type attr
                $('.dynamic-option-btn').attr('data-target', '#custom-components-box');
            }
            if (selected === 'Fixed Components') {
                // Dynamic Option data-type attr
                getMainTestOption(testid)
                $('.dynamic-option-btn').attr('data-target', '#fixed-components-box');
            }
        }

        // on click dynamic-option-btn
        $(".dynamic-option-btn").on("click", function () {
            // var testid = $('#technologist_test_name').val();

            // alert('here')
            var test_name = $('#technologist_test_name').val();
            var input_mode = $('#getChangeDataType option:selected').val();
            /*alert(input_mode)*/
            if (test_name === '' || input_mode === '') {
                alert('Please Select Test Name and Input Mode');
                return false;
            }

            if (input_mode === 'Fixed Components') {
                //alert('dd')
                getMainTestOption(test_name)
            }

            if (input_mode === 'Custom Components') {
                //alert('dd')
                getMainTestOption(test_name)
            }
            if (input_mode !== 'No Selection' || input_mode !== 'Fixed Components' || input_mode !== 'Custom Components') {
                console.log("hit hit");
                getTestOption(test_name, input_mode);
                getClinicianScale(test_name, input_mode);
                getDistinctGroup(test_name, input_mode);
            }
        });

        $(document).on("click", ".get-selected-test-data tr", function () {
            var testid = $(this).attr("rel");
            // ajax
            // alert(testid);
            $.get('test/related-value?testid=' + testid, function (data) {
                // console.log(data);
                $('#technologist_add_reset').css('display', 'block');
                $('#technologist_save').css('display', 'none');
                // Empty Data
                $('#technologist_test_name').val(null);
                $('#technologist_collection').val(null);
                $('#technologist_sensitivity').val(null);
                $('#technologist_specificity').val(null);
                $('#technologist_critical').val(null);
                $('#technologist_description').val(null);
                $('#technologist_comment').val(null);
                ////////////////////////////////come here

                $('option:selected', 'select[name="technologist_categories"]').removeAttr('selected');
                $('option:selected', 'select[name="technologist_constant"]').removeAttr('selected');
                $('option:selected', 'select[name="technologist_specimen"]').removeAttr('selected');
                $('option:selected', 'select[name="technologist_datatype"]').removeAttr('selected');
                $('option:selected', 'select[name="technologist_vial"]').removeAttr('selected');
                $('option:selected', 'select[name="selected_clinicial_scale_group"]').removeAttr('selected');

                // Get Data
                $('#technologist_test_name').val(data.fldtestid);
                $('#technologist_test_name').attr('rel', data.fldtestid);
                $('#technologist_collection').val(data.fldcollection);
                $('#technologist_vial').val(data.fldvial);
                $('#technologist_sensitivity').val(data.fldsensitivity);
                $('#technologist_specificity').val(data.fldspecificity);
                $('#technologist_critical').val(data.fldcritical);
                $('#technologist_description').val(data.flddetail);
                $('#technologist_comment').val(data.fldcomment);

                $('select[name="technologist_categories"]').find('option[value="' + data.fldcategory + '"]').attr("selected", true);
                $('select[name="technologist_constant"]').find('option[value="' + data.fldsysconst + '"]').attr("selected", true);
                $('select[name="technologist_specimen"]').find('option[value="' + data.fldspecimen + '"]').attr("selected", true);
                $('select[name="technologist_datatype"]').find('option[value="' + data.fldtype + '"]').attr("selected", true);
                $('select[name="technologist_vial"]').find('option[value="' + data.fldtype + '"]').attr("selected", true);
                qualitativeQuantitativeDataPopulate();
                $('select[name="technologist_input_mode"]').find('option[value="' + data.fldoption + '"]').attr("selected", true);
                sectedAsPerModalType();
            });
        });
    })

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
                    $('#getChangeDataType').val(response.data.radioData.fldoption);
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

    $(document).on('click', ".onclick-get-quantitative", function () {
        var test_name = $('#technologist_test_name').val();
        if (test_name == '') {
            alert('Select Test First');
            return false;
        }
        $(".get_test_name_here").empty();
        $(".get_test_name_here").append(test_name);
        getMethodVariables();
        getQuantitativeTestPara(test_name);
        $("#quantitative-modal-box").modal("show");
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
