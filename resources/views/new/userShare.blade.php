@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              User Share
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="form-group form-row">
            <label class="col-2">User Name:</label>
            <div class="col-sm-5">
              <input type="text" name="" value="" class="form-control" />
            </div>
            <div class="col-sm-3">
              <button class="btn btn-primary"><i class="fa fa-user" aria-hidden="true"></i></button>&nbsp;
              <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i></button>
            </div>
            <div class="col-sm-2 text-right">
              <button class="btn btn-primary"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;View List</button>
            </div>
          </div>
          <div class="form-group form-row">
            <div class="col-sm-6">
              <select class="form-control" value="">
                <option value="">Service</option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="col-sm-5">
              <select class="form-control" value="">
                <option value=""></option>
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="col-sm-1 text-right">
              <button class="btn btn-primary"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group form-row">
                <label class="col-3">Catogery:</label>
                <div class="col-sm-9">
                  <select class="form-control" value="">
                    <option value="">payable</option>
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group form-row">
                <label class="col-3">Share %:</label>
                <div class="col-sm-9">
                  <input type="text" name="" value="" class="form-control" placeholder="25" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group form-row">
                <label class="col-2">Tax %:</label>
                <div class="col-sm-10">
                  <input type="text" name="" value="" class="form-control" placeholder="7" />
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button class="btn rounded-pill btn-info"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Export</button>&nbsp;
            <button class="btn rounded-pill btn-warning"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Save</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="table-responsive table-dispensing">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Catogery</th>
                  <th>Particulars</th>
                  <th>Share %</th>
                  <th>Tax %</th>
                  <th></th>
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
