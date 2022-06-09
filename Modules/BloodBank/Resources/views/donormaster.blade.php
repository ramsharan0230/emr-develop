@extends('frontend.layouts.master')

@section('content')
@include('frontend.common.alert_message')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Donar Master</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form method="POST" id="js-blood-master-form">
                        @csrf
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-4">Branch</label>
                                    <div class="col-sm-8">
                                        <select name="branch" id="branch" class="form-control">
                                            <option value="">--- Select ---</option>
                                            @foreach ($hospitalbranches as $hospitalbranch)
                                            <option value="{{ $hospitalbranch->id }}" @if (request()->get('branch') == $hospitalbranch->id) selected @endif>{{ $hospitalbranch->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(isset($form_errors['branch']))<div class="text-danger">{{ $form_errors['branch'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-3">Reg Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="registration_date" value="{{ request()->get('registration_date') }}" id="reg_nepali" class="form-control nepaliDatePicker col-sm-10">
                                        @if(isset($form_errors['registration_date']))<div class="text-danger">{{ $form_errors['registration_date'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-4">Search Donar</label>
                                    <div class="col-sm-8">
                                        <div class="iq-search-bar p-0">
                                            <div class="searchbox full-width">
                                                <input type="hidden" name="fldencounterval" id="fldencounterval">
                                                <input type="text" class="text search-input search-input-donar" id="search-patient" placeholder="Search..." style="background:none;">
                                                <a class="search-link" id="search-patient-btn" href="javascript:;"><i class="ri-search-line"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group mt-4">
                            <div class="col-sm-4">
                                <div class="form-group form-row align-items-center">
                                    <div class="col-sm-4">
                                        <label class="">Donar</label>&nbsp;
                                        <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="title" id="title" class="form-control">
                                            <option value="">---Tittle---</option>
                                            <option value="Mr." @if (request()->get('title') == "Mr.") selected @endif>Mr.</option>
                                            <option value="Mrs." @if (request()->get('title') == "Mrs.") selected @endif>Mrs.</option>
                                            <option value="Ms." @if (request()->get('title') == "Ms.") selected @endif>Ms</option>
                                        </select>
                                        @if(isset($form_errors['title']))<div class="text-danger">{{ $form_errors['title'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="fullname" value="{{ request()->get('fullname') }}" id="fullname" class="form-control">
                                @if(isset($form_errors['fullname']))<div class="text-danger">{{ $form_errors['fullname'] }} </div>@endif
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-3">Blood Grp</label>
                                    <div class="col-sm-9">
                                        <select name="blood_group" id="blood_group" class="form-control">
                                            <option value="">--- Select ---</option>
                                            <option value="A" @if (request()->get('blood_group') == "A") selected @endif>A</option>
                                            <option value="B" @if (request()->get('blood_group') == "B") selected @endif>B</option>
                                            <option value="AB" @if (request()->get('blood_group') == "AB") selected @endif>AB</option>
                                            <option value="O" @if (request()->get('blood_group') == "O") selected @endif>O</option>
                                        </select>
                                        @if(isset($form_errors['blood_group']))<div class="text-danger">{{ $form_errors['blood_group'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-5">RH Type</label>
                                    <div class="col-sm-7 p-0">
                                        <select name="rh_type" id="rh_type" class="form-control">
                                            <option value="">--- Select ---</option>
                                            <option value="Positive" @if (request()->get('rh_type') == "Positive") selected @endif>Positive</option>
                                            <option value="Negative" @if (request()->get('rh_type') == "Negative") selected @endif>Negative</option>
                                        </select>
                                        @if(isset($form_errors['rh_type']))<div class="text-danger">{{ $form_errors['rh_type'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-4">Gender</label>
                                    <div class="col-sm-8">
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="">--- Select ---</option>
                                            <option value="Male" @if (request()->get('gender') == "Male") selected @endif>Male</option>
                                            <option value="Female" @if (request()->get('gender') == "Female") selected @endif>Female</option>
                                            <option value="Others" @if (request()->get('gender') == "Others") selected @endif>Others</option>
                                        </select>
                                        @if(isset($form_errors['gender']))<div class="text-danger">{{ $form_errors['gender'] }} </div>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">DOB</label>
                                    <input type="text" name="dob" value="{{ request()->get('dob') }}" id="dob-nepali" class="form-control col-sm-10">
                                    @if(isset($form_errors['dob']))<div class="text-danger">{{ $form_errors['dob'] }} </div>@endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-3">Age</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="age" class="form-control">
                                    </div>
                                    <div class="col-sm-5">
                                        <select class="form-control">
                                            <option value="Year">Year</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="communication-tab" data-toggle="tab" href="#communication" role="tab" aria-controls="communication" aria-selected="true">Communication Address</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="permenant-tab" data-toggle="tab" href="#permenant" role="tab" aria-controls="permenant" aria-selected="false">Permenant Address</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="others-tab" data-toggle="tab" href="#others" role="tab" aria-controls="others" aria-selected="false">Others Details</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade show active" id="communication" role="tabpanel" aria-labelledby="communication-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Country</label>
                                            <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                    <div class="searchbox full-width">
                                                        <input type="text" name="temp_country" value="{{ request()->get('temp_country') }}" id="temp_country" class="text search-input search-input-donar" style="background:none;">
                                                        <a class="search-link" href="javascript:;"><i class="ri-search-line"></i></a>
                                                        @if(isset($form_errors['temp_country']))<div class="text-danger">{{ $form_errors['temp_country'] }} </div>@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">State</label>
                                            <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                    <div class="searchbox full-width">
                                                        <input type="text" name="temp_state" value="{{ request()->get('temp_state') }}" id="temp_state" class="text search-input search-input-donar" style="background:none;">
                                                        <a class="search-link" href="javascript:;"><i class="ri-search-line"></i></a>
                                                        @if(isset($form_errors['temp_state']))<div class="text-danger">{{ $form_errors['temp_state'] }} </div>@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Street</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="temp_city" value="{{ request()->get('temp_city') }}" id="temp_city" class="form-control">
                                                @if(isset($form_errors['temp_city']))<div class="text-danger">{{ $form_errors['temp_city'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Mobile</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="mobile" value="{{ request()->get('mobile') }}" id="mobile" class="form-control">
                                                @if(isset($form_errors['mobile']))<div class="text-danger">{{ $form_errors['mobile'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Phone</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="phone" value="{{ request()->get('phone') }}" id="phone" class="form-control">
                                                @if(isset($form_errors['phone']))<div class="text-danger">{{ $form_errors['phone'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="email" value="{{ request()->get('email') }}" id="email" class="form-control">
                                                @if(isset($form_errors['email']))<div class="text-danger">{{ $form_errors['email'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="permenant" role="tabpanel" aria-labelledby="permenant-tab">
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Country</label>
                                            <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                    <div class="searchbox full-width">
                                                        <input type="text" name="prem_country" value="{{ request()->get('prem_country') }}" id="prem_country" class="text search-input search-input-donar" style="background:none;">
                                                        <a class="search-link" id="header-search" href="javascript:;"><i class="ri-search-line"></i></a>
                                                        @if(isset($form_errors['prem_country']))<div class="text-danger">{{ $form_errors['prem_country'] }} </div>@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">State</label>
                                            <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                    <div class="searchbox full-width">
                                                        <input type="text" name="prem_state" value="{{ request()->get('prem_state') }}" id="prem_state" class="text search-input search-input-donar" style="background:none;">
                                                        <a class="search-link" id="header-search" href="javascript:;"><i class="ri-search-line"></i></a>
                                                        @if(isset($form_errors['prem_state']))<div class="text-danger">{{ $form_errors['prem_state'] }} </div>@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-2">Street</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="prem_city" value="{{ request()->get('prem_city') }}" id="prem_city" class="form-control">
                                                @if(isset($form_errors['prem_city']))<div class="text-danger">{{ $form_errors['prem_city'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="others" role="tabpanel" aria-labelledby="others-tab">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-4">Type:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="type" value="{{ request()->get('type') }}" id="type" class="form-control">
                                                @if(isset($form_errors['type']))<div class="text-danger">{{ $form_errors['type'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-4">Marital Status:</label>
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="is_married" value="1" class="custom-control-input" checked>
                                                    <label class="custom-control-label"> Married </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="is_married" value="0" class="custom-control-input" @if (request()->get('is_married') == '0') checked @endif>
                                                    <label class="custom-control-label"> Unmarried </label>
                                                </div>
                                                @if(isset($form_errors['is_married']))<div class="text-danger">{{ $form_errors['is_married'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-4">Food Type:</label>
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="is_veg" value="1" class="custom-control-input" checked>
                                                    <label class="custom-control-label"> Veg </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="is_veg" value="0" class="custom-control-input" @if (request()->get('is_veg') == '0') checked @endif>
                                                    <label class="custom-control-label"> Non-Veg </label>
                                                </div>
                                                @if(isset($form_errors['is_veg']))<div class="text-danger">{{ $form_errors['is_veg'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-sm-5">Last Donated On</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="last_donated" value="{{ request()->get('last_donated') }}" id="last_donated" class="form-control nepaliDatePicker">
                                                @if(isset($form_errors['last_donated']))<div class="text-danger">{{ $form_errors['last_donated'] }} </div>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group form-row mt-2">
                            <label class="col-sm-1">Remarks</label>
                            <div class="col-sm-6">
                                <input type="text" name="remarks" value="{{ request()->get('remarks') }}" id="remarks" class="form-control">
                                @if(isset($form_errors['remarks']))<div class="text-danger">{{ $form_errors['remarks'] }} </div>@endif
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary"><i class="ri-save-3-line"></i></button>
                                <button class="btn btn-primary"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $(document).ready(function () {
        var nepaliDateConverter = new NepaliDateConverter();

        setTimeout(() => {
            var reg_nepali = $('#reg_nepali').val() || '';
            if (reg_nepali == '') {
                var today = new Date();
                today = (today.getMonth()+1) + '/' + today.getDate() + '/' + today.getFullYear();
                today = (new NepaliDateConverter()).ad2bs(today);

                $('#reg_nepali').val(today)
            }
        }, 500);
        $('#search-patient').on('keydown', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                searchPatient();
            }
        });
        $('#search-patient-btn').on('click', function(e) {
            searchPatient();
        });

        $('#age').keydown(function(e) {
            if(!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8 || e.keyCode == 9))
                return false;
        });

        $('#dob-nepali').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50,
            disableDaysAfter: 1,
            onChange: function() {
                getAge();
            }
        });
        $('#age').focusin(function () {
            getAge();
        });

        function getAge() {
            var dob = $('#dob-nepali').val().split('-');
            dob = dob[1] + '/' + dob[2] + '/' + dob[0];
            dob = nepaliDateConverter.bs2ad(dob);
            var d1 = new Date();
            var d2 = new Date(dob);
            diff = new Date(d1.getFullYear()-d2.getFullYear(), d1.getMonth()-d2.getMonth(), d1.getDate()-d2.getDate());
            $('#age').val(diff.getYear());
        }

        $('#age').keyup(function(e) {
            var age = $('#age').val().replace(/[^0-9]/g,'');
            var priorDate = new Date();

            var dd = priorDate.getDate();
            var mm = priorDate.getMonth()+1;
            var yyyy = priorDate.getFullYear()-age;
            if(dd<10)
                dd='0'+dd;
            if(mm<10)
                mm='0'+mm;

            var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
            $('#dob-nepali').val(dob);
        });

        function searchPatient() {
            var text = $('#search-patient').val() || '';
            if (text !== '') {
                $.ajax({
                    url: baseUrl + "/bloodbank/donor-master/searchPatient",
                    type: "GET",
                    data: {
                        text: text,
                    },
                    dataType: "json",
                    success: function (response) {
                        var regdate = $('#reg_nepali').val();
                        var branch = $('#branch').val();
                        $('form#js-blood-master-form')[0].reset();
                        $('#search-patient').val(text);
                        $('#reg_nepali').val(regdate);
                        $('#branch').val(branch);

                        if (response) {
                            $('#fldencounterval').val(response.fldencounterval);
                            $('#title').val(response.fldtitle);
                            $('#fullname').val(response.fldfullname);

                            // todos
                            if (response.fldbloodgroup) {
                                $('#blood_group').val(response.fldbloodgroup.slice(0, -1));
                                var rh_type = response.fldbloodgroup.slice(-1) == "+" ?  "Positive" : "Negative";
                                $('#rh_type').val(rh_type);
                            }
                            $('#gender').val(response.fldptsex);

                            var dob = response.fldptbirday;
                            if (dob) {
                                dob = dob.split(' ')[0].split('-');
                                dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                                dob = nepaliDateConverter.ad2bs(dob);
                                $('#dob-nepali').val(dob);
                                $('#age').val(response.fldagestyle);
                            }

                            var city = response.fldmunicipality ? response.fldmunicipality : '';
                            city += (city && response.fldwardno) ? ' - ' + response.fldwardno : '';
                            city += (city && response.fldptadddist) ? ', ' + response.fldptadddist : (response.fldptadddist) ? response.fldptadddist : '';
                            $('#temp_country').val(response.fldcountry);
                            $('#temp_state').val(response.fldprovince);
                            $('#temp_city').val(city);
                            $('#mobile').val(response.fldptcontact);
                            $('#email').val(response.fldemail);

                            if (response.fldmaritalstatus) {
                                var value = (response.fldmaritalstatus == 'Married') ? '1' : '0';
                                $('input[type="radio"][name="is_married"][value="' + value + '"]').attr('checked', true);
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
