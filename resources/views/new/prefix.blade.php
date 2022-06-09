@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Encounter Id
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="row">
            <div class="col-sm-6">
              <label>Prefix Text</label>

              <input type="text" class="form-control" name="" placeholder="E">
            </div>
            <div class="col-sm-6">
              <label>Integer Length</label>

              <input type="text" class="form-control" name="" placeholder="0">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
             Patient Number
           </h4>
         </div>
       </div>
       <div class="iq-card-body">
        <div class="row">
          <div class="col-sm-6">
            <label>Prefix Text</label>

            <input type="text" class="form-control" name="" placeholder="E">
          </div>
          <div class="col-sm-6">
            <label>Integer Length</label>

            <input type="text" class="form-control" name="" placeholder="0">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
      <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
          <h4 class="card-title">
            Booking Number
          </h4>
        </div>
      </div>
      <div class="iq-card-body">
        <div class="row">
          <div class="col-sm-6">
            <label>Prefix Text</label>

            <input type="text" class="form-control" name="" placeholder="E">
          </div>
          <div class="col-sm-6">
            <label>Integer Length</label>

            <input type="text" class="form-control" name="" placeholder="0">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
      <div class="iq-card-body">
        <div class="form-group mt-4">
          <label>Hospital Code</label>

          <input type="text" class="form-control" name="" placeholder="E">
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="d-flex justify-content-center mt-3 text-center mb-5">
      <a href="#" type="button" class="btn btn-primary rounded-pill">
        <i class="fa fa-edit"></i> Update</a>

      </div>
    </div>
  </div>
</div>
@endsection
