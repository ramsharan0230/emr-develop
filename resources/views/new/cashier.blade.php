@extends('frontend.layouts.master') 
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Cashier Payable
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
         <div class="form-group form-row">
          <label class="col-2">Group Name:</label>
          <div class="col-sm-7">
            <input type="text" name="" value="" class="form-control" />
          </div>
          <div class="col-sm-1">
            <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i></button>
          </div>
          <div class="col-sm-2">
            <button class="btn btn-primary"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;View List</button>
          </div>
        </div>
        <div class="iq-header-title">
          <h5 style="font-weight: 600;" class="card-title">
            Components:
          </h5>
        </div>
        <div class="form-group form-row">
          <div class="col-sm-6">
            <select class="form-control" value="">
              <option value="">%</option>
              <option value=""></option>
              <option value=""></option>
            </select>
          </div>
          <div class="col-sm-6">
            <select class="form-control" value="">
              <option value="">%</option>
              <option value=""></option>
              <option value=""></option>
            </select>
          </div>
        </div>
        <div class="form-group form-row">
          <label class="col-1">Quantity:</label>
          <div class="col-sm-3">
            <input type="text" value="" placeholder="0" name="" class="form-control">
          </div>
          <div class="col-sm-8 text-right">
           <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Save</button>&nbsp;
           <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Report</button>
         </div>
       </div>
     </div>
   </div>
 </div>
 <div class="col-sm-12">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="table-responsive table-dispensing">
        <table class="table table-bordered table-hover table-striped">
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
