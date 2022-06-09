<div class="modal fade" id="patient-profile-modal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="patient-modal-title">Patient Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.375rem 1rem;">
                <form name="myform" id="myform">    
                    <div class="patient-form-container" style="max-height: none; ">
                        <div class="patient-form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-row">
                                        <label for="name" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Encounter:</label>
                                        <div class="col-sm-5 col-lg-7 ">
                                            <input type="text" name="encounter" class="form-control form-control-sm" id="profile_encounter" value="">
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:void(0);" class="btn btn-primary" onclick="searchEncounter();"><i class="fas fa-sync"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="name" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">File Index:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <input type="text" name="file_index" class="form-control form-control-sm" id="file_index" value="">
                                        </div>
                                        <div class="col-sm-2 ">
                                            <a href="javascript:void(0);" class="btn btn-primary" onclick="searchFileindex();"><i class="fas fa-sync"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="name" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">First Name:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="name" class="form-control form-control-sm" id="name" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">SurName:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <input type="text" name="surname" class="form-control form-control-sm" id="surname" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="province" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">Province:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <select name="province" id="province" class="col-sm-12 from-control form-control-sm"></select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">District:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <select name="district" id="district" class="col-sm-12 from-control form-control-sm">
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="municipality" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">Municipality:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <select name="municipality" id="municipality" class="col-sm-12 from-control form-control-sm"></select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="address" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Address:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="address" class="form-control form-control-sm" id="address" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="ward" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Ward No:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="ward" class="form-control form-control-sm" id="ward" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="tole" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Tole:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="tole" class="form-control form-control-sm" id="tole" value="">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-row">
                                        <label for="password" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">Encryption</label>
                                        <div class="col-sm-2  col-lg-2 text-center">
                                            <input type="checkbox" name="encryption" class="" id="encryption" value="" style="margin-top: 10px;">
                                        </div>
                                        <div class="col-sm-6 col-lg-6">
                                            <a href="javascript:void(0);" class="btn btn-primary" onclick="fileMenu.searchModalDisplay()"><i class="fas fa-search"></i>&nbsp;Search</a>
                                        </div>


                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Patient No:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <input type="text" name="patient_no" class=" form-control form-control-sm" id="patient_no" placeholder="" value="">
                                        </div>
                                        <div class="col-sm-2 col-lg-2">
                                            <a href="javascript:void(0);" style="margin-top: 5px;" onclick="searchPatient();" class="btn btn-primary btn-sm-in"><i class="fas fa-sync"></i></a>
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="name" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Mid Name:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="mid_name" class="form-control form-control-sm" id="mid_name" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="gender" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Gender:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <select name="gender" id="gender" class="col-sm-12 from-control form-control-sm">

                                            </select>

                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-2 col-lg-1 col-form-label col-form-label-sm">Age:</label>&nbsp;&nbsp;
                                        <input type="text" name="years" class="col-sm-3 col-lg-2 form-control form-control-sm" id="years" placeholder="" value="">&nbsp;

                                        <label for="bedno" class="col-form-label col-form-label-sm">Years:</label>
                                        <input type="text" name="month" class=" col-sm-3 col-lg-2 form-control form-control-sm" id="month" placeholder="" value="">&nbsp;

                                       
                                        <label for="bedno" class="col-sm-4 col-lg-2 col-form-label col-form-label-sm">Month:</label>
                                        <input type="text" class=" col-sm-4 col-lg-2 padding-none form-control form-control-sm" id="day-dob">
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3  col-form-label col-form-label-sm">DOB:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <input type="text" name="dob_patient_profile" class="col-sm-12 form-control form-control-sm" id="dob_patient_profile" placeholder="Date Of Birth" value="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">E-mail:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="" value="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="gender" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Contact No:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="contact" class="form-control form-control-sm" id="contact" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="guardian" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Guardian:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="guardian" class="form-control form-control-sm" id="guardian" value="">
                                        </div>

                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">Relation:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <input type="text" name="relation" class=" form-control form-control-sm" id="relation" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="bedno" class="col-sm-4 col-lg-3 col-form-label col-form-label-sm">Code/PAN:</label>
                                        <div class="col-sm-8 col-lg-9 ">
                                            <input type="text" name="code_pan" class=" form-control form-control-sm" id="code_pan" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="comment" class="col-sm-5 col-lg-3 col-form-label col-form-label-sm">Comment:</label>
                                        <div class="col-sm-7 col-lg-9">
                                            <input type="text" name="comment" class="form-control form-control-sm" id="comment" value="">
                                        </div>

                                    </div>                                                                        
                                    <div class="form-group form-row mt-2" style="float: right;">
                                    <div class="col-sm-12">
                                        <a href="javascript:void(0);" class="btn btn-secondary" id="clear" onclick="cleardata()"><i class="fas fa-times"></i>&nbsp;Clear</a>
                                        <a href="javascript:void(0);" class="btn btn-primary" id="update" onclick="update()"><i class="fas fa-edit"></i>&nbsp;Update</a>                                        
                                    </div>
                                </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <i class="glyphicon glyphicon-chevron-left"></i>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    var addresses = JSON.parse('{!! $addresses !!}');
    var initdistricts = JSON.parse('{!! $districts !!}');

    var selectOption = $('<option>',{val:'',text:'--Select--'});


    var districts = null;
    var municipalities = null;

    var reportMainMenu = {
        patientProfileModal: function () {
            $('#dob_patient_profile').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                yearRange: "1600:2050",
                container: '#patient-profile-modal',onSelect: function(dob) {
                    var activeForm = $('#patient-profile-modal');
                    var detail = getAgeDetail(dob);
                    $(activeForm).find('#years').val(detail.age);
                    $(activeForm).find('#month').val(detail.month);
                    $(activeForm).find('#day-dob').val(detail.day);
                }
            });
            $('.ui-datepicker').css('z-index', 9999);
            $('#patient-profile-modal').modal('show');
        },
    }

   $(document).ready(function () {
       $('#years,#month,#day-dob').keyup(function(e) {
           var activeForm = $('#patient-profile-modal');
           // this.value = this.value.replace(/[^0-9]/g,'');
           // var age = this.value;
           var age = $(activeForm).find('#years').val().replace(/[^0-9]/g,'');
           var month = $(activeForm).find('#month').val().replace(/[^0-9]/g,'');
           var day = $(activeForm).find('#day-dob').val().replace(/[^0-9]/g,'');

           var totalDays = (Number(age)*365) + (Number(month)*30) + Number(day)+1;
           var priorDate = new Date().setDate((new Date()).getDate()-totalDays);
           priorDate = new Date(priorDate);

           var dd = priorDate.getDate();
           var mm = priorDate.getMonth()+1;
           var yyyy = priorDate.getFullYear();
           if(dd<10)
               dd='0'+dd;
           if(mm<10)
               mm='0'+mm;

           // var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
           // $(activeForm).find('.js-registration-dob').val(dob);
           $(activeForm).find('#dob_patient_profile').val(yyyy+'-'+mm+'-'+dd);
       });
   });

    function revealPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function searchEncounter() {

        if ($('#profile_encounter').val() == "") {
            alert('Please select encounter id.');
            return false;
        }
        $.ajax({
            url: '{{ route('patient.mainmenu.report.patient.profile') }}',
            type: "POST",
            data: {encounterId: $('#profile_encounter').val()},
            success: function (data) {
                var res = $.parseJSON(data);
                // alert(res.result['fldptnamefir']);
                $('#name').val(res.result['fldptnamefir']);

                $('#address').val(res.result['fldptaddvill']);

                $('#gender').html(res.gender);
                $('#contact').val(res.result['fldptcontact']);
                $('#guardian').val(res.result['fldptguardian']);
                $('#comment').val(res.result['fldcomment']);
                $('#password').val(res.result['fldpassword']);

                $('#patient_no').val(res.result['fldpatientval']);
                $('#file_index').val(res.result['fldadmitfile']);
                $('#surname').val(res.result['fldptnamelast']);
                $('#district').val(res.result['fldptadddist']);
                $('#email').val(res.result['fldemail']);
                $('#relation').val(res.result['fldrelation']);
                $('#code_pan').val(res.result['fldptcode']);
                $('#dob_patient_profile').val(res.result['fldptbirday']);
                if (res.result['fldencrypt'] === 0) {
                    $("#encryption").prop("checked", false);
                } else {
                    $("#encryption").prop("checked", true);
                }

                $('#district').html(res.districts);
                $('#province').html(res.provinces);
                $('#municipality').html(res.municipal);
                $('#years').val(res.age);
                $('#month').val(res.month);
                $('#ward').val(res.result['fldwardno']);
                $('#tole').val(res.result['fldptaddvill']);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function searchPatient() {

        if ($('#patient_no').val() == "") {
            alert('Please select patient id.');
            return false;
        }
        $.ajax({
            url: '{{ route('patient.mainmenu.report.patient.profile') }}',
            type: "POST",
            data: {encounterId: $('#patient_no').val(), type: 'P'},
            success: function (data) {
                var res = $.parseJSON(data);
                // console.log(res.encounterId);
                // alert(res.result['fldptnamefir']);
                $('#name').val(res.result['fldptnamefir']);

                $('#address').val(res.result['fldptaddvill']);

                $('#gender').html(res.gender);
                $('#contact').val(res.result['fldptcontact']);
                $('#guardian').val(res.result['fldptguardian']);
                $('#comment').val(res.result['fldcomment']);
                $('#password').val(res.result['fldpassword']);

                $('#profile_encounter').val(res.encounterId);
                $('#file_index').val(res.result['fldadmitfile']);
                $('#surname').val(res.result['fldptnamelast']);
                $('#mid_name').val(res.result['fldmidname']);
                $('#district').val(res.result['fldptadddist']);
                $('#email').val(res.result['fldemail']);
                $('#relation').val(res.result['fldrelation']);
                $('#code_pan').val(res.result['fldptcode']);
                $('#dob_patient_profile').val(res.result['fldptbirday']);
                if (res.result['fldencrypt'] === 0) {
                    $("#encryption").prop("checked", false);
                } else {
                    $("#encryption").prop("checked", true);
                }

                $('#district').html(res.districts);
                $('#province').html(res.provinces);
                $('#municipality').html(res.municipal);
                $('#years').val(res.age);
                $('#month').val(res.month);
                $('#ward').val(res.result['fldwardno']);
                $('#tole').val(res.result['fldptaddvill']);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function searchFileindex() {

        if ($('#file_index').val() == "") {
            alert('Please select file index id.');
            return false;
        }
        $.ajax({
            url: '{{ route('patient.mainmenu.report.patient.profile') }}',
            type: "POST",
            data: {encounterId: $('#file_index').val(), type: 'F'},
            success: function (data) {
                var res = $.parseJSON(data);
                // alert(res.result['fldptnamefir']);
                $('#name').val(res.result['fldptnamefir']);

                $('#address').val(res.result['fldptaddvill']);

                $('#gender').html(res.gender);
                $('#contact').val(res.result['fldptcontact']);
                $('#guardian').val(res.result['fldptguardian']);
                $('#comment').val(res.result['fldcomment']);
                $('#password').val(res.result['fldpassword']);

                $('#patient_no').val(res.result['fldpatientval']);
                $('#profile_encounter').val(res.encounterId);
                $('#surname').val(res.result['fldptnamelast']);
                $('#district').val(res.result['fldptadddist']);
                $('#email').val(res.result['fldemail']);
                $('#relation').val(res.result['fldrelation']);
                $('#code_pan').val(res.result['fldptcode']);
                $('#dob_patient_profile').val(res.result['fldptbirday']);
                if (res.result['fldencrypt'] === 0) {
                    $("#encryption").prop("checked", false);
                } else {
                    $("#encryption").prop("checked", true);
                }

                $('#district').html(res.districts);
                $('#province').html(res.provinces);
                $('#municipality').html(res.municipal);
                $('#years').val(res.age);
                $('#month').val(res.month);
                $('#ward').val(res.result['fldwardno']);
                $('#tole').val(res.result['fldptaddvill']);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function update() {

        if ($('#profile_encounter').val() == "") {
            alert('Please select encounter id.');
            return false;
        }
        var name = $('#name').val();
        var mid_name = $('#mid_name').val();
        var address = $('#address').val();
        var gender = $('#gender').val();
        var contact = $('#contact').val();
        var dob_patient_profile = $('#dob_patient_profile').val();
        var guardian = $('#guardian').val();
        var comment = $('#comment').val();
        var password = $('#password').val();
        var surname = $('#surname').val();
        var district = $('#district').val();
        var province = $('#province').val();
        var muncipal = $('#municipality').val();
        var ward = $('#ward').val();
        var tole = $('#tole').val();
        var email = $('#email').val();
        var relation = $('#relation').val();
        var code_pan = $('#code_pan').val();
        if ($('#encryption').is(":checked")) {
            var encryption = 1;
        } else {
            var encryption = 0;
        }
        $.ajax({

            url: '{{ route('patient.mainmenu.report.patient.profile.update') }}',
            type: "POST",
            data: {encounterId: $('#profile_encounter').val(), name: name, mid_name: mid_name, address: address, gender: gender, contact: contact, guardian: guardian, comment: comment, password: password, surname: surname, district: district,province: province, muncipal: muncipal, ward: ward, tole: tole, email: email, relation: relation, code_pan: code_pan, encryption: encryption, dob: dob_patient_profile},
            success: function (data) {
                var res = $.parseJSON(data);
                // alert(res.result['fldptnamefir']);
                $('#name').val(res.result['fldptnamefir']);

                $('#address').val(res.result['fldptaddvill']);
                $('#years').val(res.result.fldage);
                // $('#month').val(res.result.fldage); // confused

                $('#gender').html(res.gender);
                $('#contact').val(res.result['fldptcontact']);
                $('#guardian').val(res.result['fldptguardian']);
                $('#comment').val(res.result['fldcomment']);
                $('#password').val(res.result['fldpassword']);

                $('#patient_no').val(res.result['fldpatientval']);
                $('#mid_name').val(res.result['fldmidname']);
                $('#surname').val(res.result['fldptnamelast']);
                $('#district').val(res.result['fldptadddist']);
                $('#email').val(res.result['fldemail']);
                $('#relation').val(res.result['fldrelation']);
                $('#code_pan').val(res.result['fldptcode']);

                // $('#district').html(res.districts);
                if (res.result['fldencrypt'] === 0) {
                    $("#encryption").prop("checked", false);
                } else {
                    $("#encryption").prop("checked", true);
                }
                $('#district').html(res.districts);
                $('#province').html(res.provinces);
                $('#municipality').html(res.municipal);
                $('#years').val(res.age);
                $('#month').val(res.month);
                $('#ward').val(res.result['fldwardno']);
                $('#tole').val(res.result['fldptaddvill']);

                showAlert("{{__('messages.update', ['name' => 'Profile'])}}");
                location.reload();
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert("Error updating profile!");
            }
        });
    }

    function cleardata() {

        document.getElementById("myform").reset();
        $('#district').empty();
        $('#province').empty();
        $('#municipality').empty();
        $('#gender').empty();
    }

    $("#closeinfo").bind("click", function () {
        document.getElementById("myform").reset();
        $('#district').empty();
        $('#gender').empty();
    });


    $('#province').change(function() {
        // getDistrict($(this).val(), null);
        var id = $(this).val();
        var districtId = $('#district').val();
        if(districtId==''){
            districtId = null;
        }
                $.map(addresses, function(d) {
                    if (d.fldprovince == id) {
                        districts = d.districts;
                        return false;
                    }
                });
                districts = Object.keys(districts).sort().reduce(
                    (obj, key) => {
                        obj[key] = districts[key];
                        return obj;
                    },
                    {}
                );
                var elems = $.map(districts, function(d) {
                    return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId) });
                });

                $('#district').empty().append(selectOption.clone()).append(elems);
                $('#municipality').empty().append(selectOption.clone());

    });


    $('#district').change(function() {
        var id = $(this).val();
        var municipalityId = $('#municipality').val();
        if(municipalityId){
            municipalityId = null;
        }
        var dataFldprovince = $('#province').find('option:selected').val();

        if (dataFldprovince) {
            $('#province').val(dataFldprovince);
            $.map(addresses, function(d) {
                if (d.fldprovince.toLowerCase() == dataFldprovince.toLowerCase()) {
                    districts = d.districts;
                    return false;
                }
            });
        }

        if (districts == null) {
            districts = initdistricts;
            var valueId = ucwords(id);
            $('#district option[value="' + valueId + '"]').prop('selected', true);
            $('#district').trigger('change');
            return false;
        }

        $.map(districts, function(d) {
            if (d.flddistrict.toLowerCase() == id.toLowerCase()) {
                municipalities = d.municipalities;
                return false;
            }
        });
        municipalities = municipalities.sort();
        var elems = $.map(municipalities, function(d) {
            return $('<option>', {val: d, text: d, selected: (d == municipalityId) });
        });

        $('#municipality').empty().append(selectOption.clone()).append(elems);
    });

    $('#patient-profile-modal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#district').empty();
        $('#province').empty();
        $('#municipality').empty();
        $('#gender').empty();
    })




</script>
<style>
    .patient-form-container {
        min-height: 100px;
        max-height: 500px;
        /*overflow: scroll;*/
    }

    .ui-widget.ui-widget-content {
        z-index: 9999 !important; /* has to be larger than 1050 */
    }
</style>
