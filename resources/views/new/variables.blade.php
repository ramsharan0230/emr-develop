@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Variables
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <input type="text" name="" class="form-control" />
                    </div>
                    <div class="form-group mt-3 text-center">
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>&nbsp;
                        <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group mt-3">
                        <div class="table-scroll-fiscal form-row">
                            <table class="table table-hovered table-striped">
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
