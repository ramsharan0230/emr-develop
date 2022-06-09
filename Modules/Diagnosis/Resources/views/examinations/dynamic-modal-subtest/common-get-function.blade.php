
<script>
    function getTestOption(test_name, input_mode) {
    $('#common-modal-first-test').empty(null);
    $('#common-modal-first-test').append(test_name);
    $('#common-modal-first-type').empty(null);
    $('#common-modal-first-type').append(input_mode);

    $('.common-modal-first-test').empty(null);
    $('.common-modal-first-test').append(test_name);
    $('.common-modal-first-type').empty(null);
    $('.common-modal-first-type').append(input_mode);

    $('.display-test-option-list').empty();

    $.get('{{ route("examination.option.test.get.qualitative") }}?test_name=' + test_name + '&input_mode=' + input_mode, function (data) {
        $.each(data, function (index, getRelatedData) {
            $('.display-test-option-list').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '"><td>' + getRelatedData.fldanswer + '</td></tr>');
            $('.display-text-addition').attr('old_text_addition', getRelatedData.fldanswer);
        });
    });
    // var old_text_addition = $('.display-text-addition').attr("old_text_addition");
    // var editor = CKEDITOR.instances.text_addition;
    // CKEDITOR.instances.editor.setData( old_text_addition );
}

function getDistinctGroup(test_name, input_mode) {
    $('#selected_clinicial_scale_group').empty();
    $.get('{{ route("examination.distinct.test.get.group.qualitative") }}?test_name=' + test_name + '&input_mode=' + input_mode, function (data) {
        $.each(data, function (index, getRelatedData) {
            $('#selected_clinicial_scale_group').append('<option value="' + getRelatedData.fldscalegroup + '">' + getRelatedData.fldscalegroup + '</option>');
        });
    });
}

function getClinicianScale(test_name, input_mode) {
    $('#clinical-scale-test').empty(null);
    $('#clinical-scale-test').append(test_name);
    $('#display-clinical-scale-list').empty();
    $.get('{{ route("examination.option.test.clinical.qualitative") }}?test_name=' + test_name + '&input_mode=' + input_mode, function (data) {
        var num = 1;
        $.each(data, function (index, getRelatedData) {
            $('#display-clinical-scale-list').append('<tr class="clinicial-scale-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '" rel3="' + getRelatedData.fldscalegroup + '" rel4="' + getRelatedData.fldscale + '" ><td>' + num + '</td><td>' + getRelatedData.fldanswer + '</td><td>' + getRelatedData.fldscale + '</td><td>' + getRelatedData.fldscalegroup + '</td></tr>');
            num++;
        });
    });
}

</script>
