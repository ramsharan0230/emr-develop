@extends('frontend.layouts.master') 
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="form-group form-row align-items-center form-row">
            <button class="btn btn-primary full-width"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>
        </div>
        <div class="form-group form-row align-items-center">
            <label for="" class="col-sm-3">Plan For:</label>
            <div class="col-sm-9">
              <input type="datetime-local" class="form-control" id="exampleInputdatetime" value="2019-12-19T13:45:00" />
          </div>
      </div>
  </div>
</div>
</div>
<div class="col-sm-8">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="form-group form-row">
        <div class="col-sm-5">
          <input type="date" class="form-control" id="exampleInputdatetime" value="2019-12-19" />
      </div>
      <div class="col-sm-7">
          <select class="form-control" value="">
            <
            <option value="">%</option>
            <
            <option value=""></option>
            <
            <option value=""></option>
        </select>
    </div>
</div>
<div class="form-group form-row text-right">
    <div class="col-sm-12 text-right">
      <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>&nbsp; <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
      <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i></button>
  </div>
</div>
</div>
</div>
</div>
<div class="col-sm-4">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="table-responsive table-dispensing">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th></th>
              <th>Particulars</th>
          </tr>
      </thead>
      <tbody></tbody>
  </table>
</div>
</div>
</div>
</div>
<div class="col-sm-8">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="table-responsive table-dispensing">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th></th>
              <th>Time</th>
              <th>Particular</th>
              <th>EncID</th>
              <th>Name</th>
              <th>Age/Sex</th>
              <th>Contact</th>
          </tr>
      </thead>
      <tbody></tbody>
  </table>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection
