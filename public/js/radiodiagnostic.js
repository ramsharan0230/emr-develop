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

$('#onChangeDataType').on('change', function (e) {
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
    // if (selected === 'Custom Components') {
    //     // Dynamic Option data-type attr
    //     $('.dynamic-option-btn').attr('data-target', '#custom-components-box');
    // }
    if (selected === 'Fixed Components' || selected === 'Custom Components') {
        // Dynamic Option data-type attr
        getMainTestOption(testid)
        $('.dynamic-option-btn').attr('data-target', '#fixed-components-box');
    }
}

// on click dynamic-option-btn
$(".dynamic-option-btn").on("click", function () {
    $('.modal').find('input[type=text], input[type=number]').val("");
    // var testid = $('#technologist_test_name').val();
    var test_name = $('#technologist_test_name').val();
    var input_mode = $('#getChangeDataType option:selected').val();
    if (test_name === '' || input_mode === '') {
        alert('Please Select Test Name and Input Mode');
        return false;
    }

    if (input_mode === 'Fixed Components') {
        getMainTestOption(test_name)
    }

    if (input_mode === 'Custom Components') {
        getMainTestOption(test_name)
    }

    if(input_mode === 'Text Addition'){
        getTextAddition(test_name, input_mode, "radiology");
    }

    if (input_mode !== 'No Selection' || input_mode !== 'Fixed Components' || input_mode !== 'Custom Components' || input_mode !== 'Text Addition') {
        getTestOption(test_name, input_mode, "radiology");
        getClinicianScale(test_name, input_mode, "radiology");
        getDistinctGroup(test_name, input_mode, "radiology");
    }
});

function getTextAddition(test_name, input_mode, diagoType = "laboratory"){
    var url = baseUrl + "/diagnosis/radiology/getTextAddition";
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        diagoType: diagoType
    };

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.status)) {
                $('#text_addition').val("");
                if(data.result){
                    $('#text_addition').val(data.result.fldanswer);
                }
            } else {
                showAlert(data.message);
            }
        }
    });
}

$(document).ready(function () {
    $(".scale_group").select2();
    $(document).on("keydown", ".select2-search__field", function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === '13') {
            //alert('You pressed a "enter" key in textbox');
            $('.scale_group').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
        }
    });
});


$('.insert-test-option').on('click', function () {
    var test_name = $('#technologist_test_name').val();
    var input_mode = $('#getChangeDataType option:selected').val();
    var answer = $('#common-modal-first-option').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        answer: answer,
        diagoType: "radiology"
    };

    if (test_name === '' || input_mode === '' || answer === '') {
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
                $('#common-modal-first-option').val("");
                getTestOption(test_name, input_mode, "radiology");
            } else {
                showAlert(data.message);
            }
        }
    });
});

$('.insert-sub-test-option').on('click', function () {
    var test_name = $('#technologist_test_name').val();
    var input_mode = $('#custom_option_type option:selected').val();
    var subtest = $('#common-modal-first-sub-test').val();
    var reference = $('#common-modal-reference').val();
    var procedure = $('#common-modal-procedure').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        answer: answer,
        diagoType: "radiology"
    };

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
                getTestOption(test_name, input_mode, "radiology");
            } else {
                showAlert(data.message);
            }
        }
    });
});
$('.insert-clinical-scale').on('click', function () {
    var test_name = $('#technologist_test_name').val();
    var input_mode = $('#getChangeDataType option:selected').val();
    var scale_group = $('#selected_clinicial_scale_group').val();
    var parameter = $('#clinical-scale-parameter').val();
    var value = $('#clinical-scale-value').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        parameter: parameter,
        scale_group: scale_group,
        value: value,
        diagoType: "radiology"
    };

    if (test_name === '' || input_mode === '' || parameter === '' || scale_group === '' || value === '') {
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
                $('#selected_clinicial_scale_group').val("");
                $('#clinical-scale-parameter').val("");
                $('#clinical-scale-value').val("");
                getTestOption(test_name, input_mode, "radiology");
                getClinicianScale(test_name, input_mode, "radiology");
                getDistinctGroup(test_name, input_mode, "radiology");
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(document).on("click", ".display-test-option-list tr", function () {
    var id = $(this).attr('rel');
    var answer = $(this).attr('rel2');
    var answertype = $(this).attr('answertype');
    $('#sub-test-option-choosed').val(answertype).change();

    $('#common-modal-first-option').val(null);
    $('#common-modal-first-option').attr('rel', '');
    $('#common-modal-first-option').val(answer);
    $('#common-modal-first-option').attr('rel', id);
    $('#common-modal-first-test-id').val("");
    $('#common-modal-reference-fixed').val("");
    $('#common-modal-procedure-fixed').val("");

    $('.sub-test-name').val(answer)
    $('#common-modal-first-test-id').val(id);
    if($(this).attr('ref') != "null"){
        $('#common-modal-reference-fixed').val($(this).attr('ref'));
    }
    if($(this).attr('proc') != "null"){
        $('#common-modal-procedure-fixed').val($(this).attr('proc'));
    }

    $('.display-test-option-list').find('.select_td').removeClass("select_td");
    $(this).addClass('select_td');
});

$(document).on("click", "#display-clinical-scale-list tr", function () {
    var id = $(this).attr('rel');
    var answer = $(this).attr('rel2');
    var scale_group = $(this).attr('rel3');
    var scale = $(this).attr('rel4');

    $('#clinical-scale-parameter').val(null);
    $('#clinical-scale-parameter').attr('rel', '');
    $('#clinical-scale-parameter').val(answer);
    $('#clinical-scale-parameter').attr('rel', id);
    $('#clinical-scale-value').val(null);
    $('#clinical-scale-value').val(scale);
    $('#selected_clinicial_scale_group').val('');
    // $('option:selected', 'select[name="selected_clinicial_scale_group"]').removeAttr('selected');
    // $('select[name="selected_clinicial_scale_group"]').find('option[value="' + scale_group + '"]').attr("selected", true);
    $('#selected_clinicial_scale_group').val(scale_group);

    $('#display-clinical-scale-list').find('.select_td').removeClass("select_td");
    $(this).addClass('select_td');
});

$('.delete-test-option').on('click', function () {
    if (confirm("Delete?") === false) {
        return false;
    }
    var id = $('#common-modal-first-option').attr('rel');
    var url = $(this).attr("url");
    var formData = {
        id: id,
        diagoType: "radiology"
    };

    if (id == '') {
        alert('Please Select First');
        return false;
    }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.status)) {
                $('.remove-me-' + id).remove();
                $('#common-modal-first-option').val("");
                showAlert(data.message);
            } else {
                showAlert(data.message);
            }
        }
    });
});

$('.delete-clinical-scale').on('click', function () {
    var id = $('#clinical-scale-parameter').attr('rel');
    var url = $(this).attr("url");
    var formData = {
        id: id,
        diagoType: "radiology"
    };

    if (id == '') {
        alert('Please Select First');
        return false;
    }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.status)) {
                $('.clinicial-scale-' + id).remove();
                $('#selected_clinicial_scale_group').val("");
                $('#clinical-scale-parameter').val("");
                $('#clinical-scale-value').val("");
                showAlert(data.message);
            } else {
                showAlert(data.message);
            }
        }
    });
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
        $('#selected_clinicial_scale_group').val('');
        // $('option:selected', 'select[name="selected_clinicial_scale_group"]').removeAttr('selected');

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

$(document).on("click", "#technologist_add_reset", function () {
    $('#technologist_add_reset').css('display', 'none');
    $('#technologist_save').css('display', 'block');
    clearFields();
});

function clearFields() {
    $('#technologist_test_name').val(null);
    $('#technologist_collection').val(null);
    $('#technologist_sensitivity').val(null);
    $('#technologist_specificity').val(null);
    $('#technologist_critical').val(null);
    $('#technologist_description').val(null);
    $('#technologist_comment').val(null);

    $('select[name="technologist_categories"]').val($("select[name='technologist_categories'] option:first").val());
    $('select[name="technologist_constant"]').val($("select[name='technologist_constant'] option:first").val());
    $('select[name="technologist_specimen"]').val($("select[name='technologist_specimen'] option:first").val());
    $('select[name="technologist_datatype"]').val($("select[name='technologist_datatype'] option:first").val());
    $('select[name="technologist_vial"]').val($("select[name='technologist_vial'] option:first").val());
    $('select[name="technologist_input_mode"]').val($("select[name='technologist_input_mode'] option:first").val());

    // $('option:selected', 'select[name="technologist_categories"]').removeAttr('selected');
    // $('option:selected', 'select[name="technologist_constant"]').removeAttr('selected');
    // $('option:selected', 'select[name="technologist_specimen"]').removeAttr('selected');
    // $('option:selected', 'select[name="technologist_datatype"]').removeAttr('selected');
    // $('option:selected', 'select[name="technologist_vial"]').removeAttr('selected');
    // $('option:selected', 'select[name="technologist_input_mode"]').removeAttr('selected');
}

$('.insert-text-addition').on('click', function () {
    var test_name = $(this).closest('.modal').find('.common-modal-first-test').text();
    var input_mode = "Text Addition";
    var answer = $('#text_addition').val();
    var url = $(this).attr('url');
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        answer: answer,
        diagoType: "radiology"
    };

    if (test_name === '' || input_mode === '' || answer === '') {
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
            } else {
                showAlert(data.message);
            }
        }
    });
});

$('.delete-text-addition').on('click', function () {
    var test_name = $(this).closest('.modal').find('.common-modal-first-test').text();
    var input_mode = "Text Addition";
    var url = $(this).attr('url');
    var formData = {
        test_name: test_name,
        input_mode: input_mode,
        diagoType: "radiology"
    };

    if (test_name === '' || input_mode === '') {
        alert('Fill all the field');
        return false;
    }

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.status)) {
                $('#text_addition').val("");
                showAlert(data.message);
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(document).on("click", "#technologist_edit_reset", function () {
    var test_name = $('#technologist_test_name').val();
    if (test_name == '') {
        alert('Please Select Test First');
        return false;
    }
    $('#technologist_edit_reset').css('display', 'none');
    $('#technologist_update').css('display', 'block');
});

// Add Technologist
$('#technologist_save').click(function () {
    var test_name = $('#technologist_test_name').val();
    var collection = $('#technologist_collection').val();
    var vial = $('#technologist_vial option:selected').val();
    var sensitivity = $('#technologist_sensitivity').val();
    var specificity = $('#technologist_specificity').val();
    var critical = $('#technologist_critical').val();
    var description = $('#technologist_description').val();
    var comment = $('#technologist_comment').val();
    var categories = $('option:selected', 'select[name="technologist_categories"]').val();
    var constant = $('option:selected', 'select[name="technologist_constant"]').val();
    var specimen = $('option:selected', 'select[name="technologist_specimen"]').val();
    var datatype = $('option:selected', 'select[name="technologist_datatype"]').val();
    var input_mode = $('option:selected', 'select[name="technologist_input_mode"]').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        collection: collection,
        vial: vial,
        sensitivity: sensitivity,
        specificity: specificity,
        critical: critical,
        description: description,
        comment: comment,
        categories: categories,
        constant: constant,
        specimen: specimen,
        datatype: datatype,
        input_mode: input_mode,
        diagoType: "radiology"
    };

    if (test_name == '' || datatype == '') {
        alert('Please Insert Test Name and Data Type');
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
                getNewTestList();
                $('#technologist_add_reset').css('display', 'block');
                $('#technologist_save').css('display', 'none');
                clearFields();
            } else {
                showAlert(data.message);
            }
        }
    });
});

function getNewTestList() {
    $('.get-selected-test-data').empty();
    $.get('test/list', function (data) {
        $.each(data, function (index, getNewTestList) {
            $('.get-selected-test-data').append('<tr rel="' + getNewTestList.col + '"><td>' + getNewTestList.col + '</td></tr>');
        });
    });
}

// Update Technologist
$('#technologist_update').click(function () {
    var test_name = $('#technologist_test_name').val();
    var collection = $('#technologist_collection').val();
    var vial = $('#technologist_vial option:selected').val();
    var sensitivity = $('#technologist_sensitivity').val();
    var specificity = $('#technologist_specificity').val();
    var critical = $('#technologist_critical').val();
    var description = $('#technologist_description').val();
    var comment = $('#technologist_comment').val();
    var categories = $('option:selected', 'select[name="technologist_categories"]').val();
    var constant = $('option:selected', 'select[name="technologist_constant"]').val();
    var specimen = $('option:selected', 'select[name="technologist_specimen"]').val();
    var datatype = $('option:selected', 'select[name="technologist_datatype"]').val();
    var input_mode = $('option:selected', 'select[name="technologist_input_mode"]').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        collection: collection,
        vial: vial,
        sensitivity: sensitivity,
        specificity: specificity,
        critical: critical,
        description: description,
        comment: comment,
        categories: categories,
        constant: constant,
        specimen: specimen,
        datatype: datatype,
        input_mode: input_mode,
        diagoType: "radiology"
    };

    if (test_name == '' || datatype == '') {
        alert('Please Insert Test Name and Data Type');
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
                $('#technologist_edit_reset').css('display', 'block');
                $('#technologist_update').css('display', 'none');
            } else {
                showAlert(data.message);
            }
        }
    });
});

// Delete Technologist
$('#technologist_delete').click(function () {
    var test_name = $('#technologist_test_name').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        diagoType: "radiology"
    };

    if (test_name == '') {
        alert('Please Select Variable To Delete');
        return false;
    }

    $("#confirmation_dialog_technologist").html("You want to delete " + test_name + "?");
    $("#confirmation_dialog_technologist").dialog({
        resizable: false,
        modal: true,
        title: "Are you sure?",
        height: 250,
        width: 400,
        buttons: {
            "Yes": function () {
                $(this).dialog('close');
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.status)) {
                            showAlert(data.message);
                            getNewTestList();
                            $('#technologist_test_name').val(null);
                            $('#technologist_collection').val(null);
                            $('#technologist_sensitivity').val(null);
                            $('#technologist_specificity').val(null);
                            $('#technologist_critical').val(null);
                            $('#technologist_description').val(null);
                            $('#technologist_comment').val(null);

                            $('option:selected', 'select[name="technologist_categories"]').removeAttr('selected');
                            $('option:selected', 'select[name="technologist_constant"]').removeAttr('selected');
                            $('option:selected', 'select[name="technologist_specimen"]').removeAttr('selected');
                            $('option:selected', 'select[name="technologist_datatype"]').removeAttr('selected');
                            $('option:selected', 'select[name="technologist_input_mode"]').removeAttr('selected');
                        } else {
                            showAlert(data.message);
                        }
                    }
                });
            },
            "No": function () {
                $(this).dialog('close');
                return false;
            }
        }
    });
});

// Pop up modal for Test Name Technologist
$('#edit_radiology_test_name').click(function () {
    var test_name = $('#technologist_test_name').val();
    if (test_name == '') {
        alert('Exam Name Is Empty');
        return false;
    }

    var really = confirm("This will effect in Multiple Place");
    if(!really) {
        return false
    }

    $('#exam_name_new_value').val(test_name);
    $("#exam_name_update").modal("show");

    // $("#confirmation_dialog_technologist").html("This will effect in Multiple Place");
    // $("#confirmation_dialog_technologist").dialog({
    //     resizable: false,
    //     modal: true,
    //     title: "Are you sure?",
    //     height: 250,
    //     width: 400,
    //     buttons: {
    //         "Yes": function () {
    //             $(this).dialog('close');
    //             $('#test_name_new_value').val(test_name);
    //             $("#test_name_update").modal("show");
    //         },
    //         "No": function () {
    //             $(this).dialog('close');
    //             return false;
    //         }
    //     }
    // });
});


$('#update_technologist_test_name').click(function () {
    var test_name = $('#technologist_test_name').val();
    var test_name_new = $('#exam_name_new_value').val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        test_name_new: test_name_new,
        diagoType: "radiology"
    };
    if (test_name_new = '') {
        alert('Please Fill The Input');
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
                // getNewTestList();
                // getNewVariableData(data.test_name);
                $('#technologist_test_name').val($('#exam_name_new_value').val());
                $('#table-scroll-technologist').find('tr[rel="'+test_name+'"]').children('td').html($('#exam_name_new_value').val());
                $('#table-scroll-technologist').find('tr[rel="'+test_name+'"]').attr('rel',$('#exam_name_new_value').val());
                $("#exam_name_update").modal("hide");
            } else {
                showAlert(data.message);
            }
        }
    });
});

function getNewVariableData(id) {
    var testid = id;
    // ajax
    $.get('test/related-value?testid=' + testid, function (data) {
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

        $('option:selected', 'select[name="technologist_categories"]').removeAttr('selected');
        $('option:selected', 'select[name="technologist_constant"]').removeAttr('selected');
        $('option:selected', 'select[name="technologist_specimen"]').removeAttr('selected');
        $('option:selected', 'select[name="technologist_datatype"]').removeAttr('selected');
        $('option:selected', 'select[name="technologist_input_mode"]').removeAttr('selected');

        // Get Data
        $('#technologist_test_name').val(data.fldtestid);
        $('#technologist_test_name').attr('rel', data.fldtestid);
        $('#technologist_collection').val(data.fldcollection);
        $('#technologist_sensitivity').val(data.fldsensitivity);
        $('#technologist_specificity').val(data.fldspecificity);
        $('#technologist_critical').val(data.fldcritical);
        $('#technologist_description').val(data.flddetail);
        $('#technologist_comment').val(data.fldcomment);

        $('select[name="technologist_categories"]').find('option[value="' + data.fldcategory + '"]').attr("selected", true);
        $('select[name="technologist_constant"]').find('option[value="' + data.fldsysconst + '"]').attr("selected", true);
        $('select[name="technologist_specimen"]').find('option[value="' + data.fldspecimen + '"]').attr("selected", true);
        $('select[name="technologist_datatype"]').find('option[value="' + data.fldtype + '"]').attr("selected", true);
        $('select[name="technologist_input_mode"]').find('option[value="' + data.fldoption + '"]').attr("selected", true);
    });
};

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

function getQuantitativeTestPara(test_name) {
    $('.display-quantitative-test-para').empty();
    $.get('quantitative/get-test-parameter-mu?test_name=' + test_name + '&diagoType=radiology', function (data) {
        $.each(data, function (index, getTestPara) {
            $('.display-quantitative-test-para').append('<tr class="test_para_remove_' + getTestPara.fldid + '" rel="' + getTestPara.fldid + '"><td>' + getTestPara.fldid + '</td><td>' + (getTestPara.fldptsex ? getTestPara.fldptsex : '') + '</td><td>' + (getTestPara.fldagegroup ? getTestPara.fldagegroup : '') + '</td><td>' + (getTestPara.fldnormal ? getTestPara.fldnormal : '') + '</td><td>' + (getTestPara.fldlow ? getTestPara.fldlow : '') + '</td><td>' + (getTestPara.fldhigh ? getTestPara.fldhigh : '') + '</td><td>' + (getTestPara.fldunit ? getTestPara.fldunit : '') + '</td><td>' + (getTestPara.fldmethod ? getTestPara.fldmethod : '') + '</td><td>' + (getTestPara.fldsensitivity ? getTestPara.fldsensitivity : '') + '</td><td>' + (getTestPara.fldspecificity ? getTestPara.fldspecificity : '') + '</td></tr>');
        });
    });
}

$('input[name="quantitative_units"]').on("click", function () {
    var value = $('input[name="quantitative_units"]:checked').val();
    var test_name = $('#technologist_test_name').val();
    $('.display-quantitative-test-para').empty();
    $.get('quantitative/get-test-parameter-' + value + '?test_name=' + test_name, function (data) {
        if (value == 'mu') {
            $.each(data, function (index, getTestPara) {
                $('.display-quantitative-test-para').append('<tr class="test_para_remove_' + getTestPara.fldid + '" rel="' + getTestPara.fldid + '"><td>' + getTestPara.fldid + '</td><td>' + (getTestPara.fldptsex ? getTestPara.fldptsex : '') + '</td><td>' + (getTestPara.fldagegroup ? getTestPara.fldagegroup : '') + '</td><td>' + (getTestPara.fldnormal ? getTestPara.fldnormal : '') + '</td><td>' + (getTestPara.fldlow ? getTestPara.fldlow : '') + '</td><td>' + (getTestPara.fldhigh ? getTestPara.fldhigh : '') + '</td><td>' + (getTestPara.fldunit ? getTestPara.fldunit : '') + '</td><td>' + (getTestPara.fldmethod ? getTestPara.fldmethod : '') + '</td><td>' + (getTestPara.fldsensitivity ? getTestPara.fldsensitivity : '') + '</td><td>' + (getTestPara.fldspecificity ? getTestPara.fldspecificity : '') + '</td></tr>');
            });
        } else {
            $.each(data, function (index, getTestPara) {
                $('.display-quantitative-test-para').append('<tr class="test_para_remove_' + getTestPara.fldid + '" rel="' + getTestPara.fldid + '"><td>' + getTestPara.fldid + '</td><td>' + (getTestPara.fldptsex ? getTestPara.fldptsex : '') + '</td><td>' + (getTestPara.fldagegroup ? getTestPara.fldagegroup : '') + '</td><td>' + (getTestPara.fldnormal ? getTestPara.fldnormal : '') + '</td><td>' + (getTestPara.fldlow ? getTestPara.fldlow : '') + '</td><td>' + (getTestPara.fldhigh ? getTestPara.fldhigh : '') + '</td><td>' + (getTestPara.fldunit ? getTestPara.fldunit : '') + '</td><td>' + (getTestPara.fldmethod ? getTestPara.fldmethod : '') + '</td><td>' + (getTestPara.fldsensitivity ? getTestPara.fldsensitivity : '') + '</td><td>' + (getTestPara.fldspecificity ? getTestPara.fldspecificity : '') + '</td></tr>');
            });
        }
    });
});

$(document).on("click", ".display-quantitative-test-para tr", function () {
    $("#get_previous_test_fldid").val(null);
    $('option:selected', 'select[name="selected_quantitative_method"]').removeAttr('selected');
    $("#quantitative_valide_range").val(null);
    $("#quantitative_matric_unit").val(null);
    $("#quantitative_sensitivity").val(null);
    $("#quantitative_specificity").val(null);
    $('option:selected', 'select[name="quantitative_age_group"]').removeAttr('selected');
    $('option:selected', 'select[name="quantitative_gender"]').removeAttr('selected');
    $("#quantitative_lower").val(null);
    $("#quantitative_upper").val(null);
    $("#quantitative_normal").val(null);
    $("#quantitative_unit").val(null);


    var fldid = $(this).attr('rel');
    $.get('quantitative/get-test-parameter?fldid=' + fldid + '&diagoType=radiology', function (data) {
        $("#get_previous_test_fldid").val(data.fldid);
        $('select[name="selected_quantitative_method"]').find('option[value="' + data.fldmethod + '"]').attr("selected", true).change();
        $("#quantitative_valide_range").val(data.fldminimum);
        $("#quantitative_matric_unit").val(data.fldmaximum);
        $("#quantitative_sensitivity").val(data.fldsensitivity);
        $("#quantitative_specificity").val(data.fldspecificity);
        $('select[name="quantitative_age_group"]').find('option[value="' + data.fldagegroup + '"]').attr("selected", true);
        $('select[name="quantitative_gender"]').find('option[value="' + data.fldptsex + '"]').attr("selected", true);
        $("#quantitative_lower").val(data.fldlow);
        $("#quantitative_upper").val(data.fldhigh);
        $("#quantitative_normal").val(data.fldnormal);
        $("#quantitative_unit").val(data.fldunit);
    });
});

// Add Method Variables
$('#technologist_method_insert').click(function () {
    var flclass = $("#technologist_method_var").val();
    var url = $(this).attr("url");
    var formData = {
        flclass: flclass,
        diagoType: "radiology"
    };

    if (flclass == '') {
        alert('Please Insert Variable');
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
                getMethodVariables();
                //location.reload();
            } else {
                showAlert(data.message);
            }
        }
    });
});

function getMethodVariables() {
    $('#get_quantitative_method').empty();
    $('.method-box-list').empty();
    $.get('quantitative/getMethodVariables', function (data) {
        var num = 1;
        $('#get_quantitative_method').append("<option value=''>---Select---</option>");
        $.each(data, function (index, getVariables) {
            $('#get_quantitative_method').append('<option value="' + getVariables.col + '">' + getVariables.col + '</option>');
            var html = '<li class="list-group-item">' +
                '<input type="radio" name="selected_quantitative_method" id="radio-variable-' + num + '" value="' + getVariables.col + '">&nbsp;&nbsp;' +
                '<label for="radio-variable-' + num + '">' + getVariables.col + '</label>' +
                '</li>';
            $('.method-box-list').append(html);
            num++;
        });
    });
}

// Delete Method Variables
$('#technologist_method_delete').click(function () {
    var fldmethod = $("input[name='selected_quantitative_method']:checked").val();
    var url = $(this).attr("url");
    var formData = {
        fldmethod: fldmethod,
        diagoType: "radiology"
    };

    if (fldmethod == '') {
        alert('Please Select Variable To Delete');
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
                getMethodVariables();
                //location.reload();
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(".hide_this_modal").click(function () {
    $("#technologist_quantitative_method_variable").modal("hide");
});

$(".insert-quantitative-test-para").on("click", function () {
    var test_name = $("#technologist_test_name").val();
    var method = $("#get_quantitative_method option:selected").val();
    var valid_range = $("#quantitative_valide_range").val();
    var matric_unit = $("#quantitative_matric_unit").val();
    var sensitivity = $("#quantitative_sensitivity").val();
    var specificity = $("#quantitative_specificity").val();
    var age_group = $("#quantitative_age_group option:selected").val();
    var gender = $("#quantitative_gender option:selected").val();
    var lower = $("#quantitative_lower").val();
    var upper = $("#quantitative_upper").val();
    var normal = $("#quantitative_normal").val();
    var unit = $("#quantitative_unit").val();
    var url = $(this).attr("url");
    var formData = {
        test_name: test_name,
        method: method,
        valid_range: valid_range,
        matric_unit: matric_unit,
        sensitivity: sensitivity,
        specificity: specificity,
        age_group: age_group,
        gender: gender,
        lower: lower,
        upper: upper,
        normal: normal,
        unit: unit,
        diagoType: "radiology"
    };

    if (test_name == '') {
        alert('Please fill all the test parameter fields');
        return false;
    }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if (data.status) {
                current.closest('.modal').find('input[type=text], input[type=number]').val("");
                current.closest('.modal').find("select").prop('selectedIndex',0).change();
                showAlert(data.message);
                getQuantitativeTestPara(test_name);
            } else {
                showAlert(data.message,'error');
            }
        }
    });
});

$(".edit-quantitative-test-para").on("click", function () {
    var test_name = $("#technologist_test_name").val();
    var fldid = $("#get_previous_test_fldid").val();
    var method = $("#get_quantitative_method option:selected").val();
    var valid_range = $("#quantitative_valide_range").val();
    var matric_unit = $("#quantitative_matric_unit").val();
    var sensitivity = $("#quantitative_sensitivity").val();
    var specificity = $("#quantitative_specificity").val();
    var age_group = $("#quantitative_age_group option:selected").val();
    var gender = $("#quantitative_gender option:selected").val();
    var lower = $("#quantitative_lower").val();
    var upper = $("#quantitative_upper").val();
    var normal = $("#quantitative_normal").val();
    var unit = $("#quantitative_unit").val();
    var url = $(this).attr("url");
    var formData = {
        fldid: fldid,
        method: method,
        valid_range: valid_range,
        matric_unit: matric_unit,
        sensitivity: sensitivity,
        specificity: specificity,
        age_group: age_group,
        gender: gender,
        lower: lower,
        upper: upper,
        normal: normal,
        unit: unit,
        diagoType: "radiology"
    };

    if (fldid == '') {
        alert('Please Select Which Test To Edit');
        return false;
    }

    // if (age_group == '' || gender == '' || lower == '' || upper == '' || normal == '') {
    //     alert('Please fill all the test parameter fields');
    //     return false;
    // }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                getQuantitativeTestPara(test_name);
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(".delete-quantitative-test-para").on("click", function () {
    var fldid = $("#get_previous_test_fldid").val();
    var url = $(this).attr("url");
    var formData = {
        fldid: fldid,
        diagoType: "radiology"
    };

    if (fldid == '') {
        alert('Please Select Which Test To Delete');
        return false;
    }

    if(!confirm("Do you really want to delete?")){
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                $(".test_para_remove_" + fldid).remove();
            } else {
                showAlert(data.message);
            }
        }
    });
});
