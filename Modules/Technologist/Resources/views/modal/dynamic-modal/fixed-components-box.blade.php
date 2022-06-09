<div class="modal fade" id="fixed-components-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <input type="hidden" name="common-modal-first-test-id" id="common-modal-first-test-id">
                <div class="modal-header">
                    <h4 class="modal-title">Add Parameters </h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
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
                                    <label><input type="text" class="form-control sub-test-name"/> </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Option</label>
                                </div>
                                <div class="col-4">
                                    <select name="sub-test-option-choosed" class="form-control" id="sub-test-option-choosed">
                                        <option value=""></option>
                                        <option value="Single Selection">Single Selection</option>
                                        <option value="Dichotomous">Dichotomous</option>
                                        <option value="Text Addition">Text Addition</option>
                                        <option value="Text Reference">Text Reference</option>
                                        <option value="Left and Right">Left and Right</option>
                                        <option value="Clinical Scale">Clinical Scale</option>
                                        <option value="Percent Sum">Percent Sum</option>
                                        <option value="Multiple Selection">Multiple Selection</option>
                                        <option value="Drug Sensitivity">Drug Sensitivity</option>
                                    </select>
                                </div>
                                <a href="javascript:;" class="btn btn-primary dynamic-option-btn-subone"><i class="fas fa-edit"></i>&nbsp;Option</a>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Reference</label>
                                </div>
                                <div class="col-4">
                                    <label id="common-modal-reference-label"><input type="text" class="form-control" name="common-modal-reference" id="common-modal-reference-fixed"></label>
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Procedure</label>
                                </div>
                                <div class="col-4">
                                    <label id="common-modal-procedure-label"><input type="text" class="form-control" name="common-modal-procedure" id="common-modal-procedure-fixed"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="btn btn-primary insert-test-option-fixed" url="{{ route('technologist.insert.first.level.test.option') }}"><i class="fas fa-plus"></i> Add</button>
                                    <button type="button" class="btn btn-primary update-test-option-fixed" url="{{ route('technologist.update.first.level.test.option') }}"><i class="fas fa-edit"></i> Update</button>
                                    <button type="button" style="display: none" id="parameter_save_arrangements" class="btn btn-primary" url="{{ route('order.fixed.component') }}"><i class="fas fa-save"></i> Save arrangements</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody class="display-test-option-list" >

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="common-one-modal-of-fixed" tabindex="-1" role="dialog" aria-labelledby="common-one-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Test</label>
                                </div>
                                <div class="col-4">
                                    <label id="fixed-main-test-name"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Sub Test</label>
                                </div>
                                <div class="col-4">
                                    <label><input name="fixed-sub-test-name" class="form-control fixed-sub-test-name" readonly/> </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Option Type</label>
                                </div>
                                <div class="col-4">
                                    <label id="fixed-optiontype-sub-test"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Option</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="fixed-sub-option">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3"></div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary fixed-sub-common-type-insert " url="{{ route('fixed.sub.common.type.insert') }}"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody class="display-test-option-list-sub">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="clinical-scale-box-of-fixed" tabindex="-1" role="dialog" aria-labelledby="clinical-scale-box" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Parameters</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Test</label>
                                </div>
                                <div class="col-4">
                                    <label id="fixed-main-test-name-clinical"></label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Sub Test</label>
                                </div>
                                <div class="col-4">
                                    <label><input name="fixed-sub-test-name" class="form-control fixed-sub-test-name" readonly/> </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="fixed-clinicalexam-label-small">Group</label>
                                </div>
                                <div class="col-4">
                                    <div class="select-editable">
                                        <select onchange="this.nextElementSibling.value=this.value" id="selected_clinicial_scale_group_options_fixed" style="background: none;">
                                        </select>
                                        <input type="text" name="selected_clinicial_scale_group" id="selected_clinicial_scale_group_fixed" value=""/>
                                    </div>
                                    {{-- <select name="selected_clinicial_scale_group" id="selected_clinicial_scale_group" class="scale_group"></select> --}}
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Parameter</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="fixed-clinical-scale-parameter">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Value</label>
                                </div>
                                <div class="col-4">
                                    <input type="number" id="fixed-clinical-scale-value">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="btn btn-primary fixed-sub-clinical-type-insert" url="{{ route('fixed.sub.common.type.insert') }}"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>&nbsp;

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody id="display-clinical-scale-list-fixed">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('technologist::modal.dynamic-modal.common-get-function')

<script>

    $('.display-test-option-list').sortable({
        update: function (event, ui) {
            $(this).children().each(function (index) {
                if ($(this).attr('data-position') != (index + 1)) {
                    $(this).attr('data-position', (index + 1)).addClass('updated');
                }
            });
            $('#parameter_save_arrangements').show();
            // saveTestsQualiPositions();
        }
    });

    $('#parameter_save_arrangements').click( function () {
        saveTestsQualiPositions();
    });

    function saveTestsQualiPositions() {
        var positions = [];
        $('.updated').each(function () {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position'), $(this).attr('data-class')]);
            $(this).removeClass('updated');
        });
        $.ajax({
            url: '{{ route('order.fixed.component') }}',
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                update: 1,
                positions: positions,
            }, success: function (response) {
                if (response.html) {
                    $('.display-test-option-list').empty().append(response.html);
                }
                if (response.html==="") {
                    $('.display-test-option-list').empty().append('<tr><td>No data available</td></tr>');
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




    function getMainTestOption(test_name) {


        $('.display-test-option-list').empty();

        $.get('{{ route("test.quali.old.data") }}?fldtestid=' + encodeURIComponent(test_name), function (data) {
            $.each(data.data, function (index, getRelatedData) {
                $('.display-test-option-list').append('<tr data-class ="'+getRelatedData.fldid + '" class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldsubtest + '" ref="' + getRelatedData.fldreference + '" proc="' + getRelatedData.flddetail + '" answertype="' + getRelatedData.fldtanswertype + '"><td>'+ ( getRelatedData.order_by ? getRelatedData.order_by +'-' :'') +''+ getRelatedData.fldsubtest + '</td><td>' + getRelatedData.fldtanswertype + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteFirstTestOption(' + getRelatedData.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');

            });
        });

    }


    function getMainSubTestOption(test_name, subtest_name, subtestoptiontype) {

        $('.display-test-option-list-sub').empty();

        $.get('{{ route("sub.test.quali.old.data") }}?fldtestid=' + encodeURIComponent(test_name) + '&fldsubtest=' + encodeURIComponent(subtest_name) + '&fldanswertype=' + encodeURIComponent(subtestoptiontype), function (data) {
            $.each(data.data, function (index, getRelatedData) {
                $('.display-test-option-list-sub').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '"><td>' + getRelatedData.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteSubTestQuali(' + getRelatedData.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                $('.display-text-addition').attr('old_text_addition', getRelatedData.fldanswer);
            });
        });

    }

    function getMainClinicalSubTestOption(test_name, subtest_name, subtestoptiontype) {
        $('#display-clinical-scale-list-fixed').empty();

        $.get('{{ route("sub.test.quali.old.data") }}?fldtestid=' + encodeURIComponent(test_name) + '&fldsubtest=' + encodeURIComponent(subtest_name) + '&fldanswertype=' + encodeURIComponent(subtestoptiontype), function (data) {
            $.each(data.data, function (index, getRelatedData) {
                $('#display-clinical-scale-list-fixed').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '"><td>' + getRelatedData.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick=\'deleteSubTestQualiClinical('+getRelatedData.fldid+',"'+test_name+'","'+subtest_name+'","'+subtestoptiontype+'")'+'\'><i class="fa fa-times"></i></a></td></tr>');
                // $('.display-text-addition').attr('old_text_addition', getRelatedData.fldanswer);
            });
        });

        $('#selected_clinicial_scale_group_options_fixed').empty();
        $.get('{{ route("techno.distinct.subtest.get.group.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&fldsubtest=' + encodeURIComponent(subtest_name) + '&input_mode=' + encodeURIComponent(subtestoptiontype), function (data) {
            $.each(data, function (index, getRelatedData) {
                $('#selected_clinicial_scale_group_options_fixed').append('<option value="' + getRelatedData.fldscalegroup + '">' + getRelatedData.fldscalegroup + '</option>');
            });
        });

    }

    $('.insert-test-option-fixed').on('click', function () {

        var test_name = $('#technologist_test_name').val();
        var input_mode = $('#getChangeDataType option:selected').val();
        var subinput_mode = $('#sub-test-option-choosed option:selected').val();
        var subtest = $('.sub-test-name').val();
        var reference = $('#common-modal-reference-fixed').val();
        var procedure = $('#common-modal-procedure-fixed').val();
        var url = $(this).attr("url");
        var formData = {
            test_name: test_name,
            input_mode: input_mode,
            subinput_mode: subinput_mode,
            subtest: subtest,
            reference: reference,
            procedure: procedure,

        };

        if (test_name == '' || input_mode == '' || subtest == '' || subinput_mode == '') {
            alert('Fill all the field');
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    showAlert(data.message);
                    $('.sub-test-name').val("");
                    $('#sub-test-option-choosed').prop('selectedIndex',0).change();
                    $('#common-modal-reference-fixed').val("");
                    $('#common-modal-procedure-fixed').val("");
                    $('.display-test-option-list').append('<tr class="remove-me-' + data.fldid + '" rel="' + data.fldid + '" rel2="' + data.fldanswer + '" ref="' + formData.reference + '" proc="' + formData.procedure + '" answertype="'+formData.subinput_mode+'"><td>' + data.fldanswer + '</td><td>' + formData.subinput_mode + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteFirstTestOption(' + data.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                    // $('.display-text-addition').attr('old_text_addition', data.fldanswer);
                } else {
                    showAlert(data.message);
                }
            }
        });
    });

    $('.update-test-option-fixed').on('click', function () {

        var test_name = $('#technologist_test_name').val();
        var input_mode = $('#getChangeDataType option:selected').val();
        var subinput_mode = $('#sub-test-option-choosed option:selected').val();
        var subtest = $('.sub-test-name').val();
        var reference = $('#common-modal-reference-fixed').val();
        var procedure = $('#common-modal-procedure-fixed').val();
        var fldid = $('#common-modal-first-test-id').val();
        var url = $(this).attr("url");
        var formData = {
            fldid: fldid,
            test_name: test_name,
            input_mode: input_mode,
            subinput_mode: subinput_mode,
            subtest: subtest,
            reference: reference,
            procedure: procedure,

        };

        if (test_name == '' || input_mode == '') {
            alert('Fill all the field');
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    showAlert(data.message);
                    $('.sub-test-name').val("");
                    $('#sub-test-option-choosed').prop('selectedIndex',0).change();
                    $('#common-modal-reference-fixed').val("");
                    $('#common-modal-procedure-fixed').val("");
                    getMainTestOption(test_name);
                } else {
                    showAlert(data.message);
                }
            }
        });
    });

    $(document).on("click", ".dynamic-option-btn-subone", function () {

        var suboption_type = $('#sub-test-option-choosed option:selected').val();
        var mainsubtest = $('.sub-test-name').val();
        var maintest = $('#technologist_test_name').val();
        var subtestoptiontype = $('#sub-test-option-choosed option:selected').val();

        if(mainsubtest == "" || subtestoptiontype == ""){
            showAlert('Please fill sub test and option field first.','error');
            return false;
        }

        $('#fixed-main-test-name').text(maintest);
        $('#common-modal-first-test').val(maintest);
        $('.fixed-sub-test-name').val(mainsubtest);
        $('#fixed-optiontype-sub-test').text(subtestoptiontype);
        $('#fixed-sub-option').val("");

        if (suboption_type == 'Clinical Scale') {
            $('#fixed-main-test-name-clinical').text(maintest);


            $("#clinical-scale-box-of-fixed").modal({
                backdrop: 'static',
                keyboard: false
            });
            getMainClinicalSubTestOption(maintest, mainsubtest,subtestoptiontype)

        } else {

            $("#common-one-modal-of-fixed").modal({
                backdrop: 'static',
                keyboard: false
            });
            getMainSubTestOption(maintest, mainsubtest,subtestoptiontype)
        }

    });


    $('.fixed-sub-common-type-insert').on('click', function () {


        var test_name = $('#technologist_test_name').val();
        var sub_test = $('.fixed-sub-test-name').val();
        var input_mode = $('#sub-test-option-choosed option:selected').val();

        var answer = $('#fixed-sub-option').val();

        var url = $(this).attr("url");
        var formData = {
            test_name: test_name,
            input_mode: input_mode,
            sub_test: sub_test,
            answer: answer
        };

        // console.log(formData);
        if (test_name == '' || input_mode == '' || answer == '') {
            alert('Fill all the field');
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    showAlert(data.message);
                    $('#fixed-sub-option').val("");
                    $('.display-test-option-list-sub').append('<tr class="remove-me-' + data.fldid + '" rel="' + data.fldid + '" rel2="' + data.fldanswer + '"><td>' + data.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteSubTestQuali(' + data.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                    // $('.display-text-addition').attr('old_text_addition', data.fldanswer);
                } else {
                    showAlert(data.message);
                }
            }
        });
    });


    $('.fixed-sub-clinical-type-insert').on('click', function () {


        var test_name = $('#technologist_test_name').val();
        var sub_test = $('.fixed-sub-test-name').val();
        var input_mode = $('#sub-test-option-choosed option:selected').val();

        var answer = $('#fixed-clinical-scale-value').val();
        var scale = $('#fixed-clinical-scale-parameter').val();
        var scalegrp = $('#selected_clinicial_scale_group_fixed').val();
        // var scalegrp = $('#selected_clinicial_scale_group option:selected').val();

        var url = $(this).attr("url");
        var formData = {
            test_name: test_name,
            input_mode: input_mode,
            sub_test: sub_test,
            answer: answer,
            scale: scale,
            scalegrp: scalegrp


        };

        // console.log(formData);


        if (test_name == '' || input_mode == '' || scalegrp == '' || scale == '' || answer == '') {
            alert('Fill all the field');
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    showAlert(data.message);
                    $('#selected_clinicial_scale_group_fixed').val("");
                    $('#fixed-clinical-scale-parameter').val("");
                    $('#fixed-clinical-scale-value').val("");
                    $('#display-clinical-scale-list-fixed').append('<tr class="remove-me-' + data.fldid + '" rel="' + data.fldid + '" rel2="' + data.fldanswer + '"><td>' + data.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick=\'deleteSubTestQualiClinical('+data.fldid+',"'+test_name+'","'+sub_test+'","'+input_mode+'")'+'\'><i class="fa fa-times"></i></a></td></tr>');
                    // $('.display-text-addition').attr('old_text_addition', data.fldanswer);
                    $('#selected_clinicial_scale_group_options_fixed').empty();
                    $.get('{{ route("techno.distinct.subtest.get.group.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&fldsubtest=' + encodeURIComponent(sub_test) + '&input_mode=' + encodeURIComponent(input_mode), function (data) {
                        $.each(data, function (index, getRelatedData) {
                            $('#selected_clinicial_scale_group_options_fixed').append('<option value="' + getRelatedData.fldscalegroup + '">' + getRelatedData.fldscalegroup + '</option>');
                        });
                    });
                } else {
                    showAlert(data.message);
                }
            }
        });
    });

    function deleteFirstTestOption(fldid) {
        var confirmCheck = confirm('Delete?');
        if (confirmCheck === false) {
            return false;
        }
        $.ajax({
            url: "{{ route('delete.test.quali') }}",
            type: "POST",
            dataType: "json",
            data: {fldid: fldid},
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    $('.remove-me-' + fldid).hide();
                    $('.sub-test-name').val("");
                    $('#sub-test-option-choosed').prop('selectedIndex',0).change();
                    $('#common-modal-reference-fixed').val("");
                    $('#common-modal-procedure-fixed').val("");
                    showAlert(data.message);
                } else {
                    showAlert(data.message);
                }
            }
        });
    }

    function deleteSubTestQuali(fldid) {
        var confirmCheck = confirm('Delete?');
        if (confirmCheck === false) {
            return false;
        }
        $.ajax({
            url: "{{ route('delete.sub.test.quali') }}",
            type: "POST",
            dataType: "json",
            data: {fldid: fldid},
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    $('.remove-me-' + fldid).hide();
                    $('#fixed-sub-option').val("");
                    showAlert(data.message);
                } else {
                    showAlert(data.message);
                }
            }
        });
    }

    function deleteSubTestQualiClinical(fldid,test_name,sub_test,input_mode) {
        var confirmCheck = confirm('Delete?');
        if (confirmCheck === false) {
            return false;
        }
        $.ajax({
            url: "{{ route('delete.sub.test.quali') }}",
            type: "POST",
            dataType: "json",
            data: {fldid: fldid},
            success: function (data) {
                if ($.isEmptyObject(data.status)) {
                    $('.remove-me-' + fldid).hide();
                    $('#selected_clinicial_scale_group_options_fixed').empty();
                    $.get('{{ route("techno.distinct.subtest.get.group.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&fldsubtest=' + encodeURIComponent(sub_test) + '&input_mode=' + encodeURIComponent(input_mode), function (data) {
                        $.each(data, function (index, getRelatedData) {
                            $('#selected_clinicial_scale_group_options_fixed').append('<option value="' + getRelatedData.fldscalegroup + '">' + getRelatedData.fldscalegroup + '</option>');
                        });
                    });
                    showAlert(data.message);
                } else {
                    showAlert(data.message);
                }
            }
        });
    }
</script>
