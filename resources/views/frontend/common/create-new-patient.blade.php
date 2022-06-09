<!-- new user modal -->
@php
    $genders = Helpers::getGenders();
$billingModes = Helpers::getBillingModes();
        $surnames = Helpers::getSurnames();
        $countries = Helpers::getCountries();
        $discounts = Helpers::getDiscounts();
@endphp
<style>
    #adDate, #bsDate {
        cursor: pointer;
    }
</style>
<div class="modal fade bd-example-modal-lg" id="new-user-model" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('save.new.patient.cashier.form') }}" method="post" id="new-user-add-form">
            @csrf
            <input type="hidden" name="form_to_redirect" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create New Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group form-row col-12">
                        <div class="pl-1">
                            <label for="">Patient No <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="search-patient-no" id="search-patient-no" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="btn btn-primary" id="search-patient" name="search-patient" onclick="searchPatientInDispensing()"> <i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                        <div class="form-group form-row">
                            <div class="col-lg-2 col-sm-2 mt-2">
                                <div class="form-group">
                                    <label for="">Title <span class="text-danger">*</span></label>
                                    <select name="title" id="js-registration-title" class="form-control">
                                        <!-- <option>Select</option> -->
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 mt-2">
                                <div class="form-group">
                                    <label for="">First Name <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ request('first_name') }}" name="first_name" id="js-registration-first-name" placeholder="First Name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Middle Name</label>
                                    <input type="text" value="{{ request('middle_name') }}" name="middle_name" id="js-registration-middle-name" placeholder="Middle Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Last Name <span class="text-danger">*</span></label>
                                    <div class=" er-input p-0">
                                    <!--                                    <select name="last_name" id="js-registration-last-name-free-text" class="form-control js-registration-last-name-free-text" style="width: 100%;padding: .375rem .75rem;">
                                            <option value="">&#45;&#45;Select&#45;&#45;</option>
                                        {{--  @foreach($surnames as $surname)--}}
                                    {{--                                    <option value="{{ $surname->flditem }}" data-id="{{ $surname->fldid }}">{{ $surname->flditem }}</option>--}}
                                    {{--                                        @endforeach--}}
                                        </select>-->
                                        <input type="text" value="{{ request('last_name') }}" name="last_name" id="js-registration-last-name-free-text" placeholder="Last Name" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 mt-2">
                                <div class="form-group">
                                    <label for="">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" id="js-registration-gender" class="form-control">
                                        <option value="">--Select--</option>

                                        @if($genders)
                                            @foreach($genders as $gender)
                                                <option value="{{ $gender }}">{{ $gender }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4 mt-2">
                                <div class="form-group">
                                    <label for="">Patient Type <span class="text-danger">*</span></label>
                                    <select name="billing_mode" id="js-registration-billing-mode" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($billingModes as $billingMode)
                                            <option value="{{ $billingMode }}" {{ strtoupper($billingMode) == 'GENERAL' ? 'selected' : '' }}>{{ $billingMode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3 mt-2">
                                <label for="">Discount <span class="text-danger">*</span></label>
                                <select name="discount_scheme" class="form-control select2" id="js-discount-mode" required>
                                    <option value="">--Select--</option>
                                    @foreach($discounts as $discount)
                                        <option {{ strtoupper($discount->fldtype) == 'GENERAL' ? 'selected' : '' }} value="{{ $discount->fldtype }}" data-fldmode="{{ $discount->fldmode }}" data-fldpercent="{{ $discount->fldpercent }}" data-fldamount="{{ $discount->fldamount }}">{{ $discount->fldtype }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-3 mt-2">
                                <label for="">Contact <span class="text-danger">*</span></label>
                                <input name="contact" id="js-registration-contact" class="form-control" placeholder="Contact">
                            </div>

                            <div class="col-sm-4 mt-2">
                                <div class="d-flex flex-row justify-content-between">
                                    <label for="">Date of Birth</label>
                                    <div class="d-flex flex-row mr-3">
                                        <!-- <div class="form-check form-check-inline ml-2">
                                            <input class="form-check-input" type="radio" name="dateOptions" id="adDate" value="ad" checked>
                                            <label class="form-check-label" for="inlineRadio1" id="adDate" value="ad">AD</label>
                                        </div>
                                        <div class="form-check form-check-inline  ml-2">
                                            <input class="form-check-input" type="radio" name="dateOptions" id="bsDate" value="bs">
                                            <label class="form-check-label" for="inlineRadio2" id="bsDate" value="bs">BS</label>
                                        </div> -->
                                        <div class="ml-2">
                                            <input class="" type="radio" name="dateOptions" id="adDate" value="ad" checked>
                                            <label for="adDate">AD</label>
                                        </div>
                                        <div class="ml-2">
                                            <input class="" type="radio" name="dateOptions" id="bsDate" value="bs">
                                            <label for="bsDate">BS</label>
                                        </div>
                                    </div>
                                </div>

                                <input type="text" value="{{ request('dob') }}" name="dob" autocomplete="off" id="js-registration-dob" placeholder="Date of Birth" class="form-control js-registration-dob english-dob">
                                <input type="text" class="form-control nepali-dob" name="nep_date" id="nep_date" autocomplete="off" placeholder="Date of Birth" />
                                <input type="hidden" name="eng_from_date" id="eng_from_date" >
                                <input type="hidden" name="date_hour" id="date_hour" value="0" >
                                <input type="hidden" name="nep_from_date" id="nep_from_date" >
                            </div>
                            <!-- <div class="col-sm-2 mt-2">

                            </div> -->

                            <div class="col-sm-2 mt-2">
                                <div class="form-group">
                                    <label for="">Age <span class="text-danger">*</span> (years)</label>
                                    <input type="text" value="{{ request('year') }}" name="year" id="js-registration-age" class="form-control js-registration-age">
                                </div>
                            </div>
                             <div class="col-sm-2 er-input mt-2">
                                 <div class="form-group">
                                    <label>Months</label>
                                    <input type="text" value="{{ request('month') }}" name="month" id="js-registration-month" class="form-control js-registration-month">
                                 </div>
                            </div>
                            <div class="col-sm-2 er-input mt-2">
                                <div class="form-group">
                                    <label>Days</label>
                                    <input type="text" value="{{ request('day') }}" name="day" id="js-registration-day" class="form-control js-registration-day">
                                </div>
                            </div>

                            <div class="col-sm-2 er-input mt-2">
                                <div class="form-group">
                                    <label>Hours</label>
                                    <input min="0" type="number" autocomplete="off" value="" name="day" id="js-registration-hours" class="js-registration-hours form-control">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-2">Address Details</h6>

                        <div class="form-group form-row">
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Country <span class="text-danger">*</span></label>
                                    <select name="country" id="js-registration-country" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->fldname }}">{{ $country->fldname }}</option>
                                        @endforeach
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Province <span class="text-danger">*</span></label>
                                    <select name="province" id="js-registration-province" class="form-control">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">District <span class="text-danger">*</span></label>
                                    <select name="district" id="js-registration-district" class="form-control">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Municipality <span class="text-danger">*</span></label>
                                    <select name="municipality" id="js-registration-municipality" class="form-control">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Ward No.</label>
                                    <input type="text" value="{{ request('wardno') }}" name="wardno" id="js-registration-wardno" placeholder="Ward No." class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="form-group">
                                    <label for="">Tole</label>
                                    <input type="text" value="{{ request('tole') }}" name="tole" id="js-registration-tole" placeholder="Tole" class="form-control ">
                                </div>
                            </div>

                        <!--                                <div class="col-sm-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="">National Id</label>
                                        <input type="text" value="{{ request('national_id') }}" name="national_id" id="js-registration-national-id" placeholder="National Id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="">Citizenship No.</label>
                                        <input type="text" value="{{ request('citizenship_no') }}" name="citizenship_no" id="js-registration-citizenship-no" placeholder="Citizenship No." class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="">PAN Number</label>
                                        <input type="text" value="{{ request('pan_number') }}" name="pan_number" id="js-registration-pan-number" placeholder="PAN Number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Contact Number</label>
                                        <input type="text" value="{{ request('contact') }}" name="contact" id="js-registration-contact-number" placeholder="Contact Number" class="form-control">
                                    </div>
                                </div>-->
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-action newusercreatecashier" onclick="submitCreateNewUserForm()">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var addresses = JSON.parse('{!! $addresses !!}');
    var initdistricts = JSON.parse('{!! $districts !!}');
    var provinceSelector = 'js-registration-province';
    var districtSelector = 'js-registration-district';
    var municipalityVdcSelector = 'js-registration-municipality';
    var selectOption = $('<option>', {val: '', text: '--Select--'});

    var districts = null;
    var municipalities = null;


    /*new user*/
    $(document).ready(function () {
        $('#js-registration-country').val('NEPAL');
        $('#adDate').prop('checked',true);
        $('.nepali-dob').hide();
        $('#nep_from_date').removeAttr('value')
        $('#eng_from_date').removeAttr('value');
        var defaultCountry = 'Nepal';
        $('#js-registration-country option[value="' + defaultCountry + '"]').attr('selected', true);
        $('#js-registration-country option').trigger('change');
        getProvinces(defaultCountry, null);


        $('#js-registration-age,#js-registration-month,#js-registration-day,.js-registration-hours').keyup(function (e) {
            var activeForm = $('#new-user-add-form');
            // this.value = this.value.replace(/[^0-9]/g,'');
            // var age = this.value;
            var age = $(activeForm).find('#js-registration-age').val().replace(/[^0-9]/g, '');
            var month = $(activeForm).find('#js-registration-month').val().replace(/[^0-9]/g, '');
            var day = $(activeForm).find('#js-registration-day').val().replace(/[^0-9]/g, '');
            var hours = $(activeForm).find('.js-registration-hours').val().replace(/[^0-9]/g, '');


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
            hh = hours;

            if (dd < 10)
                dd = '0' + dd;
            if (mm < 10)
                mm = '0' + mm;
            var newdate = new Date(yyyy, today.getMonth(), today.getDate(), today.getHours(), 0, 0, 0);
            newdate.setMonth(newdate.getMonth() - mm);
            newdate.setDate(newdate.getDate()-dd);
            newdate.setHours(newdate.getHours() - hh);
            $('#date_hour').val(newdate.getHours());

            // var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
            // $(activeForm).find('.js-registration-dob').val(dob);
            $(activeForm).find('.js-registration-dob').datepicker('setDate', newdate);
            $('#nep_from_date').val(AD2BS($('#js-registration-dob').val()));

            $('#eng_from_date').val($('#js-registration-dob').val());
            $('#nep_date').val(AD2BS($('#eng_from_date').val()));
        });
    });

    $('#js-registration-dob').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        maxDate:0,
        yearRange: "1900:2050",
        container: '#patient-profile-modal', onSelect: function (dob) {
            var activeForm = $('#new-user-model');
            var detail = getAgeDetail(dob);
            $('#nep_from_date').val(AD2BS(this.value));
            $('#eng_from_date').val(this.value);
            $(activeForm).find('#js-registration-age').val(detail.age);
            $(activeForm).find('#js-registration-month').val(detail.month);
            $(activeForm).find('#js-registration-day').val(detail.day);
            $(activeForm).find('#js-registration-hours').val(0);
        }
    });

    function getAgeDetail(dob) {
        var d1 = new Date();
        var d2 = new Date(dob);
        console.log('year',d2.getFullYear())
        console.log('month',d2.getMonth())
        console.log('day',d2.getDate())
        diff = new Date(d1.getFullYear() - d2.getFullYear(), d1.getMonth() - d2.getMonth(), d1.getDate() - d2.getDate());

        return {
            age: diff.getYear(),
            month: diff.getMonth(),
            day: diff.getDate()
        }
    }

    function submitCreateNewUserForm() {
        if (document.getElementById('js-registration-title').value === "Select" || document.getElementById('js-registration-first-name').value === "" || document.getElementById('js-registration-last-name-free-text').value === "" || document.getElementById('eng_from_date').value === "") {
            showAlert('Title, Firstname, Lastname and DOB is required.', 'error');
            return false;
        }
        $('.newusercreatecashier').hide();

        $("#new-user-add-form").submit();

    }

    $(document).on('change', '#js-registration-title', function(event) {

        var title = $(this).val() || '';
        if (title != '') {


            title = title.trim().toLowerCase();
            // alert(title);

            if (title == 'mr') {
                $('#js-registration-gender option').attr('selected', false);
                $('#js-registration-gender option[value="Male"]').attr('selected', true);
            } else if (title == 'mrs' || title == 'ms') {
                $('#js-registration-gender option').attr('selected', false);
                $('#js-registration-gender option[value="Female"]').attr('selected', true);
            }


        }

    });

    $("#search-patient-no").on('keyup', function (e) {
        if (e.keyCode === 13) {
            searchPatientInDispensing();
        }
    });



    function searchPatientInDispensing() {

        if ($('#search-patient-no').val() == "") {
            alert('Please select patient id.');
            return false;
        }
        $.ajax({
            url: '{{ route('search.patient.number') }}',
            type: "GET",
            data: {patientNo: $('#search-patient-no').val()},
            success: function (data) {

                if(data.error){
                    showAlert(data.error,'error');
                    return false;
                }

                var dob = data.result.fldptbirday ? data.result.fldptbirday : '';
                if (dob != '') {
                    var detail = getAgeDetail(dob);
                    $('#js-registration-age').val(detail.age);
                    $('#js-registration-month').val(detail.month);
                    $('#js-registration-day').val(detail.day);

                    //     dob = dob.split(' ')[0].split('-');
                    //     dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    //     dob = nepaliDateConverter.ad2bs(dob);
                }
                // console.log(res.encounterId);
                // alert(res.result['fldptnamefir']);
                // $('#js-registration-title').val(data.result.fldtitle);
                if (data.result.fldtitle != '' && data.result.fldtitle != null && data.result.fldtitle != null) {
                    $('#js-registration-title option').attr('selected', false);
                    $('#js-registration-title option[value="' + data.result.fldtitle + '"]').attr('selected', true);
                }

                // $('#js-registration-billing-mode').val(data.result.latest_encounter.fldbillingmode);
                if (data.result.latest_encounter.fldbillingmode != '' && data.result.latest_encounter.fldbillingmode != null && data.result.latest_encounter.fldbillingmode != null) {
                    $('#js-registration-billing-mode option').attr('selected', false);
                    $('#js-registration-billing-mode option[value="' + data.result.latest_encounter.fldbillingmode + '"]').attr('selected', true);
                }

                if (data.result.latest_encounter.flddisctype != '' && data.result.latest_encounter.flddisctype != null && data.result.latest_encounter.flddisctype != null) {
                    $('#js-discount-mode option').attr('selected', false);
                    $('#js-discount-mode option[value="' + data.result.latest_encounter.flddisctype + '"]').attr('selected', true);
                }


                $('#js-registration-first-name').val(data.result.fldptnamefir);
                $('#js-registration-middle-name').val(data.result.fldmidname);
                $('#js-registration-last-name-free-text').val(data.result.fldptnamelast);
                $('#js-registration-gender').val(data.result.fldptsex);
                $('#js-registration-dob').val(data.result.fldptbirday);
                $('#js-registration-wardno').val(data.result.fldwardno);
                $('#js-registration-tole').val(data.result.fldwardno);
                $('#js-registration-contact').val(data.result.fldptcontact);
                $('#js-registration-country').val(data.result.fldcountry);
                $('#js-registration-province').html(data.provinces);
                $('#js-registration-district').html(data.districts);
                $('#js-registration-municipality').html(data.municipal);
                $('#js-registration-tole').val(data.result.fldptaddvill);
                // $('#js-registration-age').val(data.age);
                // $('#js-registration-month').val(data.month);
                // $('#js-registration-day').val(data.month);
            },

        });
    }

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

    $('#js-registration-country').change(function() {
        var id = $(this).val();
        getProvinces(id, null);

    });

    $('#js-registration-province').change(function() {
        getDistrict($(this).val(), null);

    });


    $('#js-registration-district').change(function() {
        getMunicipality($(this).val(), null);
    });

    function getProvinces(id, provinceId) {
        $('#' + provinceSelector).empty().append(selectOption.clone());
        $('#' + districtSelector).empty().append(selectOption.clone());
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());

        if (id == 'Other') {
            $('#' + provinceSelector).removeAttr('required');
            $('#' + provinceSelector).closest('div.form-group').find('span.text-danger').text('');
            $('#' + districtSelector).removeAttr('required');
            $('#' + districtSelector).closest('div.form-group').find('span.text-danger').text('');
            $('#' + municipalityVdcSelector).removeAttr('required');
            $('#' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('');
            return false;
        } else {
            $('#' + provinceSelector).attr('required', true);
            $('#' + provinceSelector).closest('div.form-group').find('span.text-danger').text('*');
            $('#' + districtSelector).attr('required', true);
            $('#' + districtSelector).closest('div.form-group').find('span.text-danger').text('*');
            $('#' + municipalityVdcSelector).attr('required', true);
            $('#' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('*');
        }

        if (id === "" || id === null) {
        } else {
            var elems = $.map(addresses, function (d) {
                if (d.fldprovince == provinceId)
                    districts = d.districts;

                return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId)});
            });

            $('#' + provinceSelector).empty().append(selectOption.clone()).append(elems);
            $('#' + districtSelector).empty().append(selectOption.clone());
            $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
        }

        if (id == "Nepal") {
            var elems = $.map(initdistricts, function (d) {
                return $("<option value='" + d.flddistrict + "' data-fldprovince='" + d.fldprovince + "'>" + d.flddistrict + "</option>")
            });
            $('#' + districtSelector).empty().append(selectOption.clone()).append(elems);
        }
    }

    function getDistrict(id, districtId) {
        if (id === "" || id === null) {
            $('#' + districtSelector).empty().append(selectOption.clone());
            $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
        } else {
            $.map(addresses, function (d) {
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
            var elems = $.map(districts, function (d) {
                return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId)});
            });

            $('#' + districtSelector).empty().append(selectOption.clone()).append(elems);
            $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
        }
    }

    function getMunicipality(id, municipalityId) {

        if (id === "" || id === null) {
            $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
        } else {
            var dataFldprovince = $('#' + districtSelector).find('option:selected').data('fldprovince');
            if (dataFldprovince) {
                $('#' + provinceSelector).val(dataFldprovince);
                $('#js-registration-province option[value="' + dataFldprovince + '"]').prop('selected', true);
                // $('#js-registration-province').trigger('change');

                $.map(addresses, function (d) {
                    if (d.fldprovince.toLowerCase() == dataFldprovince.toLowerCase()) {
                        districts = d.districts;
                        return false;
                    }
                });
            }

            if (districts == null) {
                districts = initdistricts;
                var valueId = ucwords(id);
                $(activeForm).find('.js-registration-district option[value="' + valueId + '"]').prop('selected', true);
                $(activeForm).find('.js-registration-district').trigger('change');
                return false;
            }

            $.map(districts, function (d) {
                if (d.flddistrict.toLowerCase() == id.toLowerCase()) {
                    municipalities = d.municipalities;
                    return false;
                }
            });

            municipalities = municipalities.sort();
            var elems = $.map(municipalities, function (d) {
                return $('<option>', {val: d, text: d, selected: (d == municipalityId)});
            });

            $('#' + municipalityVdcSelector).empty().append(selectOption.clone()).append(elems);
        }
    }
    $('.nepali-dob').hide();

    $('#nep_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            console.log('nep_date_change');
            $('#eng_from_date').val(BS2AD($('#nep_date').val()));
            var detail = getAgeDetail($('#eng_from_date').val());
            $('#js-registration-age').val(detail.age);
            $('#js-registration-month').val(detail.month);
            $('#js-registration-day').val(detail.day);
            $('#js-registration-hours').val(0);
            $('#nep_from_date').val($('#nep_date').val());
        }
    });

    $("input[name='dateOptions']").click(function(){
        if(this.value == 'ad'){
            $('.english-dob').show();
            $('.nepali-dob').hide();
            if($('#eng_from_date').val() != '') {
                $('#js-registration-dob').val($('#eng_from_date').val());
            }
        }else{
            $('.english-dob').hide();
            $('.nepali-dob').show();
            if($('#nep_from_date').val() != '') {
                $('#nep_date').val($('#nep_from_date').val());
            }
        }
    });

    $('#new-user-model').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#js-registration-district').val('');
        $('#js-registration-province').val('');
        $('#js-registration-municipality').val('');
        $('#js-registration-dob').removeAttr('value');
        $('#nep_from_date').removeAttr('value');
        $('#nep_date').removeAttr('value');
        $('#eng_from_date').removeAttr('value');
        $('#js-registration-age,#js-registration-month,#js-registration-day,.js-registration-hours').removeAttr('value');
        $('#adDate').prop('checked',true);
        $('.nepali-dob').hide();
    });



</script>
