@extends('layouts.home')

@section('content')
    <div class="clearfix"></div>
    <div class="container">

        <div class="clearfix"></div>

        @if(Session::has('error_message'))
            <div class="alert alert-danger col" style="margin-top: 30px">
                <strong> {{ Session::get('error_message') }}</strong>
            </div>
        @endif
            @if(Session::has('success_message'))
                <div class="alert alert-success col" style="margin-top: 30px">
                    <strong> {{ Session::get('success_message') }}</strong>
                </div>
            @endif


        <h5>Patient Profile</h5>
        <hr>

        <form action="" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="full_name">Encounter ID</label>
                    <input type="text" name="encounter_id" class="form-control" autocomplete="off" value="">
                    <small class="help-block text-danger">{{$errors->first('encounter_id')}}</small>
                </div>
                <div class="col-md-3" style="margin-top: 32px;">
                    <button type="submit" class="btn btn-success" style="margin-top: -10px;">Search</button>
                </div>
            </div>
        </form>
        <hr>
        @if( isset($patient_info) && $patient_info  && $encounter)
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" autocomplete="off" value="{{ $patient_info->fldptnamefir ?? null }} {{ $patient_info->fldmidname ?? null }}  {{ $patient_info->fldptnamelast ?? null }}" readonly>
                    <small class="help-block text-danger">{{$errors->first('full_name')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="rank">Rank </label>
                    <input type="text" name="rank" id="rank" class="form-control" autocomplete="off" value="{{ ( isset($patient_info) && $patient_info) ? $patient_info->fldrank : null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('rank')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="user_name">Consultant </label>
                    <input type="text" name="user_name" id="user_name" class="form-control"
                           autocomplete="off" value=" {{ (isset($userDetail) && $userDetail) ? $userDetail->getFullNameAttribute() :  null }}" readonly >
                    <small
                        class="help-block text-danger">{{$errors->first('user_name')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="address">Address </label>
                    <input type="text" name="address" id="address" class="form-control"
                           autocomplete="off" value="{{ $patient_info->fldptadddist ?? null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('address')}}</small>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="sex">Sex</label>
                    <input type="text" name="sex" id="sex" class="form-control"
                           autocomplete="off" value="{{ $patient_info->fldptsex ?? null }} " readonly >
                    <small
                        class="help-block text-danger">{{$errors->first('sex')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="age">Age </label>
                    <input type="text" name="age" id="age" class="form-control"
                           autocomplete="off" value="{{(isset($patient_info) && $patient_info->fldptbirday ) ? $patient_info->fldagestyle : null }} " readonly>
                           {{-- <input type="text" name="age" id="age" class="form-control"
                           autocomplete="off" value="{{(isset($patient_info) && $patient_info->fldptbirday ) ? \Carbon\Carbon::parse($patient_info->fldptbirday)->age : null }} " readonly> --}}
                    <small
                        class="help-block text-danger">{{$errors->first('age')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="do_reg">DoReg </label>
                    <input type="text" name="do_reg" id="do_reg" class="form-control datepicker"
                           autocomplete="off" value=" {{ (isset($encounter) && $encounter->fldregdate)  ? \Carbon\Carbon::parse($encounter->fldregdate)->format('Y-m-d') : null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('do_reg')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="location_bed">Location/Bed</label>
                    <input type="text" name="location_bed" id="location_bed" class="form-control"
                           autocomplete="off" value="{{ ( isset($encounter) && $encounter->fldcurrlocat) ? $encounter->fldcurrlocat : null }} " readonly >
                    <small
                        class="help-block text-danger">{{$errors->first('location_bed')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="height">Height </label>
                    <input type="text" step="0.1" name="height" id="height" class="form-control"
                           autocomplete="off" value="{{ ( isset($patient_info_extra) && $patient_info_extra) ? $patient_info_extra->height : null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('height')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="weight">Weight </label>
                    <input type="text" step="0.1" name="weight" id="weight" class="form-control"
                           autocomplete="off" value="{{ ( isset($patient_info_extra) && $patient_info_extra) ? $patient_info_extra->weight : null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('weight')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="weight">BMI </label>
                    <input type="text" step="0.1" name="bmi" id="bmi" class="form-control"
                           autocomplete="off" value="{{ ( isset($patient_info_extra) && $patient_info_extra) ? $patient_info_extra->bmi : null }} " readonly>
                    <small
                        class="help-block text-danger">{{$errors->first('bmi')}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="bmi">Status/Admitted</label>
                    <input type="text" class="form-control" value="{{  (isset($encounter) && $encounter->fldadmission ) ? $encounter->fldadmission : null }} " readonly>
{{--                    <select name="status" class="form-control" >--}}
{{--                        <option value="Admitted"  {{ (isset($patient_info_extra) && $patient_info_extra->status = 'Admitted') ? 'selected' : null }} > Admitted</option>--}}
{{--                        <option value="Discharged" {{( isset($patient_info_extra) && $patient_info_extra->status = 'Discharged') ? 'selected' : null }} > Discharged</option>--}}
{{--                    </select>--}}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-md-6">
                    <a class="btn btn-success" href="{{ route('create',$encounter_no) }}" style="margin-right: 10px;">Add ICU Record </a>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        Generate Report
                    </button>
{{--                    <a class="btn btn-primary" href="{{ route('generate.report',$encounter_no) }}">Generate Report</a>--}}
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Please select Date</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('generate.report',$encounter_no)  }}" method="get">
                                @csrf
                            <div class="modal-body text-center" >

                                <div class="row">
                                    <div class="col-md-12">
{{--                                        <label for="report_date">From</label>--}}
                                        <input type="text" name="report_date"  id="report_date" class="form-control" autocomplete="off" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ??  null }}" required>
                                    </div>

{{--                                    <div class="col-md-6">--}}
{{--                                        <label for="report_date">To</label>--}}
{{--                                        <input type="text" name="report_date_to"  id="report_date_to" class="form-control" autocomplete="off" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ??  null }}" required>--}}
{{--                                    </div>--}}

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit">Generate</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        /*$('#other_date').click(function () {
            $('#date').val('');
            $('#report_date').show().focus();
        });

        $('#todays_date').click(function () {
            $('#report_date').hide();
        });
        $('.datepicker').nepaliDatePicker({

        });*/
    </script>
@endsection
