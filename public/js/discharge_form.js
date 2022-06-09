
    $(document).ready(function(){
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.add-more'); //Input field wrapper
        var fieldHTML = '<div><div class="form-group"><label for="" class="label-bold">Date Of Operation:</label><input type="text" name="operation_date[]" id="proc_date" class="form-control" autocomplete="off"><input type="hidden" name="eng_operation_date[]" id="englis_proc_date"></div><div class="form-group"><label for="" class="label-bold">Operative Procedure:</label><input type="text" name="operative_procedures[]" id="operative_procedures" class="form-control"></div><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        
        //Once add button is clicked
        $(addButton).click(function(){
            //Check maximum number of input fields
            if(x < maxField){ 
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });
        
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
    $('#discharge_nepali_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#discharge_english_date').val(BS2AD($('#discharge_nepali_date').val()));
            }
        });
    $('#searchdrugs').bind('keyup', function() {

        var searchString = $(this).val();

        $("ul li").each(function(index, value) {

            currentName = $(value).text()
            if( currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
               $(value).show();
            } else {
                $(value).hide();
            }

        });

    });
    $('#proc_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#englis_proc_date').val(BS2AD($('#proc_date').val()));
        }
    });
    function nextVisit(){
        var encounter = $('#encounterid').val();
        if(!encounter || encounter ==''){
            alert('Please select encounter id.');
            return false;
        }

        $.ajax({
            url: "{{ route('discharge.display.followup')}}",
            type: "GET",
            data: {
                encounterId: $('#encounterid').val()
            },
            success: function(response) {
                // console.log(response);

                $('.pharmacy-form-data').html(response);
                $('#pharmacy-modal').find('.modal-title').text('Next Visit');
                $('#pharmacy-modal') .modal('show');
                // var modal = $(popupTemplate);
                // modal.find('.modal-title').text('HELLO');
                // modal.modal();
                // $('.detailBtn').trigger('click');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


    }
    $('#deletealdrug').on('click', function () {

        $('#select-multiple-aldrug').each(function () {
            // alval = [];
            var finalval = $(this).val().toString();
            // alert(finalval);
            var url = $('.delete_pat_findings').val();
            // alert(url);
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {ids: finalval},
                success: function (data) {
                    // console.log(data);
                    if ($.isEmptyObject(data.error)) {

                        alert('Delete Drug ?');
                        location.reload();
                    } else {
                        showAlert('Something went wrong!!');
                    }
                }
            });
        });


    });
    $('#filter').on('click', function(){
        var department = $('#department').val();

        var url = "{{route('patient-department-wise')}}";

        if (department == '' || typeof department == 'undefined' || typeof department == null) {
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                department: department,
            },
            success: function(data) {
                var html = '';
                if (data.patients.length === 0) {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    var html = '';
                    html += '<td align="center" colspan="4">No data availlable!</td>';
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                } else {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    $.each(data.patients, function(index, value) {
                        var name = (value.fldptnamefir) + ' ' + (value.fldmidname != null ? value.fldmidname : '') + ' ' + (value.fldptnamelast);
                        var gender = (value.fldptsex === 'Male' ? 'M' : 'F');

                        html += '<tr><td>' + value.fldencounterval + '</td ><td>' + name + '/' + gender + '/' + value.age + ' </td> <td>' + value.fldbed + '</td> <td><button type="button"  class="btn btn-primary btn-sm detailBtn" data-encounter="' + value.fldencounterval + '" > <i class="fas fa-arrow-right"></i> </button></td</tr>';
                    });
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }
                $('#preview').hide();
                $('#discharge_certificate').hide();

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });
    $('#department').change(function() {
        var department = $(this).val();
        var url = '{{ route("patient-department-wise") }}';

        if (department == '' || typeof department == 'undefined' || typeof department == null) {
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                department: department,
            },
            success: function(data) {
                var html = '';
                if (data.patients.length === 0) {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    var html = '';
                    html += '<td align="center" colspan="4">No data availlable!</td>';
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                } else {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    $.each(data.patients, function(index, value) {
                        var name = (value.fldptnamefir) + ' ' + (value.fldmidname != null ? value.fldmidname : '') + ' ' + (value.fldptnamelast);
                        var gender = (value.fldptsex === 'Male' ? 'M' : 'F');

                        html += '<tr><td>' + value.fldencounterval + '</td ><td>' + name + '/' + gender + '/' + value.age + ' </td> <td>' + value.fldbed + '</td> <td><button type="button"  class="btn btn-primary btn-sm detailBtn" data-encounter="' + value.fldencounterval + '" > <i class="fas fa-arrow-right"></i> </button></td</tr>';
                    });
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }
                $('#preview').hide();
                $('#discharge_certificate').hide();

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


    });
    CKEDITOR.replace('pastHistory',
    {
    height: '100px',
    } );
    CKEDITOR.replace('course_in_hospital',
    {
    height: '100px',
    } );
    CKEDITOR.replace('special_instruction',
    {
    height: '100px',
    } );
    CKEDITOR.replace('diet',
    {
    height: '100px',
    } );
    CKEDITOR.replace('consult_note',
    {
    height: '100px',
    } );
    CKEDITOR.replace('complaints',
    {
    height: '100px',
    } );
    CKEDITOR.replace('onExamination',
    {
    height: '100px',
    } );
    CKEDITOR.replace('physicalExamination',
    {
    height: '100px',
    } );
    CKEDITOR.replace('surgericalNote',
    {
    height: '100px',
    } );
    CKEDITOR.replace('advice',
    {
    height: '100px',
    } );
    //patientProfile data
    $(document).on('click', '.detailBtn', function() {
        // alert('click bahyo');
        $('form').submit(false);
        var encounter = $(this).data('encounter');
        var url = "{{ route('populate-patient-profile') }}";
        if (encounter == '' || typeof encounter == 'undefined' || typeof encounter == null) {
            return false;
        } 

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                encounter_id: encounter,
            },
            success: function(data) {

                if (data.length != 0) {
                    // alert(data.dischargedata.othergeneralData.complaints);
                    dob = new Date(data.patient.fldptbirday);
                    var today = new Date();
                    var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));


                    var name = (data.patient.fldfullname)
                    var patID = (data.enpatient.fldpatientval)
                    var encID = (data.enpatient.fldencounterval)
                    var height = (data.height)
                    var heigt_rate = (data.heightrate)
                    var bmi = (data.bmi)
                    var weight = (data.body_weight != null && data.body_weight.fldrepquali) ? data.body_weight.fldrepquali : '';
                    var doReg = (data.enpatient.fldregdate)
                    var location = (data.enpatient.fldcurrlocat === 'Discharged') ? 'Discharged' : (data.enpatient.fldcurrlocat)
                    var status = (data.enpatient.fldadmission)
                    var gender = (data.patient.fldptsex)
                    var address = (data.patient.fldptaddvill) + '/' + (data.patient.fldptadddist)
                    // alert(encID);
                    $('#selectedEncounter').val(encID);
                    $('#fldencounterval').val(encID);
                    $('#patient_id').val(encID);

                    $('#patientID').val(encID);
                    $('#encounter_id').val(encID);
                    $('#encounterid').val(encID);
                    $('#patientName').html('');
                    $('#patID').html('');
                    $('#EncID').html('');
                    $('#gender').html('');
                    $('#heightvalue').val('');
                    $('#weight').val('');
                    $('#bmi').html('');
                    $('#age').html('');
                    $('#DOReg').html('');
                    $('#location').html('');
                    $('#admitedstatus').html('');
                    $('#address').html('');
                    $('#diagnosistext').html('');
                    $('#complaints').html('');
                    $('#onExamination').val('');
                    $('#surgericalNote').val('');
                    $('#medicine').val('');
                    $('#pastHistory').val('');
                    $('#physicalExamination').val('');
                    $('#operation').html('');
                    $('#diet').html('');
                    $('#special_instruction').html('');
                    $('#course_in_hospital').html('');
                    $('#consult_note').html('');
                    $('#patient_condition').val('');
                    $('.list-group').html('');
                    $('#select-multiple-aldrug').html('');
                    $('#bed_number').val('');
                    $('#pat_department').val('');
                    $('#complaints').val('');
                    $('#pastHistory').val('');
                    $('#onExamination').val('');
                    $('#physicalExamination').val('');
                    $('#surgericalNote').val('');
                    $('#course_in_hospital').val('');
                    $('#special_instruction').val('');
                    $('#discharge_nepali_date').val('');
                    $('#discharge_english_date').val('');
                    $('#laboratory-test').val('');
                    $('#radiology-test').val('');
                    $('#diet').val('');
                    $('#consult_note').val('');
                    $('#advice').val('');
                    $('#discharge_english_date').val('');
                    $('#discharge_nepali_date').val('');
                    // $('#complaints').val(data.dischargedata.othergeneralData.complaints);
                    CKEDITOR.instances['complaints'].setData(data.dischargedata.othergeneralData.complaints);
                    if(data.dischargedata.othergeneralData.past_history !=''){
                        CKEDITOR.instances['pastHistory'].setData(data.dischargedata.othergeneralData.past_history);
                    }else{
                        CKEDITOR.instances['pastHistory'].setData(data.pasthistoryhtml);
                    }
                    CKEDITOR.instances['onExamination'].setData(data.dischargedata.othergeneralData.on_examination);
                    CKEDITOR.instances['physicalExamination'].setData(data.dischargedata.othergeneralData.physical_examination);
                    CKEDITOR.instances['surgericalNote'].setData(data.dischargedata.othergeneralData.surgerical_note);
                    CKEDITOR.instances['course_in_hospital'].setData(data.dischargedata.othergeneralData.course_in_hospital);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    if(data.dischargedata.othergeneralData.medication !=''){
                        $('#medicine').val(data.dischargedata.othergeneralData.medication);
                    }else{
                        $('#medicine').val(data.medicationhtml);
                    }

                    $('#laboratory-test').val(data.dischargedata.othergeneralData.laboratory);
                    $('#radiology-test').val(data.dischargedata.othergeneralData.radiology);
                    CKEDITOR.instances['diet'].setData(data.dischargedata.othergeneralData.diet);
                    CKEDITOR.instances['consult_note'].setData(data.dischargedata.othergeneralData.consult_note);
                    CKEDITOR.instances['advice'].setData(data.dischargedata.othergeneralData.advice);
                    // $('#patient_condition option[value="data.dischargedata.othergeneralData.patient_condition"]').prop('selected', true);
                    // $('#consultant option[value="data.dischargedata.othergeneralData.consultant"]').prop('selected', true);
                    // $('#medical_officer option[value="data.dischargedata.othergeneralData.medical_officer"]').prop('selected', true);
                    // $('#anaesthetists option[value="data.dischargedata.othergeneralData.anaesthetists"]').prop('selected', true);
                    $('#patient_condition').val(data.dischargedata.othergeneralData.patient_condition);
                    $('#consultant').val(data.dischargedata.othergeneralData.consultant);
                    $('#medical_officer').val(data.dischargedata.othergeneralData.medical_officer);
                    $('#anaesthetists').val(data.dischargedata.othergeneralData.anaesthetists);
                    $('#proc_date').val(data.dischargedata.othergeneralData.operation_date);
                    $('#englis_proc_date').val(data.dischargedata.othergeneralData.eng_operation_date);
                    $('#operative_procedures').val(data.dischargedata.othergeneralData.operative_procedures);
                    $('#operative_findings').val(data.dischargedata.othergeneralData.operative_findings);
                    $('#discharge_nepali_date').val(data.dischargedata.othergeneralData.discharge_nepali_date);
                    $('#discharge_english_date').val(data.dischargedata.othergeneralData.discharge_english_date);
                    
                    $('#patientName').html(name);
                    $('#patID').html(patID);
                    $('#EncID').html(encID);
                    $('#gender').html(gender);
                    $('#heightvalue').val(height);
                    $('#weight').val(weight);
                    $('#bmi').html(bmi);
                    $('#address').html(address);
                    $('#age').html(age + ' Years/' + gender);
                    $('#DOReg').html(doReg);
                    $('#location').html(location);
                    $('#admitedstatus').html(status);
                    $('#billingmode').empty().append((data.billing) ? data.billing : '');
                    $('#diagnosistext').html(data.diagnosishtml);
                    $('#complaints').html(data.complaintshtml);
                    $('#onExamination').val(data.onexaminationhtml);
                    $('#surgericalNote').val(data.surgicalnotehtml);

                    $('#pastHistory').val(data.pasthistoryhtml);
                    $('#physicalExamination').val(data.onexaminationhtml);
                    $('#operation').html(data.operationperformedhtml);
                    $('#select-multiple-aldrug').html(data.drughtml);
                    $('.list-group').html(data.allergicdrugshtml);
                    $('#bed_number').val(data.bed_number);
                    $('#pat_department').val(data.patientdepartment);
                    $("#billingmode option:selected").attr('disabled', 'disabled');
                    if(data.enable_freetext == 1){
                            $('#freewritingyes').show();
                            $('#freewritingno').hide();
                            $('#freeallergyyes').show();
                            $('#freeallergyno').hide();
                    }else{
                        $('#freewritingyes').hide();
                        $('#freewritingno').show();
                        $('#freeallergyyes').hide();
                        $('#freeallergyno').show();
                    }
                    if(gender == 'Female'){
                            $('#obs_div').show();
                        }else{
                            $('#obs_div').hide();
                        }
                    if (data.heightrate === 'cm') {
                        $html = '<option selected>cm </option>'
                        $('#heightrate').empty().append($html)
                    }
                    if (data.heightrate === 'm') {
                        $html = '<option selected>m </option>'
                        $('#heightrate').empty().append($html)

                    }
                    $('#patient_list').hide();
                    $('#patient_profile').removeClass('col-sm-7');
                    $('#patient_profile').addClass('col-sm-12');
                }

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });




    var dischargelaboratory = {
        displayModal: function() {
            // alert('laboratory');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.lab.list')}}",
                type: "POST",
                data: {
                    encounter_id: $('#encounterid').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#laboratory-list-modal').modal('show');
                    $('#form-data-laboratory-table-list').html(response);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }

    var dischargeradiology = {
        displayModal: function() {
            // alert('Radiology');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.radio.list') }}",
                type: "POST",
                data: {
                    encounter_id: $('#encounterid').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#radiology-list-modal').modal('show');
                    $('#form-data-radiology-table-list').html(response);


                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var dischargedoctors = {
        displayModal: function() {
            // alert('Doctors');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.doctors.list')}}",
                type: "POST",
                data: {
                    department: $('#department').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#doctors-list-modal').modal('show');
                    $('.form-data-doctors-list').html(response);


                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }
    $(document).ready(function() {
        setTimeout(function() {
            $("#department").select2({

            });

        }, 1500);
    });

    function saveDischargeDetail() {
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var cond = $('#patient_condition').val();

        if (cond == '') {
            alert('Please select patient condition');
            return false;
        }
        // alert('save discharge');
        var url = "{{route('saveDischarge')}}";
        var alldata = $("#discharge_details").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data: $("#discharge_details").serialize(),
            "_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                $('#discharge_certificate').show();
                $('#admitedstatus').text('Discharged');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

     function saveDischarge() {
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var cond = $('#patient_condition').val();

        if (cond == '') {
            alert('Please select patient condition');
            return false;
        }
        // alert('save discharge');
        var url = "{{route('save')}}";
        var alldata = $("#discharge_details").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data: $("#discharge_details").serialize(),
            "_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                $('#preview').show();
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function exportDischargeCertificate() {
        // alert('discharge certificate');
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var encounter_id = $('#encounterid').val();
        var urlReport = baseUrl + "/discharge/dischargeCertificate?encounter_id=" + encounter_id + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');
    }


    var dischargepharmacy = {
            displayModal: function() {

                $('form').submit(false);
                $('.pharmacy-form-data').empty();
                if ($('#encounterid').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }

                if($('#department').val() ==''){
                    alert('Please select department')
                    return false;
                }

                $.ajax({
                    url: "{{ route('discharge.medicineRequest')}}",
                    type: "POST",
                    data: {
                        encounterId: $('#encounterid').val(),
                        department: $('#department').val(),
                    },
                    success: function(response) {
                        // console.log(response);

                        $('.pharmacy-form-data').html(response.html);
                        $('#pharmacy-modal').find('#request_department_pharmacy').val(response.department);
                        $('#pharmacy-modal').modal('show');
                        // $('.detailBtn').trigger('click');
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }

        var dischargeallergyfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.', 'error');
                    return false;
                }
                $('.form-data-allergy-freetext').empty();
                $.ajax({
                    url: "{{ route('patient.allergy.freetext') }}",
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        $('.form-data-allergy-freetext').html(response);
                        $('#allergy-freetext-modal').modal('show');
                        // $('#allergy-freetext-modal').on('show.bs.modal', function (event) {
                        //     $('#custom_allergy').focus();
                        // });
                        setTimeout(function () {
                            $('#custom_allergy').focus();
                        }, 1500);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
        }

    $(document).on('click','.diagnosissub', function(){
            // alert('click sub bhayo');

            $('input[name="diagnosissub"]').bind('click',function() {
                $('input[name="diagnosissub"]').not(this).prop("checked", false);
            });
            var diagnosub = $("input[name='diagnosissub']");

            if (diagnosub.is(':checked')) {
                var value = $(this).val();

                $('#diagnosissubname').val(value);
            }else{
                $("#diagnosissubname").val('');
            }
        });


        $(document).on('click','.dccat', function(){
            // alert('click bhayo');

            $('input[name="dccat"]').bind('click',function() {
                $('input[name="dccat"]').not(this).prop("checked", false);
            });
            var diagnocode = $("input[name='dccat']");
            $('#code').val($(this).val());
            if (diagnocode.is(':checked')) {

                diagnocode = $(this).val() + ",";
                diagnocode = diagnocode.slice(0, -1);

                $("input[name='dccat']").attr('checked', false);

                if(diagnocode.length > 0){
                    // alert(diagnocode);
                    $.get("getDiagnosisByCode", {term: diagnocode}).done(function(data){
                        // Display the returned data in browser
                        $("#sublist").html(data);
                    });
                }
            }else{
                $("#sublist").html('');
            }
        });

        $('.onclose').on('click', function(){

            $('input[name="dccat"]').prop("checked", false);
            $('#code').val('');
            $("#diagnosissubname").val('');
            $("#sublist").val('');
        });


        $('#searchbygroup').on('click', function(){
            // alert('searchbygroup');
            var groupname = $('#diagnogroup').val();
            // alert(groupname);
            if(groupname.length > 0){
                $.get("getDiagnosisByGroup", {term: groupname}).done(function(data){
                    // Display the returned data in browser
                    $("#diagnosiscat").html(data);
                });
            }
        });
        $('#closesearchgroup').on('click', function(){
            $('#diagnogroup').val('');
            $.get("getInitialDiagnosisCategoryAjax", {term:'' }).done(function(data){
                // Display the returned data in browser
                $("#diagnosiscat").html(data);
            });

        });

        $('#deletealdiagno').on('click', function(){
            var id = $('#diagnosistext').val();
            var encounter = $('#encounterid').val();
            if(!encounter || encounter ==''){
                alert('Please select encounter id.');
                return false;
            }
            if(id !=''){
                var url = "{{ route('discharge.deleteDiagnosis') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {ids:id,encounter:encounter},
                    success: function(data) {
                        // console.log(data);
                        if(data.message){
                            showAlert(data.message);
                        }
                        if(data.html){
                            $('#diagnosistext').empty().append(data.html);
                        }
                        if(data.error){
                            showAlert(data.error,'error');
                        }
                    }
                });

            }
        });

        function updateDiagnosis() {
            // alert('diagn');
            var url = "{{route('discharge.diagnosisStore')}}";
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $("#opd-diagnosis").append($("#patient_id"));

            $.ajax({
                url: url,
                type: "POST",
                data: $("#opd-diagnosis").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    // response.log()
                    console.log(response);
                    $('#diagnosistext').html(response);
                    $('#diagnosis').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!', 'error);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        var finaldiagnosisfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    // url: "{{ route('discharge.diagnosis.freetext.final') }}",
                    url: '{{route("discharge.diagnosis.freetext.final")}}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext-final').html(response);
                        setTimeout(function () {
                            $('#custom_diagnosis').focus();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-freetext-modal-final').modal('show');
            },
        }

        var finalobstetric = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("discharge.diagnosis.final.obstetric") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-obstetric').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-obstetric-modal').modal('show');
            },
        }
