<div class="modal fade" id="fixed-components-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
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
                                    </select>
                                </div>
                                <a href="javascript:;" class="btn btn-primary dynamic-option-btn-subone"><i class="fas fa-edit"></i>&nbsp;Option</a>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Reference</label>
                                </div>
                                <div class="col-4">
                                    <label id="common-modal-reference-label"><input type="text" class="form-control" name="common-modal-reference" id="common-modal-reference" value=""></label>
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="clinicalexam-label-small">Procedure</label>
                                </div>
                                <div class="col-4">
                                    <label id="common-modal-procedure-label"><input type="text" class="form-control" name="common-modal-procedure" id="common-modal-procedure" value=""></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box half_box">
                                <div class="box__btn__clinical">
                                    <button type="button" class="btn btn-primary insert-test-option-fixed" url="{{ route('insert.first.level.test.option') }}"><i class="fas fa-plus"></i> Add</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <tbody class="display-test-option-list">

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
                                    <label><input name="fixed-sub-test-name" class="form-control fixed-sub-test-name"/> </label>
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
                                    <label><input name="fixed-sub-test-name" class="fixed-sub-test-name"/> </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-3">
                                    <label class="fixed-clinicalexam-label-small">Group</label>
                                </div>
                                <div class="col-4">
                                    <select name="selected_clinicial_scale_group" id="selected_clinicial_scale_group" class="scale_group"></select>
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
                                    <tbody id="display-clinical-scale-list">

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
    function getMainTestOption(test_name) {


        $('.display-test-option-list').empty();

        $.get('{{ route("test.quali.old.data") }}?fldtestid=' + test_name, function (data) {

            $.each(data.data, function (index, getRelatedData) {
                // console.log(getRelatedData)
                $('.display-test-option-list').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldsubtest + '"><td>' + getRelatedData.fldsubtest + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteFirstTestOption(' + getRelatedData.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');

            });
        });

    }


    function getMainSubTestOption(test_name, subtest_name) {

        $('.display-test-option-list-sub').empty();

        $.get('{{ route("sub.test.quali.old.data") }}?fldtestid=' + test_name + '&fldsubtest=' + subtest_name, function (data) {
            $.each(data.data, function (index, getRelatedData) {
                $('.display-test-option-list-sub').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '"><td>' + getRelatedData.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteSubTestQuali(' + getRelatedData.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                $('.display-text-addition').attr('old_text_addition', getRelatedData.fldanswer);
            });
        });

    }

    $('.insert-test-option-fixed').on('click', function () {

        var test_name = $('#technologist_test_name').val();
        var input_mode = $('#getChangeDataType option:selected').val();
        var subinput_mode = $('#sub-test-option-choosed option:selected').val();
        var subtest = $('.sub-test-name').val();
        var reference = $('#common-modal-reference').val();
        var procedure = $('#common-modal-procedure').val();
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
                    $('.display-test-option-list').append('<tr class="remove-me-' + data.fldid + '" rel="' + data.fldid + '" rel2="' + data.fldanswer + '"><td>' + data.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteFirstTestOption(' + data.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                    // $('.display-text-addition').attr('old_text_addition', data.fldanswer);
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

        $('#fixed-main-test-name').text(maintest);
        $('#common-modal-first-test').val(maintest);
        $('.fixed-sub-test-name').val(mainsubtest);
        $('#fixed-optiontype-sub-test').text(subtestoptiontype);

        if (suboption_type == 'Clinical Scale') {
            $('#fixed-main-test-name-clinical').text(maintest);


            $("#clinical-scale-box-of-fixed").modal({
                backdrop: 'static',
                keyboard: false
            });
            getMainSubTestOption(maintest, mainsubtest)

        } else {

            $("#common-one-modal-of-fixed").modal({
                backdrop: 'static',
                keyboard: false
            });
            getMainSubTestOption(maintest, mainsubtest)
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
        var scalegrp = $('#selected_clinicial_scale_group option:selected').val();

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
                    $('#display-clinical-scale-list').append('<tr class="remove-me-' + data.fldid + '" rel="' + data.fldid + '" rel2="' + data.fldanswer + '"><td>' + data.fldanswer + '</td><td><a href="javascript:;" class="btn btn-danger btn-sm-in" onclick="deleteSubTestQuali(' + data.fldid + ')"><i class="fa fa-times"></i></a></td></tr>');
                    // $('.display-text-addition').attr('old_text_addition', data.fldanswer);
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
                    showAlert(data.message);
                } else {
                    showAlert(data.message);
                }
            }
        });
    }
</script>
