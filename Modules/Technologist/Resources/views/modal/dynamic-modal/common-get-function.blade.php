<script>
    function getTestOption(test_name, input_mode) {

        $('#common-modal-first-test').empty(null);
        $('#common-modal-first-test').text(test_name);
        $('#common-modal-first-type').empty(null);
        $('#common-modal-first-type').text(input_mode);

        $('.common-modal-first-test').empty(null);
        $('.common-modal-first-test').text(test_name);
        $('.common-modal-first-type').empty(null);
        $('.common-modal-first-type').text(input_mode);

        $('.display-test-option-list').empty();

        $.get('{{ route("techno.option.test.get.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&input_mode=' + encodeURIComponent(input_mode), function (data) {
            $.each(data, function (index, getRelatedData) {
                // console.log(getRelatedData);
                $('.display-test-option-list').append('<tr class="remove-me-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '"><td>' + getRelatedData.fldanswer + '</td></tr>');
                $('.display-text-addition').attr('old_text_addition', getRelatedData.fldanswer);
            });
        });
    }

    function getDistinctGroup(test_name, input_mode) {
        $('#selected_clinicial_scale_group_options').empty();
        $.get('{{ route("techno.distinct.test.get.group.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&input_mode=' + encodeURIComponent(input_mode), function (data) {
            $.each(data, function (index, getRelatedData) {
                $('#selected_clinicial_scale_group_options').append('<option value="' + getRelatedData.fldscalegroup + '">' + getRelatedData.fldscalegroup + '</option>');
            });
        });
    }

    function getClinicianScale(test_name, input_mode) {
        $('#clinical-scale-test').empty(null);
        $('#clinical-scale-test').append(test_name);
        $('#display-clinical-scale-list').empty();

        $.get('{{ route("techno.option.test.clinical.qualitative") }}?test_name=' + encodeURIComponent(test_name) + '&input_mode=' + encodeURIComponent(input_mode), function (data) {
            var num = 1;
            $.each(data, function (index, getRelatedData) {
                $('#display-clinical-scale-list').append('<tr class="clinicial-scale-' + getRelatedData.fldid + '" rel="' + getRelatedData.fldid + '" rel2="' + getRelatedData.fldanswer + '" rel3="' + getRelatedData.fldscalegroup + '" rel4="' + getRelatedData.fldscale + '" ><td>' + num + '</td><td>' + getRelatedData.fldanswer + '</td><td>' + getRelatedData.fldscale + '</td><td>' + getRelatedData.fldscalegroup + '</td></tr>');
                num++;
            });
        });
    }

</script>
