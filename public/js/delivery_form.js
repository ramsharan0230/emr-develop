
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$(".unauthorised").click(function () {
    permit_user = $(this).attr('permit_user');
    showAlert('Authorization with  '+permit_user);
});

$(document).on('click', '#js-obstetric-diagnosis-update-btn', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + '/display-obstetric-form-save-waiting',
        type: "POST",
        data: $('.obstetric-form').serialize(),
        success: function (response) {
            $('#diagnosis-obstetric-modal').modal('hide');
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
});

function toggle_newborn_tab() {
    var hasallgender = $('#js-newdelivery-tbody tr').length > 0;
    if (hasallgender) {
        $.each($('#js-newdelivery-tbody tr td:nth-child(6)'), function(i, e) {
            var text = $(e).text().trim() || '';
            if(text == '')
                hasallgender = false;
        });
    }

    if (hasallgender)
        $('#delivery_tab li a[href="#newborn"]').removeClass('disabled');
    else
        $('#delivery_tab li a[href="#newborn"]').addClass('disabled');
}
toggle_newborn_tab();

// Tab function start

/*
start function of newdelivery
*/

    CKEDITOR.replace('js-newdelivery-comment-input',
    {
    height: '100px',
    } );

    function toggle_add_btn() {
        var type = $("input[type='radio'][name='newdelivery_type']:checked").val();

        if (type === 'single' && $('#js-newdelivery-tbody tr').length > 0)
            $('#js-newdelivery-deliver-add-btn').attr('disabled', true);
        else
            $('#js-newdelivery-deliver-add-btn').attr('disabled', false);
    }

    toggle_add_btn();
    $('ul#delivery_tab li.nav-item a[href="#newdelivery"]').click(function() {
        toggle_add_btn();
        refresh_newdelivery_options();
    });
    $("input[type='radio'][name='newdelivery_type']").change(function() {
        toggle_add_btn();
    });
    var diagnosisfreetext = {
        displayModal: function () {
            // alert('obstetric');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: baseUrl + '/display-obstetric-freetextform',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.form-data-diagnosis-freetext').html(response);
                    $('#diagnosis-freetext-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }
    function get_newdelivery_form_data() {
        return {
            'flddelphysician' : $('#js-newdelivery-consultant-input').val(),
            'flddeldate' : $('#js-newdelivery-deliverydate-input').val(),
            'flddeltime' : $('#js-newdelivery-deliverytime-input').val(),
            'flddeltype' : $('#js-newdelivery-deliverytype-input').val(),
            'flddelnurse' : $('#js-newdelivery-nursing-input').val(),
            'fldcomplication' : $('#js-newdelivery-complication-input').val(),
            'flddelresult' : $('#js-newdelivery-deliverybaby-input').val(),
            'fldbloodloss' : $('#js-newdelivery-bloodloss-input').val(),
            'flddelwt' : $('#js-newdelivery-babyweight-input').val(),
            'fldplacenta' : $('#js-newdelivery-placenta-input').val(),
            'fldcomment' : CKEDITOR.instances['js-newdelivery-comment-input'].getData(),
        };
    }

    function refresh_newdelivery_options() {
    	$.ajax({
            url: baseUrl + '/delivery/newdelivery/getSelectOptions',
            type: "GET",
            success: function (response) {
            	var delivered_types = '<option value="">-- Select --</option>';
            	$.each(response.delivered_types, function(i, e) {
            		delivered_types += '<option value="' + e.flditem + '">' + e.flditem + '</option>'
            	});

            	var complications = '<option value="">-- Select --</option>';
            	$.each(response.complications, function(i, e) {
            		complications += '<option value="' + e.flditem + '">' + e.flditem + '</option>'
            	});

            	$('#js-newdelivery-deliverytype-input').html(delivered_types);
            	$('#js-newdelivery-complication-input').html(complications);
            }
        });
    }

    $('#js-newdelivery-deliver-add-btn').click(function() {
        var data = get_newdelivery_form_data();
        $.ajax({
            url: baseUrl + '/delivery/newdelivery/store',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var val = response.data;

                    var trData = '<tr data-fldid="' + val.fldid + '">';
                    trData += '<td>' + val.flddeltime + '</td>';
                    trData += '<td>' + val.flddeltype + '</td>';
                    trData += '<td>' + val.flddelresult + '</td>';
                    trData += '<td>' + val.flddelwt + '</td>';
                    trData += '<td>' + val.flddelphysician + '</td>';
                    trData += '<td>&nbsp;</td><td>&nbsp;</td><td><button class="btn btn-sm"><i class="fa fa-transgender"></i></button></td></tr>';

                    $('#js-newdelivery-tbody').append(trData);
                    toggle_add_btn();
                }
                showAlert(response.message);
            }
        });
    });

    $('#js-newdelivery-deliver-update-btn').click(function() {
        var fldid = $('#js-newdelivery-tbody tr[is_selected="yes"]').data('fldid') || '';
        console.log($('#js-newdelivery-tbody tr[is_selected="yes"]').data('fldid'));
        if (fldid != '') {
            var data = get_newdelivery_form_data();
            data['fldid'] = $('#js-newdelivery-tbody tr[is_selected="yes"]').data('fldid');

            $.ajax({
                url: baseUrl + '/delivery/newdelivery/update',
                type: "POST",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        var tr_elem = $('#js-newdelivery-tbody tr[is_selected="yes"]');

                        $(tr_elem).find('td:nth-child(1)').text(val.flddeltime);
                        $(tr_elem).find('td:nth-child(2)').text(val.flddeltype);
                        $(tr_elem).find('td:nth-child(3)').text(val.flddelresult);
                        $(tr_elem).find('td:nth-child(4)').text(val.flddelwt);
                        $(tr_elem).find('td:nth-child(5)').text(val.flddelphysician);
                        toggle_add_btn();
                    }
                    showAlert(response.message);
                }
            });
        } else
            alert('Please select data to update.');
    });

    $(document).on('click', '#js-newdelivery-tbody tr', function() {
	    selected_td('#js-newdelivery-tbody tr', this);

	    $.ajax({
	    	url: baseUrl + '/delivery/newdelivery/getDelivery',
	    	type: 'GET',
	    	data: {fldid: $(this).data('fldid')},
            dataType: "json",
	    	success: function(response) {
	    		$('#js-newdelivery-deliverytype-input option').attr('selected', false);
				$('#js-newdelivery-complication-input option').attr('selected', false);
				$('#js-newdelivery-deliverybaby-input option').attr('selected', false);
                $('#js-newdelivery-nursing-input option').attr('selected', false);
                $('#js-newdelivery-consultant-input option').attr('selected', false);

                $.each(response.flddelnurse, function(i, flddelnurse) {
                    $('#js-newdelivery-nursing-input option[value="' + flddelnurse + '"]').attr('selected', true);
                });
				$('#js-newdelivery-consultant-input option[value="' + response.flddelphysician + '"]').attr('selected', true);
                $('.select2').trigger('change');

                $('#js-newdelivery-deliverydate-input').val(response.flddate);
				$('#js-newdelivery-deliverytime-input').val(response.fldtime);
				$('#js-newdelivery-placenta-input').val(response.fldplacenta);

				$('#js-newdelivery-deliverytype-input option[value="' + response.flddeltype + '"]').attr('selected', true);
				$('#js-newdelivery-complication-input option[value="' + response.fldcomplication + '"]').attr('selected', true);
				$('#js-newdelivery-deliverybaby-input option[value="' + response.flddelresult + '"]').attr('selected', true);
				$('#js-newdelivery-bloodloss-input').val(response.fldbloodloss);
				$('#js-newdelivery-babyweight-input').val(response.flddelwt);
				CKEDITOR.instances['js-newdelivery-comment-input'].setData(response.fldcomment);
	    	}
	    });
	});

	$('.js-newdelivery-add-item').click(function() {
        var tr_data = '';
        $.each($(this).closest('.half_box').find('.form-input-newdeli option'), function(i, e) {
			var value = $(e).val();
			if (value !== '')
				tr_data += '<tr data-flditem="' + value + '"><td>' + value + '</td></tr>';
		});

		$('#js-newdelivery-type-input-modal').val($(this).data('variable'))
		$('#js-newdelivery-table-modal').html(tr_data);
		$('#js-newdelivery-add-item-modal').modal('show');
	});

	$('#js-newdelivery-flditem-input-modal').keyup(function() {
		var searchText = $(this).val().toUpperCase();
		$.each($('#js-newdelivery-table-modal tr td:first-child'), function(i, e) {
			var tdText = $(e).text().trim().toUpperCase();

			if (tdText.search(searchText) >= 0)
				$(e).show();
			else
				$(e).hide();
		});
	});

    $(document).on('click', '#js-newdelivery-table-modal tr', function() {
	    selected_td('#js-newdelivery-table-modal tr', this);
	});

	$('#js-newdelivery-add-btn-modal').click(function() {
		var data = {
			flditem: $('#js-newdelivery-flditem-input-modal').val(),
			type: $('#js-newdelivery-type-input-modal').val(),
		};
		$.ajax({
            url: baseUrl + '/delivery/newdelivery/addVariable',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var val = response.data;
                    var trData = "";
                    var modalType = $('#js-newdelivery-type-input-modal').val();
                    var selectId = "";
                    if(modalType == "delivery_type"){
                        selectId = "#js-newdelivery-deliverytype-input";
                    }else{
                        selectId = "#js-newdelivery-complication-input";
                    }
                    $(selectId).append(`<option value="${val.flditem}">
                                       ${val.flditem}
                                  </option>`);
                    $.each($(selectId+' option'), function(i, e) {
                        var value = $(e).val();
                        if (value !== '')
                            trData += '<tr data-flditem="' + value + '"><td>' + value + '</td></tr>';
                    });
                    // trData += '<tr data-flditem="' + val.flditem + '"><td>' + val.flditem + '</td></tr>';
                    $('#js-newdelivery-table-modal').append(trData);
                    $('#js-newdelivery-flditem-input-modal').val('');
                }
                showAlert(response.message);
            }
		});
	});

	$('#js-newdelivery-delete-btn-modal').click(function() {
		var data = {
			flditem: $('#js-newdelivery-table-modal tr[is_selected="yes"]').data('flditem'),
			type: $('#js-newdelivery-type-input-modal').val(),
		};
		$.ajax({
            url: baseUrl + '/delivery/newdelivery/deleteVariable',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status)
                    var selectId = "";
                    var modalType = $('#js-newdelivery-type-input-modal').val();
                    if(modalType == "delivery_type"){
                        selectId = "#js-newdelivery-deliverytype-input";
                    }else{
                        selectId = "#js-newdelivery-complication-input";
                    }
                    $(selectId+" option[value='"+$('#js-newdelivery-table-modal tr[is_selected="yes"]').data('flditem')+"']").remove();
                    $('#js-newdelivery-table-modal tr[is_selected="yes"]').remove();
                    showAlert(response.message);
            }
		});
	});

	$('#js-newdelivery-add-item-modal').on('hidden.bs.modal', function () {
	    $('#js-newdelivery-flditem-input-modal').val('');
	    $('#js-newdelivery-type-input-modal').val('');
	    $('#js-newdelivery-table-modal').html('');

	    refresh_newdelivery_options();
	});

	$(document).on('click', '#js-newdelivery-tbody tr td:nth-child(7), #js-newdelivery-tbody tr td:nth-child(8)', function() {
	    $('#js-newdelivery-gender-select-gender-modal option').attr('selected', false);

	    $('#js-newdelivery-fldid-input-gender-modal').val($(this).closest('tr').data('fldid'));
	    $('#js-newdelivery-fldpatientval-input-gender-modal').val($(this).closest('tr').find('td:nth-child(6)').text().trim());

	    var gender = $('#js-newdelivery-tbody tr td:nth-child(7)').text().trim();
	    $('#js-newdelivery-gender-select-gender-modal option[value="' + gender + '"]').attr('selected', true);

		$('#js-newdelivery-gender-modal').modal('show');
	});

	$('#js-newdelivery-gender-modal').on('hidden.bs.modal', function () {
	    $('#js-newdelivery-gender-select-gender-modal option').attr('selected', false);

	    $('#js-newdelivery-fldid-input-gender-modal').val('');
	    $('#js-newdelivery-fldpatientval-input-gender-modal').val('');
        toggle_newborn_tab();
	});

	$('#js-newdelivery-add-btn-gender-modal').click(function() {
		var fldid = $('#js-newdelivery-fldid-input-gender-modal').val();
        $.ajax({
            url: baseUrl + '/delivery/newdelivery/saveUpdateChildGender',
            type: "POST",
            data: {
				fldid: fldid,
				fldpatientval: $('#js-newdelivery-fldpatientval-input-gender-modal').val(),
				gender: $('#js-newdelivery-gender-select-gender-modal').val(),
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                	$('#js-newdelivery-tbody tr[is_selected="yes"] td:nth-child(7)').text(response.data.gender);
                	if (response.action == 'save')
                		$('#js-newdelivery-tbody tr[is_selected="yes"] td:nth-child(6)').text(response.data.fldpatientval);
                    toggle_newborn_tab();
                }
                showAlert(response.message);
				$('#js-newdelivery-gender-modal').modal('hide');
            }
        });

	});

    $('#js-newdelivery-textfile-input').change(function() {
        var data = new FormData();
        if ($(this)[0].files !== undefined && $(this)[0].files.length > 0) {
            var image = $(this)[0].files[0];
            data.append('fldtext', image);
        }

        $.ajax({
            url: baseUrl + '/inpatient/prog/readtextfile',
            type: "POST",
            enctype: 'multipart/form-data',
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                var optionData = '<select id="js-newdelivery-nursing-input" style="width: 100%;" class="select-01 form-input"><option>-- Select --<option>';
                $.each(response, function(i, val) {
                    optionData += '<option>' + val + '<option>';
                });
                optionData += "</select>"

                $('#js-newdelivery-input-parent').html(optionData);
            }
        });
    });

/*
end funtion of newdelivery
*/


/*
start function of pre, on , podt delivery
*/
    function get_dept_type(currElem) {
        return (currElem) ? $(currElem).data('flddept') : $('#delivery_tab li a[class="nav-link active"]').data('flddept');
    }

    function get_examinations(flddept) {
        $.ajax({
            url: baseUrl + '/delivery/deliveryexamination/getExaminationLists',
            type: "GET",
            data: {flddept: flddept},
            dataType: "json",
            success: function (response) {
                var trData = '';

                $.each(response, function(i,val) {
                    trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                    trData += '<td>' + val.fldexamid + '</td></tr>';
                });
                $('#js-deliveryexamination-examination-tbody').html(trData);
            }
        });
    }

    function get_patient_examinations(fldinput) {
        $.ajax({
            url: baseUrl + '/delivery/deliveryexamination/getPatientExaminations',
            type: "GET",
            data: {fldinput: fldinput},
            dataType: "json",
            success: function (response) {
                var trData = '';

                $.each(response, function(i,val) {
                    var abnormalVal = get_abnoraml_btn(val.fldabnormal);

                    trData += '<tr data-fldid="' + val.fldid + '">';
                    trData += '<td>' + (i+1) + '</td>';
                    trData += '<td>' + val.fldhead + '</td>';
                    trData += '<td>' + abnormalVal + '</td>';
                    trData += '<td>' + val.fldrepquali + '</td>';
                    trData += '<td>' + val.fldtime + '</td>';
                    trData += '</tr>';
                });
                $('#js-deliveryexamination-patient-examination-tbody').html(trData);
            }
        });
    }

    $('#delivery_tab li a[data-flddept]').click(function() {
        $('#js-deliveryexamination-examination-tbody').html('');
        $('#js-deliveryexamination-patient-examination-tbody').html('');

        var flddept = get_dept_type($(this));
        get_examinations(flddept);
        get_patient_examinations(flddept);
    });

    $(document).on('click', '#js-deliveryexamination-examination-tbody tr', function() {
        selected_td('#js-deliveryexamination-examination-tbody tr', this);

        var type = $(this).data('fldtanswertype');
        var examid = $(this).data('fldexamid');

        $.ajax({
            url: baseUrl + '/delivery/deliveryexamination/getModalContent',
            type: "GET",
            data: {type: type, examid: examid},
            dataType: "json",
            success: function (response) {
                $('#js-deliveryexamination-content-modal').html(response.view_data);
                $('#js-deliveryexamination-content-modal').modal('show');
            }
        });
    });

    $(document).on('click', '#js-deliveryexamination-patient-examination-tbody tr', function() {
        selected_td('#js-deliveryexamination-patient-examination-tbody tr', this);
    });

    $(document).on('click', '#js-deliveryexamination-examination-save-modal', function() {
        var selectedTr = $('#js-deliveryexamination-examination-tbody tr[is_selected="yes"]');
        var examinationid = $(selectedTr).data('fldexamid');
        var examtype = $(selectedTr).data('fldtype');
        var examOption = $(selectedTr).data('fldtanswertype');
        var qualitative = '';
        var quantative = '0';

        if (examOption === 'Left and Right') {
            qualitative = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
            if($('#js-left-tbody').val() == "" || $('#js-right-tbody').val() == ""){
                showAlert("Please fill up the data first");
                return false;
            }
        } else if (examOption == 'No Selection') {
            qualitative = $('#js-input-no-selection').val();
            quantative = qualitative;
            if(qualitative == ""){
                showAlert("Please fill up the data first");
                return false;
            }
        } else {
            qualitative = $('#js-input-element').val();
            if(qualitative == ""){
                showAlert("Please fill up the data first");
                return false;
            }
        }

        var data = {
            fldinput : get_dept_type(),
            examtype : examtype,
            examinationid : examinationid,
            qualitative : qualitative,
            quantative : quantative,
            abnormalVal : ($('#js-abnormal').length !== undefined && $('#js-abnormal').prop('checked')) ? '1' : '0'
        };
        $.ajax({
            url: baseUrl + '/delivery/deliveryexamination/savePatientExaminations',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var val = response.data;
                    var observationVal = (examOption == 'No Selection' || examOption == 'Clinical Scale') ? val.quantative : val.qualitative;
                    var abnormalVal = (val.abnormal == '0') ? '<div class="btn btn-success btn-sm"></div>' : '<div class="btn btn-danger btn-sm"></div>';

                    var trData = '<tr data-fldid="' + val.fldid + '">';
                    trData += '<td>' + ($('#js-deliveryexamination-patient-examination-tbody tr').length+1) + '</td>';
                    trData += '<td>' + val.examination + '</td>';
                    trData += '<td>' + abnormalVal + '</td>';
                    trData += '<td>' + observationVal + '</td>';
                    trData += '<td>' + val.time + '</td>';
                    trData += '</tr>';

                    $('#js-deliveryexamination-patient-examination-tbody').append(trData);
                }
                showAlert(response.message);
                $('#js-deliveryexamination-content-modal').modal('hide');
            }
        });
    });

    $(document).on('click', '#js-deliveryexamination-patient-examination-tbody tr td:nth-child(3)', function() {
        var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
        $('#js-deliveryexamiination-status-select option').attr('selected', false);
        $('#js-deliveryexamiination-status-select option[value="' + status + '"]').attr('selected', true);

        $('#js-deliveryexamiination-status-modal').modal('show');
    });

    $('#js-deliveryexamiination-status-save-modal').click(function() {
        var fldid = $('#js-deliveryexamination-patient-examination-tbody tr[is_selected="yes"]').data('fldid') || '';
        var fldabnormal = $('#js-deliveryexamiination-status-select').val() || '';

        if (fldabnormal !== '' || fldid !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/onexamination/changeOnExamStatus',
                type: "POST",
                data: {fldid: fldid, fldabnormal: fldabnormal},
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        var btnElem = '#js-deliveryexamination-patient-examination-tbody tr[data-fldid="' + fldid + '"] td:nth-child(3) div.btn';
                        var addClass = (fldabnormal === '0') ? 'btn-success' : 'btn-danger';
                        var removeClass = (fldabnormal === '0') ? 'btn-danger' : 'btn-success';

                        $(btnElem).removeClass(removeClass);
                        $(btnElem).addClass(addClass);

                        $('#js-deliveryexamiination-status-modal').modal('hide');
                    }
                    showAlert(response.message);
                }
            });
        } else
            alert('Invalid data selected for update.');
    });

    $(document).on('click', '#js-deliveryexamination-patient-examination-tbody tr td:nth-child(4)', function() {
        updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'), 'tbldeptexam');
    });

    $('.js-delivery-report-generate-btn').click(function() {
        var flddept = $('#delivery_tab li.nav-item a.nav-link.active').data('flddept') || '';
        var encounterId = $('#encounter_id').val() || '';

        if (encounterId != '' || flddept != '') {
            var url = baseUrl + '/delivery/deliveryexamination/geerateReport?encounterId=' + encounterId + '&flddept=' + flddept;
            window.open(url, 'name');
        } else
        showAlert('Something went wrong.');


    });

/*
end funtion of pre, on , podt delivery
*/


/*
start function of newborn
*/

    function get_newborn_examination() {
        $.ajax({
            url: baseUrl + '/delivery/newborn/getExaminations',
            type: "GET",
            success: function (response) {
                var trData = '';

                $.each(response, function(i,val) {
                    trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                    trData += '<td>' + val.fldexamid + '</td></tr>';
                });
                $('#js-newborn-examination-tbody').html(trData);
            }
        });
    }

    function get_newborn_children() {
        $.ajax({
            url: baseUrl + '/delivery/newborn/getChildren',
            type: "GET",
            success: function (response) {
                var optionData = '<option value="">-- Select --</option>';
                $.each(response, function(i,val) {
                    optionData += '<option value="' + val.fldencounterval + '">' + val.fldencounterval + '</option>';
                });
                $('#js-newborn-children-select').html(optionData);
            }
        });
    }

    function reset_newborn_form() {
        $('#js-newborn-refer-input').val('');
        $('#js-newborn-patno-input').val('');
        $('#js-newborn-dob-input').val('');
        $('#js-newborn-sex-select option[value=""]').attr('selected', true);
        $('#js-newborn-age-input').val('');
    }

    $('#delivery_tab li a[href="#newborn"]').click(function() {
        reset_newborn_form();
        get_newborn_examination();
        get_newborn_children();
        $('#js-newborn-baby-examination-tbody').empty();
    });

    $(document).on('change', '#js-newborn-children-select', function() {
        var value = $(this).val() || '';
        if (value !== '') {
            $.ajax({
                url: baseUrl + '/delivery/newborn/getChildData',
                type: "GET",
                data: {fldencounterval: $(this).val()},
                dataType: "json",
                success: function (response) {
                    $('#js-newborn-refer-input').val('');
                    $('#js-newborn-patno-input').val(response.fldpatientval);
                    $('#js-newborn-dob-input').val(AD2BS(response.patient_info.fldptbirday.split(' ')[0]));
                    $('#js-newborn-sex-select option[value="' + response.patient_info.fldptsex + '"]').attr('selected', true);
                    $('#js-newborn-age-input').val(response.patient_info.birthhours);

                    // plot exam
                    var trData = '';
                    $.each(response.examinations, function(i, val) {
                        var abnormalVal = get_abnoraml_btn(val.fldabnormal);

                        trData += '<tr data-fldid="' + val.fldid + '">';
                        trData += '<td>' + (i+1) + '</td>';
                        trData += '<td>' + val.fldhead + '</td>';
                        trData += '<td>' + abnormalVal + '</td>';
                        trData += '<td>' + val.fldrepquali + '</td>';
                        trData += '<td>' + val.fldtime + '</td>';
                        trData += '</tr>';
                    });
                    $('#js-newborn-baby-examination-tbody').html(trData);
                }
            });
        } else
            reset_newborn_form();
    });

    $(document).on('click', '#js-newborn-baby-examination-tbody tr', function() {
        selected_td('#js-newborn-baby-examination-tbody tr', this);
    });

    $(document).on('click', '#js-newborn-baby-examination-tbody tr td:nth-child(3)', function() {
        var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
        $('#js-newborn-status-select option').attr('selected', false);
        $('#js-newborn-status-select option[value="' + status + '"]').attr('selected', true);

        $('#js-newborn-status-modal').modal('show');
    });

    $('#js-newborn-status-save-modal').click(function() {
        var fldid = $('#js-newborn-baby-examination-tbody tr[is_selected="yes"]').data('fldid') || '';
        var fldabnormal = $('#js-newborn-status-select').val() || '';

        if (fldabnormal !== '' || fldid !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/onexamination/changeOnExamStatus',
                type: "POST",
                data: {fldid: fldid, fldabnormal: fldabnormal},
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        var btnElem = '#js-newborn-baby-examination-tbody tr[data-fldid="' + fldid + '"] td:nth-child(3) div.btn';
                        var addClass = (fldabnormal === '0') ? 'btn-success' : 'btn-danger';
                        var removeClass = (fldabnormal === '0') ? 'btn-danger' : 'btn-success';

                        $(btnElem).removeClass(removeClass);
                        $(btnElem).addClass(addClass);

                        $('#js-newborn-status-modal').modal('hide');
                    }
                    showAlert(response.message);
                }
            });
        } else
            alert('Invalid data selected for update.');
    });


    $(document).on('click', '#js-newborn-examination-tbody tr', function() {
        selected_td('#js-newborn-examination-tbody tr', this);

        var type = $(this).data('fldtanswertype');
        var examid = $(this).data('fldexamid');

        $.ajax({
            url: baseUrl + '/delivery/newborn/getModalContent',
            type: "GET",
            data: {type: type, examid: examid},
            dataType: "json",
            success: function (response) {
                $('#js-deliveryexamination-content-modal').html(response.view_data);
                $('#js-deliveryexamination-content-modal').modal('show');
            }
        });
    });

    $(document).on('click', '#js-newborn-examination-save-modal', function() {
        var selectedTr = $('#js-newborn-examination-tbody tr[is_selected="yes"]');
        var examinationid = $(selectedTr).data('fldexamid');
        var examtype = $(selectedTr).data('fldtype');
        var examOption = $(selectedTr).data('fldtanswertype');
        var qualitative = '';
        var quantative = '0';

        if (examOption === 'Left and Right') {
            qualitative = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
        } else if (examOption == 'No Selection') {
            qualitative = $('#js-input-no-selection').val();
            quantative = qualitative;
        } else if (examOption == 'Single Selection') {
            qualitative = $('input[name="js-input-element"]:checked').val();
        } else {
            qualitative = $('#js-input-element').val();
        }

        var data = {
            encounter_id: $('#js-newborn-children-select').val(),
            fldinput : get_dept_type(),
            examtype : examtype,
            examinationid : examinationid,
            qualitative : qualitative,
            quantative : quantative,
            abnormalVal : ($('#js-abnormal').length !== undefined && $('#js-abnormal').prop('checked')) ? '1' : '0'
        };
        $.ajax({
            url: baseUrl + '/delivery/newborn/addExamination',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var val = response.data;
                    var observationVal = (examOption == 'No Selection' || examOption == 'Clinical Scale') ? val.quantative : val.qualitative;
                    var abnormalVal = (val.abnormal == '0') ? '<div class="btn btn-success btn-sm"></div>' : '<div class="btn btn-danger btn-sm"></div>';

                    var trData = '<tr data-fldid="' + val.fldid + '">';
                    trData += '<td>' + ($('#js-newborn-baby-examination-tbody tr').length+1) + '</td>';
                    trData += '<td>' + val.examination + '</td>';
                    trData += '<td>' + abnormalVal + '</td>';
                    trData += '<td>' + observationVal + '</td>';
                    trData += '<td>' + val.time + '</td>';
                    trData += '</tr>';

                    $('#js-newborn-baby-examination-tbody').append(trData);
                }
                showAlert(response.message);
                $('#js-deliveryexamination-content-modal').modal('hide');
            }
        });
    });

    $('#js-newborn-examreport-export-btn').click(function() {
        var value = $('#js-newborn-children-select').val() || '';
        if (value !== '')
            window.open(baseUrl + '/delivery/newborn/examReport?fldencounterval=' + value, '_blank');
    });

    $(document).on('click', '#js-newborn-baby-examination-tbody tr td:nth-child(4)', function() {
        updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'), 'tbldeptexam');
    });

    $('#js-newborn-dob-save-btn').click(function() {
        var encid = $('#js-newborn-children-select').val() || '';
        var date = $('#js-newborn-dob-input').val() || '';

        if (encid != '' && date != '') {
            $.ajax({
                url: baseUrl + '/delivery/newborn/changedob',
                type: "POST",
                data: {date: date, encid: encid},
                dataType: "json",
                success: function (response) {
                    showAlert(response.message);
                    if (response.status)
                        $('#js-newborn-age-input').val(response.age);
                }
            });
        }
    });

    $('#js-newborn-birth-btn').click(function() {
        var babyEncounter = $('#js-newborn-children-select').val() || '';
        if (babyEncounter != '')
            window.open(baseUrl + '/delivery/newborn/birthcertificate?fldencounterval=' + babyEncounter, '_blank');
    });

/*
end funtion of newborn
*/


/*
start function of pharmacydelivery
*/

    // toggle show all examinations
    $('#js-delivery-pharmacy-showall').click(function() {
        var toggleData = $('#js-delivery-pharmacy-showall').attr('datatype');
        get_medicine(toggleData);

        var newVal = (toggleData === 'all') ? 'today' : 'all';
        $('#js-delivery-pharmacy-showall').attr('datatype', newVal);
    });

    function get_medicine(date) {
        $.ajax({
            url: baseUrl + '/delivery/pharmacy/getAllMedicine',
            type: "GET",
            data: {date: date, fldencounterval: globalEncounter},
            dataType: "json",
            success: function (data) {
                var trData = '';
                $.each(data, function(i, val) {
                    trData += '<tr>';
                    trData += '<td>' + (i+1) + '</td>';
                    trData += '<td>' + val.fldstarttime + '</td>';
                    trData += '<td>' + val.fldroute + '</td>';
                    trData += '<td>' + val.flditem + '</td>';
                    trData += '<td>' + val.flddose + '</td>';
                    trData += '<td>' + val.fldfreq + '</td>';
                    trData += '<td>' + val.flddays + '</td>';
                    trData += '<td>' + val.qty + '</td>';
                    trData += '<td>' + val.fldcurval + '</td></tr>';
                });
                $('#js-delivery-pharmacy-tbody').empty().html(trData);
            }
        });
    }


    $('#delivery_tab li a[href="#pharmacydelivery"]').click(function() {
        get_medicine('today')
        $('#js-newborn-baby-examination-tbody').empty();
    });


/*
end funtion of pharmacydelivery
*/

// Tab function end
