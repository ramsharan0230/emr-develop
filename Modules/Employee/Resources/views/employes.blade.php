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
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="form-group form-row">
                            <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="code_radio" name="search_type" class="custom-control-input"
                                           value="code"/>
                                    <label class="custom-control-label" for="code_radio">Code </label>
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
                                    <input type="radio" id="name_radio" name="search_type" class="custom-control-input"
                                           value="name"/>
                                    <label class="custom-control-label" for="name_radio"> Name </label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="name" name="name" value="" class="form-control"
                                       placeholder="Name"/>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="surname_radio" name="search_type"
                                           class="custom-control-input" value="sur_name"/>
                                    <label class="custom-control-label" for="surname_radio"> Surname </label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="sur_name" name="sur_name" value="" class="form-control"
                                       placeholder="Sur Name"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="table-responsive table-report" style="height: 815px; min-height: 815px;">
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
                <div class="panel mt-3 mb-3" style="display: block;">

                    <button type="button" class="accordion accordion-box">Personal Information<i class="fa fa-down float-right"></i></button>
                    <br>
                    <br>
                    <form class="form" action="{{ route('employees.store') }}" id="stafform">
                        @csrf
                        <div class="iq-card iq-card-block">
                            <div class="iq-card-body">
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Code.:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" name="patient_no" id="patient_no" value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">Patient Type:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <select name="patient_type" id="patientType" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($billingModes as $billingMode)
                                                <option value="{{ $billingMode }}">{{ $billingMode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                                                                  aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Name[In English]:</label>
                                    <div class="col-sm-3 col-lg-3">
                                        <input type="text" id="first_name" name="first_name" value=""
                                               class="form-control"/>
                                    </div>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="middle_name" name="middle_name" value=""
                                               class="form-control"/>
                                    </div>
                                    <div class="col-sm-3 col-lg-3">
                                        <input type="text" id="last_name" name="last_name" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Rank:</label>
                                    <div class="col-sm-3 col-lg-3">
                                        <select id="rank" name="rank" class="form-control">
                                            <option value=""></option>
                                            <option value=""></option>
                                        </select>
                                        {{--                                    <input type="text" id="rank" name="rank" value="" class="form-control"/>--}}
                                    </div>

                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label class="col-sm-2 col-lg-2">Gender:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        {{--                                    <input type="text" id="gender" name="gender" value="" class="form-control"/>--}}
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Unit:</label>
                                    <div class="col-sm-7 col-lg-8">
                                        <select id="unit" name="unit" class="form-control">
                                            <option value=""></option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                                                                  aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Email:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="email" name="email" value="" class="form-control">
                                    </div>
                                    <label class="col-sm-3 col-lg-2">DOB:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="patient_dob" name="dob" value=""
                                               class="form-control nepaliDatePicker"/>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                  aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4 col-lg-3">Contact No:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="contact" name="contact" value="" class="form-control">
                                    </div>

                                    <label class="col-sm-3 col-lg-2">Blood Grp:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <select id="blood_group" name="blood_group" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($bloodGroups as $bloodGroup)
                                                <option value="{{ $bloodGroup }}">{{ $bloodGroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                  aria-hidden="true"></i></button>
                                    </div>
                                </div>

                                <button type="button" class="accordion accordion-box">Address<i
                                        class="fa fa-down float-right"></i></button>
                                <br>
                                <br>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Address:</label>
                                    <div class="col-sm-3 col-lg-4">
                                        <input type="text" id="address" name="address" value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">District:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <select id="district" name="district" class="form-control">
                                            <option value="">--Select--</option>
                                            {{--                                        @foreach($muncipalities as $muncipaliti)--}}
                                            {{--                                            <option--}}
                                            {{--                                                value="{{ $muncipaliti->flddistrict }}">{{ $muncipaliti->flddistrict }}</option>--}}
                                            {{--                                        @endforeach--}}
                                        </select>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                                                                  aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>


                                <button type="button" class="accordion accordion-box">Other Information<i
                                        class="fa fa-down float-right"></i></button>
                                <br>
                                <br>

                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Citizenship:</label>
                                    <div class="col-sm-3 col-lg-4">
                                        <input type="text" id="citizen" name="citizen" value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">Marks:</label>
                                    <div class="col-sm-3 col-lg-4">
                                        <input type="text" id="marks" name="marks" value="" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Service:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <select id="service" name="service" class="form-control">
                                            <option value="">--Select--</option>

                                            @foreach($services as $service)
                                                <option
                                                    value="{{ $service->fldservice }}">{{ $service->fldservice }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">Join Date:</label>
                                    <div class="col-sm-3 col-lg-3">
                                        <input type="text" id="join_date" name="join_date" value=""
                                               class="form-control nepaliDatePicker"/>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Patient Status:</label>
                                    <div class="col-sm-2 col-lg-3">

                                        <select id="patient_status" name="patient_status" class="form-control">
                                            <option value="">--Select--</option>
                                        </select>
                                        {{--                                    <input type="text" id="patient_status" name="patient_status" value="" class="form-control"/>--}}
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">End Date:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="end_date" name="end_date" value=""
                                               class="form-control nepaliDatePicker"/>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Patient No:</label>
                                    <div class="col-sm-1 col-lg-2">
                                        <input type="text" id="patientNo" name="patientNo" value="" class="form-control"
                                               readonly/>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-link"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">Status:</label>
                                    <div class="col-sm-3 col-lg-4">
                                        <select id="status" name="status" class="form-control">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">OPD No:</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" id="opdNo" name="opdNo" value="" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"
                                                                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label class="col-sm-3 col-lg-2">Discount:</label>
                                    <div class="col-sm-3 col-lg-4">
                                        <select id="discount" name="discount" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($discounts as $discount)
                                                <option value="{{ $discount->fldtype }}"
                                                        data-fldmode="{{ $discount->fldmode }}"
                                                        data-fldpercent="{{ $discount->fldpercent }}"
                                                        data-fldamount="{{ $discount->fldamount }}">{{ $discount->fldtype }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 col-lg-2">Remarks:</label>
                                    <div class="col-sm-9 col-lg-10">
                                        <textarea class="form-control" id="remarks" name="remarks" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>

                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <div class="d-flex justify-content-between">
                            <button type="submit" id="save" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;Save
                            </button>&nbsp;&nbsp;
                            <button type="button" id="new" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;New
                            </button>&nbsp;&nbsp;
                            <button type="submit" id="update" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Update
                            </button>&nbsp;&nbsp;
                            <button type="button" id="delete" class="btn btn-primary"><i class="fa fa-times"></i>&nbsp;Delete
                            </button>&nbsp;&nbsp;
                            <button type="button" id="edit_discount" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Edit
                                Discount
                            </button>&nbsp;&nbsp;
                            <button type="button" id="clear" class="btn btn-primary"><i class="fa fa-times"></i>&nbsp;Clear
                            </button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>
    </form>

@endsection

@push('after-script')
    <script src="{{asset('js/employeeList.js')}}"></script>
@endpush
