$('#service').change(function () {

    if ($('#service').val() == 'emergency') {
        $('#EmergencyModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'consultant') {
        $('#consultantModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'inpatient') {
        $('#InPatientModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'anc') {
        $('#ancModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'diagnostic') {
        $('#diagnosticModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'delivery') {
        $('#deliveryModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'laboratory') {
        $('#laboratoryModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'culture') {
        $('#cultureModal').modal('toggle');
        $(this).val('');
    }


    if ($('#service').val() == 'culture_specimens') {
        $('#cultureSpecimensModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'free_service') {
        $('#FreeServiceModal').modal('toggle');
        $(this).val('');
    }

    if ($('#service').val() == 'laboratory_services') {
        $('#LabServiceModal').modal('toggle');
        $(this).val('');
    }
});


/** Script for changing component */
$(document).on('click', '.tr_emergency', function () {
    var getemergency= baseUrl +'/mapping/emergency';
    $('#table_emergency').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_emergency_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_emergency_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_emergency_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.flddept + '" ><td> <input type="checkbox" value="' + value.flddept + '" name="emergency_check"> '  + value.flddept + ' </td></tr>';
            });

            $('#sub_emergency_table_body').html("");
            $('#sub_emergency_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing consulation options */
$(document).on('click', '.consolation_tr', function () {
    var getemergency= baseUrl +'/mapping/emergency';
    var sub_category = $(this).data('info');
    $('#table_consultant').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_consultant_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_consultant_table_body').append(html);
        },
        success: function (data) {
            var html_sub_consultant_list = "";
            $('#sub_consultant_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_consultant_list += '<tr data-fldcode="' + value.flddept + '" ><td> <input type="checkbox" value="' + value.flddept + '" name="consultant_check" data-sub_cat="'+ sub_category +'"> '  + value.flddept + ' </td></tr>';
            });

            $('#sub_consultant_table_body').html("");
            $('#sub_consultant_table_body').append(html_sub_consultant_list);

        },
    });
});


/** Script for changing Inpatient options */
$(document).on('click', '.tr_inpatient', function () {
    var getemergency= baseUrl +'/mapping/inpatients';
    var sub_category = $(this).data('sub_category');
    $('#table_inpatient').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_inpatient_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_inpatient_table_body').append(html);
        },
        success: function (data) {
            var html_sub_inpatient_list = "";
            $('#sub_inpatient_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_inpatient_list += '<tr data-fldcode="' + value.flddept + '" ><td> <input type="checkbox" value="' + value.flddept + '" name="inpatient_check" data-sub_cat="'+ sub_category +'"> '  + value.flddept + ' </td></tr>';
            });

            $('#sub_inpatient_table_body').html("");
            $('#sub_inpatient_table_body').append(html_sub_inpatient_list);

        },
    });
});

/** Script for changing ANC component */
$(document).on('click', '.tr_anc', function () {
    var getemergency= baseUrl +'/mapping/emergency';
    $('#table_anc').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_anc_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_anc_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_anc_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.flddept + '" ><td> <input type="checkbox" value="' + value.flddept + '" name="anc_check"> '  + value.flddept + ' </td></tr>';
            });

            $('#sub_anc_table_body').html("");
            $('#sub_anc_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing Diagnostic component */
$(document).on('click', '.tr_diagnostic', function () {
    var getemergency= baseUrl +'/mapping/diagnostic';
    var sub_category = $(this).data('sub_category');
    $('#table_diagnostic').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_diagnostic_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_diagnostic_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_diagnostic_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.flditemname + '" ><td> <input type="checkbox" value="' + value.flditemname + '" name="diagnostic_check" data-sub_cat="'+ sub_category +'"> '  + value.flditemname + ' </td></tr>';
            });

            $('#sub_diagnostic_table_body').html("");
            $('#sub_diagnostic_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing Delivery component */
$(document).on('click', '.tr_delivery', function () {
    var getemergency=baseUrl +'/mapping/delivery';
    var sub_category = $(this).data('sub_category');
    $('#table_delivery').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_delivery_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_delivery_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_delivery_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.flditem + '" ><td> <input type="checkbox" value="' + value.flditem + '" name="delivery_check" data-sub_cat="'+ sub_category +'"> '  + value.flditem + ' </td></tr>';
            });

            $('#sub_delivery_table_body').html("");
            $('#sub_delivery_table_body').append(html_sub_emergency_list);

        },
    });
});


/** Script for changing Laboratory component */
$(document).on('click', '.tr_laboratory', function () {
    var sub_category = $(this).data('sub_category');
    var getemergency = baseUrl +'/mapping/laboratory';
    $('#table_laboratory').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');

    $.ajax({
        url: getemergency,
        method: 'POST',
        data:{
            "_token": "{{ csrf_token() }}",
            sub_category : sub_category,
        },
        beforeSend: function () {
            $('#ssub_laboratory_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#ssub_laboratory_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#ssub_laboratory_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.fldtestid + '" ><td> <input type="checkbox" value="' + value.fldtestid + '" ' +
                    'name="laboratory_check" data-sub_cat="'+ sub_category +'"> '  + value.fldtestid + ' </td></tr>';
            });

            $('#ssub_laboratory_table_body').html("");
            $('#ssub_laboratory_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing Culture sensitiviy component */
$(document).on('click', '.tr_culture', function () {
    var getemergency= baseUrl +'/mapping/culture';
    $('#table_culture').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_culture_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_culture_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_culture_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.fldtestid + '" ><td> <input type="checkbox" value="' + value.fldtestid + '" name="culture_check"> '  + value.fldtestid + ' </td></tr>';
            });

            $('#sub_culture_table_body').html("");
            $('#sub_culture_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing Culture specimen component */
$(document).on('click', '.tr_culture_specimen', function () {
    var sub_category = $(this).data('sub_category');
    var getemergency= baseUrl +'/mapping/culture_specimen';
    $('#table_culture_specimens').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_culture_specimens_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_culture_specimens_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_culture_specimens_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.fldsampletype + '" ><td> <input type="checkbox" value="' + value.fldsampletype + '" name="culture_specimen_check" data-sub_cat="'+ sub_category +'"> '  + value.fldsampletype + ' </td></tr>';
            });

            $('#sub_culture_specimens_table_body').html("");
            $('#sub_culture_specimens_table_body').append(html_sub_emergency_list);

        },
    });
});

/** Script for changing Free Service component */
$(document).on('click', '.tr_free_service', function () {
    var sub_category = $(this).data('sub_category');
    var getemergency= baseUrl +'/mapping/free_service';
    $('#table_free_service').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');
    $.ajax({
        url: getemergency,
        method: 'get',
        beforeSend: function () {
            $('#sub_free_service_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_free_service_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_free_service_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.fldtype + '" ><td> <input type="checkbox" value="' + value.fldtype + '" name="free_service_check" data-sub_cat="'+ sub_category +'"> '  + value.fldtype + ' </td></tr>';
            });

            $('#sub_free_service_table_body').html("");
            $('#sub_free_service_table_body').append(html_sub_emergency_list);

        },
    });
});



/** Script for Saving emergency componenet */
$(document).on('click','#emergency_save', function () {
    var emergency = [];
    $.each($("input[name='emergency_check']:checked"), function(){
        emergency.push($(this).val());
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency= baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'emergency',
            sub_category:'',
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#EmergencyModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Something went wrong','error')
        },
    });
});

/** Script for Saving COnsulatant componenet */
$(document).on('click','#consultant_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='consultant_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='consultant_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency= baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'consultant',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#consultantModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving Inpatient componenet */
$(document).on('click','#inpatient_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='inpatient_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='inpatient_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency= baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'inpatient',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#InPatientModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving ANC componenet */
$(document).on('click','#anc_save', function () {
    var emergency = [];
    $.each($("input[name='anc_check']:checked"), function(){
        emergency.push($(this).val());
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency= baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'ANC',
            sub_category:'',
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#ancModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Functin for saving test mapping */

$(document).on('click','#laboratory_service_save', function () {
    var emergency = [];
    var sub_categ = $('#lab_options').val();
    var service_name = $('#table_laboratory_service').find('tr.rowSelected').data('fldcode');
    // alert(service_name);
    // return false;
    $.each($("input[name='laboratory_service_check']:checked"), function(){
        emergency.push($(this).val());
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
        return false;
    }
    if(service_name == null || service_name== undefined)
    {
        showAlert('Please select local option','error')
        return false;
    }

    var save_emergency= baseUrl +'/mapping/save_test';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Test Mapping',
            sub_category:sub_categ,
            service_name: service_name,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#LabServiceModal').modal('toggle');
            if((data.existed_data) && (data.existed_data.length > 0)){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving Diagnostic componenet */
$(document).on('click','#diagnostic_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='diagnostic_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='diagnostic_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list');
    }

    var save_emergency=  baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Diagnostic',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#diagnosticModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving Delivery componenet */
$(document).on('click','#deliver_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='delivery_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='delivery_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list');
    }

    var save_emergency=  baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Delivery',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#deliveryModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving culture_sensitivity componenet */
$(document).on('click','#culture_save', function () {
    var emergency = [];
    $.each($("input[name='culture_check']:checked"), function(){
        emergency.push($(this).val());
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list');
    }

    var save_emergency=  baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Culture Sensitivity',
            sub_category:'',
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#cultureModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving CUlture specien componenet */
$(document).on('click','#culture_sepcimen_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='culture_specimen_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='culture_specimen_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency=  baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Culture Specimen',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#cultureSpecimensModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error')
        },

    });
});

/** Script for Saving Free service componenet */
$(document).on('click','#free_service_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='free_service_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='free_service_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency=  baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Free Service',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#FreeServiceModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong','error');
        },
    });
});

/** Script for Saving Laboratory service componenet */
$(document).on('click','#laboratory_save', function () {
    var emergency = [];
    var sub_cat =[];

    $.each($("input[name='laboratory_check']:checked"), function(){
        emergency.push($(this).val());
    });

    $.each($("input[name='laboratory_check']:checked "), function(){
        sub_cat.push($(this).data('sub_cat'));
    });
    if(emergency.length==0)
    {
        showAlert('Please select from list','error');
    }

    var save_emergency= baseUrl +'/mapping/save_mappings';
    $.ajax({
        url: save_emergency,
        method: 'post',
        data: {
            // "_token": "{{ csrf_token() }}",
            category : 'Laboratory',
            sub_category:sub_cat,
            service_name: emergency,
            service_value: emergency,
        },
        success: function (data) {
            var html ="";
            $('input:checkbox').removeAttr('checked');
            if(data.is_inserted)
            {
                showAlert('Mapping information is saved ');
            }
            $('#laboratoryModal').modal('toggle');
            if(data.existed_data.length > 0){
                // if(emergency.length === data.)
                $.each(data.existed_data, function (index, value) {
                    var subcate = (value.sub_category !=null && value.sub_category !=undefined) ? value.sub_category : "";
                    html += '<tr><td align="center">'+ index +'</td>  <td align="center"> '+ value.category + ' </td> <td align="center"> '+ subcate +'</td><td align="center">'+ value.service_value +'</td></tr>';
                });
                $('#dataexistbody').html("");
                $('#dataexistbody').append(html);
                $('#dataExistModal').modal('toggle');
            }
        },
        error: function () {
            showAlert('Somehitng went wrong');
        },
    });
});

/** Function for plotting local options of lab services */
$('#lab_options').change( function () {
    if($('#lab_options').val()==='HAEMATOLOGY')
    {
        var option = 'HAEMATOLOGY';
        var array = [
            'Hb',
            'RBC Count',
            'TLC',
            'Platelets Count',
            'DLC',
            'ESR',
            'PCV/Hct',
            'MCV',
            'MCH',
            'MCHC',
            'RDW',
            'Blood Group & Rh Type',
            'Coombs test',
            'Retics',
            'PBS/PBF',
            'HbA1c',
            'MPO',
            'PAS',
            'Sickling Test',
            'Urine for Hemosiderin',
            'BT',
            'CT',
            'PT-INR',
            'APTT',
            'Bone Marrow Analysis',
            'Aldehyde test',
            'MP Total',
            'PF',
            'PV',
            'P-MIX',
            'MF Total',
            'MF Pos.',
            'LD Bodies',
            'Hb Electrophoresis',
            'LE cell',
            'ALC',
            'AEC',
            'FDP',
            'D-dimer',
            'Fac VIII',
            'Fac IX',
            'Others'
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='IMMUNOLOGY')
    {
        var option = 'IMMUNOLOGY';
        var array = [
            'Pregnancy Test (UPT)',
            'ASO',
            'CRP',
            'RA Factor',
            'TPHA Total',
            'TPHA +Ve',
            'ANA',
            'Anti-dsDNA',
            'RPR/VDRL Total',
            'RPR/VDRL +Ve',
            'CEA',
            'CA-125',
            'CA-19.9',
            'CA-15.3',
            'Toxo',
            'Rubella',
            'CMV',
            'HSV',
            'Measles',
            'Echinococcus',
            'Amoebiasis',
            'PSA',
            'Ferritin',
            'Cysticercosis',
            'Brucella',
            'Thyroglobulin',
            'Anti TPO',
            'Protein Electrophoresis',
            'Anti-CCP',
            'RK-39 Total',
            'RK-39 +Ve',
            'JE Total',
            'JE +Ve',
            'Dengue Total',
            'Dengue +Ve',
            'Rapid MP test Total',
            'Rapid MP test +Ve PV',
            'Rapid MP test +Ve PF',
            'Mantoux',
            'Chikungunya Total',
            'Chikungunya P+ve',
            'Scrub Typhu  Total',
            'H. Pylori',
            'Leptospira',
            'Others',

        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }


    if($('#lab_options').val()==='BIOCHEMISTRY')
    {
        var option = 'BIOCHEMISTRY';
        var array = [
            'Sugar',
            'Blood Urea',
            'Creatinine',
            'Sodium (Na)',
            'Potassium (K)',
            'Calcium',
            'Phosphorus',
            'Magnesium',
            'Uric acid',
            'Total Cholesterol',
            'Triglycerides',
            'HDL',
            'LDL',
            'Amylase',
            'Micro albumin',
            'Bilirubin',
            'SGPT',
            'Alk Phos',
            'Total Protein',
            'Albumin',
            'Gamma GT',
            '24hr urine protein ',
            '24hr urine U/A ',
            'Creatinine Clearance',
            'Iron',
            'TIBC',
            'CPK-MB',
            'CPK-NAC',
            'LDH',
            'Iso-Trop-I',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='IMMUNO-HISTROCHEMESTRY')
    {
        var option = 'IMMUNO-HISTROCHEMESTRY';
        var array = [
            'ER',
            'PR',
            'G-FAP',
            's-100',
            'Vimentin',
            'Cytokeratin',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='BACTERIOLOGY')
    {
        var option = 'BACTERIOLOGY';
        var array = [
            'Gram stain',
            'Sputum AFB',
            'Other AFB',
            'Leprosy Smear',
            'India Ink Test',
            'Culture Blood',
            'Culture Urine',
            'Culture Body Fluid',
            'Culture Swab',
            'Culture Stool',
            'Culture Water',
            'Culture Pus',
            'Culture Sputum',
            'Culture CSF',
            'Culture Others',
            'Fungus KOH Test',
            'Fungus Culture',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='VIROLOGY')
    {
        var option = 'VIROLOGY';
        var array = [
            'HIV Total',
            'HIV +Ve',
            'HAV Total',
            'HAV +Ve',
            'HBsAg Total',
            'HBsAg +Ve',
            'HCV Total',
            'HCV +Ve',
            'HEV Total',
            'HEV +Ve',
            'Anti-HBs',
            'HBeAg',
            'Anti-HBe',
            'HBcAg',
            'Anti-HBcAg',
            'Western blot',
            'CD4 count',
            'Viral load',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='CYTOLOGY')
    {
        var option = 'CYTOLOGY';
        var array = [
            'Biopsy H & E',
            'Biopsy Other',
            'Cytology Pap',
            'Cytology Giemsa',
            'Cytology Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='PARASITOLOGY')
    {
        var option = 'PARASITOLOGY';
        var array = [
            'Stool R/E',
            'Occult blood',
            'Reducing sugar',
            'Urine R/E',
            'Bile salts',
            'Bile pigments',
            'Urobilinogen',
            'Porphobilinogen',
            'Acetone',
            'Chyle',
            'Specific Gravity',
            'Bence Jones protein',
            'Semen analysis',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='HORMONES-ENDOCRINES')
    {
        var option = 'HORMONES-ENDOCRINES';
        var array = [
            'T3',
            'T4',
            'TSH',
            'Cortisol',
            'AFP',
            'β-HCG',
            'LH',
            'FSH',
            'Prolactin',
            'Oestrogen',
            'Progesterone',
            'Testosterone',
            'Vit.D',
            'Vit.B12',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }

    if($('#lab_options').val()==='DRUG-ANALYSIS')
    {
        var option = 'DRUG-ANALYSIS';
        var array = [
            'Carbamazepine',
            'Cyclosporine',
            'Valporic acid',
            'Phenytoin',
            'Digoxine',
            'Tacrolimus',
            'Others',
        ];
        $('#labservice_table').append("");
        plot(array);
        getOptions(option)
    }
});

function plot( option) {
    var html = '';
    $.each(option , function(index, value) {
        html  +='<tr data-fldcode="' + value + '" class="tr_test_map" ><td> '  + value +' </td></tr>';
        $('#labservice_table').html("");
        $('#labservice_table').append(html);
    });
}

function getOptions( option) {

    var getemergency =  baseUrl +'/mapping/laboratory';
    $.ajax({
        url: getemergency,
        method: 'POST',
        data:{
            // "_token": "{{ csrf_token() }}",
            sub_category : option,
        },
        beforeSend: function () {
            $('#sub_laboratory_service_table_body').html("");
            var html = '<tr ><td colspan="2" class="text-center">' + 'Please Wait.....' + '</td></tr>';
            $('#sub_laboratory_service_table_body').append(html);
        },
        success: function (data) {
            var html_sub_emergency_list = "";
            $('#sub_laboratory_service_table_body').append("");
            $.each(data, function (index, value) {
                html_sub_emergency_list += '<tr data-fldcode="' + value.fldtestid + '" ><td> <input type="checkbox" value="' + value.fldtestid + '" ' +
                    'name="laboratory_service_check" data-sub_cat="'+ option +'"> '  + value.fldtestid + ' </td></tr>';
            });

            $('#sub_laboratory_service_table_body').html("");
            $('#sub_laboratory_service_table_body').append(html_sub_emergency_list);

        },
    });
}

$(document).on('click','.tr_test_map',function () {
    $('#table_laboratory_service').find('tr').removeClass('rowSelected');
    $(this).addClass('rowSelected');

});

$(".modal").on("hidden.bs.modal", function(){
    $(this).find('input:checkbox').prop('checked', false);
});
