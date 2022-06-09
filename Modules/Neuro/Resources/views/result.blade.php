@extends('layouts.home')
@section('content')
    <div class="container">

        <h5> Patient Details/Profile/</h5>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="encounter_no">Encounter No</label>
                <input type="text" name="encounter_no" id="encounter_no" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " readonly>
                <small
                    class="help-block text-danger">{{$errors->first('encounter_no')}}</small>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('full_name')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="rank">Rank </label>
                <input type="text" name="rank" id="rank" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('rank')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="user_name">User Name </label>
                <input type="text" name="user_name" id="user_name" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('user_name')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="address">Address </label>
                <input type="text" name="address" id="address" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('address')}}</small>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="sex">Sex</label>
                <input type="text" name="sex" id="sex" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('sex')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="age">Age </label>
                <input type="text" name="age" id="age" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('age')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="do_reg">DoReg </label>
                <input type="text" name="do_reg" id="do_reg" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('do_reg')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="location_bed">Location/Bed</label>
                <input type="text" name="location_bed" id="location_bed" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('location_bed')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="height">Height </label>
                <input type="text" name="height" id="height" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('height')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="weight">Weight </label>
                <input type="text" name="weight" id="weight" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('weight')}}</small>
            </div>
            <div class="form-group col-md-3">
                <label for="status">Admitted/Status </label>
                <input type="text" name="status" id="status" class="form-control"
                       autocomplete="off" value="{{ $encounterNo ?? null }} " >
                <small
                    class="help-block text-danger">{{$errors->first('status')}}</small>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <a class="btn btn-success" href="{{ route('create') }}">Add ICU Record </a>
            </div>
            <div class="form-group col-md-3 col-md-offset-3">
                <a class="btn btn-success" href="#">Generate Report</a>
            </div>
        </div>
    </div>
@endsection
