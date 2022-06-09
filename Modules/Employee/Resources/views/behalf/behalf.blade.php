@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Behalf Profile
                            </h3>
                        </div>
                        <div class="d-flex">
                            <button type="submit" id="save" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;Save
                            </button>&nbsp;&nbsp;
                            <button type="button" id="new" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;New
                            </button>&nbsp;&nbsp;
                            <button type="button" id="update" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Update
                            </button>&nbsp;&nbsp;
                            <button type="button" id="close" class="btn btn-primary"><i class="fa fa-times"></i>&nbsp;Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="form-group form-row">
                            <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="code_radio" name="search_type"
                                           class="custom-control-input"/>
                                    <label class="custom-control-label" for="code_radio"> Code </label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="code" name="code" value="" class="form-control"
                                       placeholder="Code"/>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="name_radio" name="search_type"
                                           class="custom-control-input"/>
                                    <label class="custom-control-label" for="name_radio"> Name </label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name"/>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="surname_radio" name="search_type"
                                           class="custom-control-input"/>
                                    <label class="custom-control-label" for="surname_radio"> Surname </label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="sur_name" name="sur_name" value="" class="form-control"
                                       placeholder="Surname"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="table-responsive table-report">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                </tr>
                                </thead>
                                <tbody id="patientListBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <form class="form" action="#" id="stafform">
                    @csrf
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-body">
                            <div class="form-group form-row">
                                <div class="col-sm-6 text-right float-right">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-sync"
                                                                                            aria-hidden="true"></i>&nbsp;&nbsp;नेपालीमा
                                        परिवर्तन गर्नुहोस्
                                    </button>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group form-row">
                                <div class="col-sm-6">
                                    <div
                                        class="custom-control custom-checkbox custom-checkbox-color-checked custom-control-inline">
                                        <input type="checkbox" id="update_computer_patta_no"
                                               name="update_computer_patta_no" class="custom-control-input bg-primary"
                                               data-toggle="modal" data-target="#updatePattaNo"/>
                                        <label class="custom-control-label" for="update_computer_patta_no">Update
                                            Computer Patta Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div
                                    class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="block_profile" name="update_status"
                                           class="custom-control-input bg-primary" value="block"/>
                                    <label class="custom-control-label" for="block_profile"> Block Profile </label>
                                </div>
                                <div
                                    class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="upadan" name="update_status"
                                           class="custom-control-input bg-primary" value="upadan"/>
                                    <label class="custom-control-label" for="upadan">Upadan</label>
                                </div>
                                <div
                                    class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="barkashi" name="update_status"
                                           class="custom-control-input bg-primary" value="barkashi"/>
                                    <label class="custom-control-label" for="barkashi">Barkashi </label>
                                </div>
                                <div
                                    class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="unblock" name="update_status"
                                           class="custom-control-input bg-primary" value="unblock"/>
                                    <label class="custom-control-label" for="unblock">Unblock</label>
                                </div>
                                <div
                                    class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="birami" name="update_status"
                                           class="custom-control-input bg-primary" value="birami"/>
                                    <label class="custom-control-label" for="birami">Birami</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="patta_no">Computer/Patta No.:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" id="patta_no" name="patta_no" value="" class="form-control"/>
                                </div>
                                <label class="col-sm-3 col-lg-2" for="post_status">Working/Retired:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <select id="post_status" name="post_status" class="form-control">
                                        <option value="Working">Working</option>
                                        <option value="Retired">Retired</option>
                                    </select>
                                </div>
                                <div class="col-sm-1 col-lg-1">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                                                                            aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="button" class="accordion accordion-box">Personal Information<i
                                    class="fa fa-down float-right"></i></button>
                            <br>
                            <br>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="nepali_name">Name:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" id="nepali_name" name="nepali_name" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Name[In English]:</label>
                                <div class="col-sm-3 col-lg-3">
                                    <input type="text" id="first_name" name="first_name" value="" class="form-control"/>
                                </div>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" id="middle_name" name="middle_name" value=""
                                           class="form-control"/>
                                </div>
                                <div class="col-sm-3 col-lg-3">
                                    <input type="text" id="last_name" name="last_name" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="patient_dob">Date Of Birth:</label>
                                <div class="col-sm-3 col-lg-3">
                                    <input type="text" id="patient_dob" name="dob" value=""
                                           class="form-control nepaliDatePicker"/>
                                </div>

                                <div class="col-sm-1 col-lg-1">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                            aria-hidden="true"></i>
                                    </button>
                                </div>
                                <label class="col-sm-2 col-lg-2" for="age">Age:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" id="age" name="age" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="gender">Sex:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="gender" name="gender" class="form-control">
                                        <option value="">Male</option>
                                        <option value="">Female</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-lg-2" for="join_date">Join Date:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" id="join_date" name="join_date" value=""
                                           class="form-control nepaliDatePicker"/>
                                </div>
                            </div>

                            <button type="button" class="accordion accordion-box">Address<i
                                    class="fa fa-down float-right"></i></button>
                            <br><br>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="zone">Zone:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="zone" name="zone" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-lg-2" for="district">District:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <select id="district" name="district" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="municipal">Minicipality/VDC:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="municipal" name="municipal" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-lg-2" for="ward">Ward No.:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" class="form-control" id="ward" name="ward"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="house_no">House No.:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <input type="text" class="form-control" id="house_no" name="house_no"/>
                                </div>
                                <label class="col-sm-2 col-lg-2" for="tel_no">Tell No.:</label>
                                <div class="col-sm-2 col-lg-3">
                                    <input type="text" class="form-control" id="tel_no" name="tel_no"/>
                                </div>
                            </div>

                            <button type="button" class="accordion accordion-box">Other Information<i
                                    class="fa fa-down float-right"></i></button>
                            <br><br>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="regional_hospital">Regional Hospital:</label>
                                <div class="col-sm-3 col-lg-3">
                                    <select id="regional_hospital" name="regional_hospital" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-sm-1 col-lg-1">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                            aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="service">Service:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="service" name="service" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($services as $service)
                                            <option
                                                value="{{ $service->fldservice }}">{{ $service->fldservice }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-lg-5">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="rank">Rank:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="rank" name="rank" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-sm-4 col-lg-5">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3" for="unit">Unit:</label>
                                <div class="col-sm-4 col-lg-4">
                                    <select id="unit" name="unit" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-sm-4 col-lg-5">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="d-flex justify-content-center mt-3">
                                <a href="#" type="button" class="btn btn-primary" url="" id="">Patient Search</a>&nbsp;
                                <a href="#" type="button" class="btn btn-primary" url="" id=""><i
                                        class="fa fa-upload"></i></a>
                            </div>
                            <div class="form-group form-row mt-2">
                                <label class="col-sm-5 col-lg-4">Behalf Family Description:</label>

                                <div class="col-sm-7 col-lg-8 d-flex justify-content-between">
                                    <label>Discontinue</label>
                                    <p class="box-behalf bg-success text-center col-sm-1 mt-1"></p>
                                    <label>Blocked</label>
                                    <p class="box-behalf bg-danger text-center col-sm-1 mt-1"></p>
                                    <label>Upadan</label>
                                    <p class="box-behalf bg-warning text-center col-sm-1 mt-1"></p>
                                    <label>Barkashi</label>
                                    <p class="box-behalf bg-purple text-center col-sm-1 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="row">
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover ">
                                <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Code No</th>
                                    <th class="text-center">Registered Date</th>
                                    <th class="text-center">Relation</th>
                                    <th class="text-center">DOB</th>
                                    <th class="text-center">Age</th>
                                    <th class="text-center">Blood Group</th>
                                    <th class="text-center">Computer/Patta no</th>
                                </tr>
                                </thead>
                                <tbody id="patientFamily">

                                </tbody>
                            </table>
                        </div>
                        {{--                            <textarea class="form-control" rows="10">--}}

                        {{--                            </textarea>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Updating Patta No -->
    <div class="modal fade" id="updatePattaNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Enter Computer/Patta No</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input class="form-control" type="text" value="" name="updatePattaInput" id="updatePattaInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updatePattaBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script src="{{asset('js/behalf.js')}}"></script>
@endpush
