@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                           Fiscal Year
                       </h4>
                   </div>
               </div>
               <div class="iq-card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Label</label>

                        <input type="text" class="form-control" name="">
                    </div>
                    <div class="col-sm-4">
                        <label>Start Date</label>

                        <input type="Date" class="form-control" name="">
                    </div>
                    <div class="col-sm-4">
                        <label>End Date</label>

                        <input type="Date" class="form-control" name="">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <a href="#" type="button" class="btn btn-primary rounded-pill">
                        <i class="fa fa-plus"></i> Add</a>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="res-table">
                        <table class="table table-bordered table-striped table-hover">
                           <tbody></tbody>
                       </table>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection
