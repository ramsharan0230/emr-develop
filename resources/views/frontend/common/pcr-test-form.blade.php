<!-- new user modal -->
@php
    $genders = Helpers::getGenders();
        $surnames = Helpers::getSurnames();
        $countries = Helpers::getCountries();
        $districts = Helpers::getIMUDistricts();
        $municipalities = Helpers::getIMUMunicipalities();
        $provinces = Helpers::getIMUProvinces();

@endphp
<div class="modal fade bd-example-modal-lg" id="pcr-test-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('laboratory.admin.laboratory.syncIMU') }}" method="post" id="pcr-test-forms">
            {{ csrf_field() }}
            <input type="hidden" name="form_to_redirect" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">PCR FORM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-12">
                        <div class="form-group form-row">
                            {{-- <div class="col-lg-1 col-sm-2">
                                <label for="">Title <span class="text-danger">*</span></label>
                                <select name="title" id="js-pcr-title" class="form-control">
                                    <option>Select</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                </select>
                            </div> --}}
                            <div class="col-lg-3 col-sm-4">
                                <label for="">First Name <span class="text-danger">*</span></label>
                                <input type="text" value="{{ request('first_name') }}" name="first_name" id="js-pcr-first-name" placeholder="First Name" class="form-control" required>
                            </div>
                            <div class="col-lg-3 col-sm-3 ">
                                <label for="">Middle Name</label>
                                <input type="text" value="{{ request('middle_name') }}" name="middle_name" id="js-pcr-middle-name" placeholder="Middle Name" class="form-control">
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <label for="">Last Name<span class="text-danger">*</span> </label>
                                <div class=" er-input p-0">
                                    <input type="text" value="{{ request('last_name') }}" name="last_name" id="js-pcr-last-name-free-text" placeholder="Last Name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <label for="">Gender<span class="text-danger">*</span></label>
                                    <select name="gender" id="js-pcr-gender" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="3">Other</option>
                                    </select>
                            </div>

                            <div class="col-lg-3 mt-2">
                                <label for="">Age<span class="text-danger">*</span></label>
                                <div class=" er-input p-0">
                                    <input type="text" value="{{ request('year') }}" name="year" id="js-pcr-age" class="form-control js-pcr-age">
                                </div>
                                {{-- <label>Years</label> --}}
                            </div>
                            {{-- <div class="col-sm-3 er-input mt-2">
                                <input type="text" value="{{ request('month') }}" name="month" id="js-pcr-month" class="form-control col-lg-8 js-pcr-month">&nbsp;
                                <label>Months</label>
                            </div>
                            <div class="col-sm-3 er-input mt-2">
                                <input type="text" value="{{ request('day') }}" name="day" id="js-pcr-day" class="form-control col-lg-8 js-pcr-day">&nbsp;
                                <label>Days</label>
                            </div> --}}

                            <div class="col-sm-3 mt-2">
                                <label for="">Dob</label>
                                <div class=" er-input p-0">
                                    <input type="text" value="{{ request('dob') }}" name="dob" autocomplete="off" id="js-pcr-dob" placeholder="Date of Birth" class="form-control js-pcr-dob">
                                </div>
                            </div>

                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Caste<span class="text-danger">*</span></label>
                                    <select name="caste"
                                            class="form-control select2" id="js-pcr-caste">
                                        <option value="">--Select--</option>
                                        <option
                                                {{ (request('ethnicgroup') == '0 - Dalit') ? 'selected' : '' }} value="0">
                                            0 - Dalit
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '1 - Janajati') ? 'selected' : '' }} value="1">
                                            1 - Janajati
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '2 - Madhesi') ? 'selected' : '' }} value="2">
                                            2 - Madhesi
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '3 - Muslim') ? 'selected' : '' }} value="3">
                                            3 - Muslim
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '4 - Brahman/Chhetri') ? 'selected' : ''}} value="4">
                                           4 - Brahman/Chhetri
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '5 - Others') ? 'selected' : '' }} value="5">
                                           5 - Others
                                        </option>
                                        <option
                                                {{ (request('ethnicgroup') == '6 - Don\'t Know') ? 'selected' : '' }} value="6">
                                            6 - Don't Know
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Province<span class="text-danger">*</span></label>
                                    <select name="province" id="js-pcr-province" class="form-control">
                                        <option value="">--Select--</option>
                                        @if($provinces)
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['id'] }}">{{ $province['province_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">District<span class="text-danger">*</span></label>
                                    <select name="district" id="js-district" class="form-control">
                                        <option value="">--Select--</option>
                                        @if($districts)
                                            @foreach($districts as $district)
                                                <option value="{{ $district['id'] }}" data-province="{{$district['province_id']}}">{{ $district['district_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Municipality<span class="text-danger">*</span></label>
                                    <select name="municipality" id="js-municipality" class="form-control">
                                        <option value="">--Select--</option>
                                        @if($municipalities)
                                            @foreach($municipalities as $index => $municipality)
                                                <option value="{{ $municipality['id'] }}" data-district="{{$municipality['district_id']}}">{{ $municipality['municipality_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Ward No.<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ request('wardno') }}" name="wardno" id="js-pcr-wardno" placeholder="Ward No." class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Tole<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ request('tole') }}" name="tole" id="js-pcr-tole" placeholder="Tole" class="form-control ">
                                </div>
                            </div>

                            <div class="col-sm-3 mt-2">
                                <label for="">Contact<span class="text-danger">*</span></label>
                                <input name="contact" id="js-pcr-contact" class="form-control" placeholder="Contact">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Travelled<span class="text-danger">*</span></label>
                                    <select name="travelled" id="js-pcr-travelled" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Occupation<span class="text-danger">*</span></label>
                                    <select name="occupation" id="js-pcr-occupation" class="form-control">
                                        <option value="1">Front Line Health Worker </option>
                                        <option value="2">Doctor</option>
                                        <option value="3">Nurse</option>
                                        <option value="4">Police/Army</option>
                                        <option value="5">Business/Industry</option>
                                        <option value="6">Teacher/Student/Education</option>
                                        <option value="7">Journalist</option>
                                        <option value="8">Agriculture</option>
                                        <option value="9">Transport/Delivery</option>
                                        <option value="10">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Service For<span class="text-danger">*</span></label>
                                    <select name="service_for" id="js-pcr-service-for" class="form-control">
                                        <option value="1">PCR Swab Collection</option>
                                        <option value="2">Antigen Test</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Service Type<span class="text-danger">*</span></label>
                                    <select name="service_type" id="js-pcr-service-type" class="form-control">
                                        <option value="1">Paid Service</option>
                                        <option value="2">Free of cost service</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Sample Type<span class="text-danger">*</span></label>
                                    <select name="sample_type" id="js-pcr-sample-type" class="form-control">
                                        <option value="1">Nasopharyngeal</option>
                                        <option value="2">Oropharyngeal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Infection Type<span class="text-danger">*</span></label>
                                    <select name="infection_type" id="js-pcr-infection-type" class="form-control">
                                        <option value="1">Symptomatic</option>
                                        <option value="2">Asymptomatic</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-sm-3 mt-2">
                                <label for="">Register Date<span class="text-danger">*</span></label>
                                <input type="text"  name="register_date" autocomplete="off" id="js-pcr-register-date" placeholder="Register Date" class="form-control">

                            </div>
                           {{-- <div class="col-sm-3 mt-2">
                                <label for="">Sample Collected Date<span class="text-danger">*</span></label>
                                <input type="date"  name="sample_collected_date" autocomplete="off" id="js-pcr-sample-collected-date" placeholder="Sample Collected Date" class="form-control js-pcr-dob">

                            </div>
                            <div class="col-sm-3 mt-2">
                                <label for="">Lab Receive Date<span class="text-danger">*</span></label>
                                <input type="date"  name="lab_receive_date" autocomplete="off" id="js-pcr-lab-receive-date" placeholder="Lab Receive Date" class="form-control js-pcr-dob">

                            </div><div class="col-sm-3 mt-2">
                                <label for="">Lab Test Date<span class="text-danger">*</span></label>
                                <input type="date"  name="lab_test_date" autocomplete="off" id="js-pcr-lab-test-date" placeholder="Lab Test Date" class="form-control js-pcr-dob">
                            </div><div class="col-sm-3 mt-2">
                                <label for="">Lab Test Time<span class="text-danger">*</span></label>
                                <input type="time"  name="lab_test_time" autocomplete="off" id="js-pcr-lab-test-time" placeholder="Lab Test Time" class="form-control js-pcr-dob">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Lab Result<span class="text-danger">*</span></label>
                                    <select name="lab_result" id="js-pcr-lab-result" class="form-control">
                                        <option value="3">Positive</option>
                                        <option value="4">Negative</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-action newusercreatecashier" onclick="add_pcr_data()">Save changes</button>
                    <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var province_data='{!! json_encode($provinces) !!}';
    localStorage.setItem('province_data', province_data);
    var districts_data='{!! json_encode($districts) !!}';
    localStorage.setItem('districts_data', districts_data);
    var municipalities_data='{!! json_encode($municipalities) !!}';
    localStorage.setItem('municipalities_data', municipalities_data);
    var provinceSelector = 'js-pcr-province';
    var districtSelector = 'js-pcr-district';
    var municipalityVdcSelector = 'js-pcr-municipality';
    var selectOption = $('<option>', {val: '', text: '--Select--'});

    var districts = null;
    var municipalities = null;

    /*new user*/
    $(document).ready(function () {
        var defaultCountry = 'Nepal';
        $('#js-pcr-country option[value="' + defaultCountry + '"]').attr('selected', true);
        $('#js-pcr-country option').trigger('change');


        $('#js-pcr-age,#js-pcr-month,#js-pcr-day').keyup(function (e) {
            var activeForm = $('#pcr-test-forms');
            // this.value = this.value.replace(/[^0-9]/g,'');
            // var age = this.value;
            var age = $(activeForm).find('#js-pcr-age').val().replace(/[^0-9]/g, '');
            var month = $(activeForm).find('#js-pcr-month').val().replace(/[^0-9]/g, '');
            var day = $(activeForm).find('#js-pcr-day').val().replace(/[^0-9]/g, '');

            // var totalDays = (Number(age) * 365) + (Number(month) * 30) + Number(day) + 1;
            // var priorDate = new Date().setDate((new Date()).getDate() - totalDays);
            // priorDate = new Date(priorDate);

            // var dd = priorDate.getDate();
            // var mm = priorDate.getMonth() + 1;
            // var yyyy = priorDate.getFullYear();

            var today = new Date();
            var currentYear = today.getFullYear();
            var yyyy = currentYear - age;
            mm = month;
            dd = day;

            if (dd < 10)
                dd = '0' + dd;
            if (mm < 10)
                mm = '0' + mm;

            // var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
            // $(activeForm).find('.js-pcr-dob').val(dob);
            $(activeForm).find('.js-pcr-dob').datepicker('setDate', new Date(yyyy, mm, dd, 0, 0, 0, 0));
        });
    });

    $('#js-pcr-dob').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "1900:2050",
        container: '#patient-profile-modal', onSelect: function (dob) {
            var activeForm = $('#new-user-model');
            var detail = getAgeDetail(dob);
            $(activeForm).find('#js-pcr-age').val(detail.age);
            $(activeForm).find('#js-pcr-month').val(detail.month);
            $(activeForm).find('#js-pcr-day').val(detail.day);
        }
    });

    $('#js-pcr-register-date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "1900:2050",
    });

    function getAgeDetail(dob) {
        var d1 = new Date();
        var d2 = new Date(dob);
        diff = new Date(d1.getFullYear() - d2.getFullYear(), d1.getMonth() - d2.getMonth(), d1.getDate() - d2.getDate());

        return {
            age: diff.getYear(),
            month: diff.getMonth(),
            day: diff.getDate()
        }
    }

    function add_pcr_data() {
        if (document.getElementById('js-pcr-first-name').value === "" || document.getElementById('js-pcr-last-name-free-text').value === "" || document.getElementById('js-pcr-dob').value === "") {
            showAlert('Title, Firstname, Lastname and DOB is required.', 'error');
            return false;
        }
        if(document.getElementById('js-pcr-caste').value === ""){
            showAlert('Cast is required.', 'error');
            return false;
        }
        var fd = new FormData(document.getElementById('pcr-test-forms'));
        fd.append( 'encounter_id', $('#js-sampling-encounterid-input').val() );
        $.ajax({
            url: baseUrl + '/admin/laboratory/syncIMU',
            type: "POST",
            data: fd,
            dataType: "json",
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false,
            success: function (response) {
                showAlert("Sync Successful.");
                $('#pcr-test-modal').modal('hide');
                updateTest();
            }
        });
    }
//document.getElementById('js-pcr-title').value === "Select" ||
    // $(document).on('change', '#js-pcr-title', function(event) {

    //     var title = $(this).val() || '';
    //     if (title != '') {


    //         title = title.trim().toLowerCase();
    //         // alert(title);

    //         if (title == 'mr') {
    //             $('#js-pcr-gender option').attr('selected', false);
    //             $('#js-pcr-gender option[value="Male"]').attr('selected', true);
    //         } else if (title == 'mrs' || title == 'ms') {
    //             $('#js-pcr-gender option').attr('selected', false);
    //             $('#js-pcr-gender option[value="Female"]').attr('selected', true);
    //         }


    //     }

    // });

    // $("#search-patient-no").on('keyup', function (e) {
    //     if (e.keyCode === 13) {
    //         searchPatientInDispensing();
    //     }
    // });

    // function searchPatientInDispensing() {

    //     if ($('#search-patient-no').val() == "") {
    //         alert('Please select patient id.');
    //         return false;
    //     }
    //     $.ajax({
    //         url: '{{ route('search.patient.number') }}',
    //         type: "GET",
    //         data: {patientNo: $('#search-patient-no').val()},
    //         success: function (data) {

    //             if(data.error){
    //                 showAlert(data.error,'error');
    //                 return false;
    //             }

    //             var dob = data.result.fldptbirday ? data.result.fldptbirday : '';
    //             if (dob != '') {
    //                 var detail = getAgeDetail(dob);
    //                 $('#js-pcr-age').val(detail.age);
    //                 $('#js-pcr-month').val(detail.month);
    //                 $('#js-pcr-day').val(detail.day);

    //             }

    //             if (data.result.fldtitle != '' && data.result.fldtitle != null && data.result.fldtitle != null) {
    //                 $('#js-pcr-title option').attr('selected', false);
    //                 $('#js-pcr-title option[value="' + data.result.fldtitle + '"]').attr('selected', true);
    //             }

    //             // $('#js-pcr-billing-mode').val(data.result.latest_encounter.fldbillingmode);
    //             if (data.result.latest_encounter.fldbillingmode != '' && data.result.latest_encounter.fldbillingmode != null && data.result.latest_encounter.fldbillingmode != null) {
    //                 $('#js-pcr-billing-mode option').attr('selected', false);
    //                 $('#js-pcr-billing-mode option[value="' + data.result.latest_encounter.fldbillingmode + '"]').attr('selected', true);
    //             }

    //             if (data.result.latest_encounter.flddisctype != '' && data.result.latest_encounter.flddisctype != null && data.result.latest_encounter.flddisctype != null) {
    //                 $('#js-discount-mode option').attr('selected', false);
    //                 $('#js-discount-mode option[value="' + data.result.latest_encounter.flddisctype + '"]').attr('selected', true);
    //             }


    //             $('#js-pcr-first-name').val(data.result.fldptnamefir);
    //             $('#js-pcr-middle-name').val(data.result.fldmidname);
    //             $('#js-pcr-last-name-free-text').val(data.result.fldptnamelast);
    //             $('#js-pcr-gender').val(data.result.fldptsex);
    //             $('#js-pcr-dob').val(data.result.fldptbirday);
    //             $('#js-pcr-wardno').val(data.result.fldwardno);
    //             $('#js-pcr-tole').val(data.result.fldwardno);
    //             $('#js-pcr-contact').val(data.result.fldptcontact);
    //             $('#js-pcr-country').val(data.result.fldcountry);
    //             // $('#js-pcr-province').html(data.provinces);
    //             // $('#js-pcr-district').html(data.districts);
    //             $('#js-pcr-municipality').html(data.municipal);
    //             $('#js-pcr-tole').val(data.result.fldptaddvill);
    //             // $('#js-pcr-age').val(data.age);
    //             // $('#js-pcr-month').val(data.month);
    //             // $('#js-pcr-day').val(data.month);
    //         },

    //     });
    // }

    function getAgeDetail(dob) {
        var d1 = new Date();
        var d2 = new Date(dob);
        diff = new Date(d1.getFullYear() - d2.getFullYear(), d1.getMonth() - d2.getMonth(), d1.getDate() - d2.getDate());

        return {
            age: diff.getYear(),
            month: diff.getMonth(),
            day: diff.getDate()
        }
    }

    $('#js-pcr-province').on('change', function() {
        if(this.value){
            console.log(this.value);
            var val = this.value;
            $('#js-district option').hide().filter(function() {
                return $(this).data("province") == val;
            })
                .show();
        }

    })
        .change();

    $('#js-district').on('change', function() {
        if(this.value){
            console.log(this.value);
            var val = this.value;
            $('#js-municipality option').hide().filter(function() {
                return $(this).data("district") == val;
            })
                .show();
        }

    })
        .change();

    $('#pcr-test-modal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#js-pcr-district').val('');
        $('#js-pcr-province').val('');
        $('#js-pcr-municipality').val('');
    });


</script>
