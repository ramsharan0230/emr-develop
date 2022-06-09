

@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h3 class="card-title">
                     Despensing List
                  </h3>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <div class="form-group form-row align-items-center form-row">
                  <div class="col-sm-9">
                     <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="customCheck-t" checked="">
                        <label class="custom-control-label" for="customCheck-t">InPatient</label>
                     </div>
                     <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="customCheck-f">
                        <label class="custom-control-label" for="customCheck-f">Outpatient</label>
                     </div>
                  </div>
                  <div class="col-sm-3 p-0">
                     <button class="btn btn-primary btn-sm-in"><i class="fa fa-sync" aria-hidden="true"></i></button>
                     <button class="btn btn-primary btn-sm-in"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </div>
               <div class="form-group form-row align-items-center">
                  <label for="" class="col-sm-4">Department:</label>
                  <div class="col-sm-8">
                     <select name="" class="form-control">
                        <option value=""></option>
                        <option value=""></option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group form-row">
                        <label class="col-3">Full Name</label>
                        <div class="col-sm-9">
                           <input type="text" name="" value="" class="form-control">
                        </div>
                     </div>
                     <div class="form-group form-row">
                        <label class="col-3">Address</label>
                        <div class="col-sm-9">
                           <input type="text" name="" value="" class="form-control">
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group form-row">
                        <label class="col-3 padding-none">Gender</label>
                        <div class="col-sm-9">
                           <input type="text" name="" value="" class="form-control">
                        </div>
                     </div>
                     <div class="form-group form-row">
                        <label class="col-3 padding-none">Location</label>
                        <div class="col-sm-9">
                           <input type="text" name="" value="" class="form-control">
                        </div>
                     </div>
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
                           <th>Location</th>
                           <th>Encounter</th>
                           <th>Patient Name</th>
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
                           <th>DateTime</th>
                           <th>Route</th>
                           <th>Particular</th>
                           <th>Dose</th>
                           <th>Freq</th>
                           <th>Da</th>
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

