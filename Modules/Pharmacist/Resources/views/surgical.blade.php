@extends('frontend.layouts.master')
@push('after-styles')
<style>
  ul {
    list-style-type: none;
    display: contents;
  }
</style>
@endpush
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">Surgical Information</h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="form-group form-row">
            <div class="col-sm-5">
              <div class="form-group form-row align-items-center">
                <input type="hidden" name="fldid" id="surgFldId">
                <label for="" class="col-sm-3">Category:</label>
                <div class="col-sm-9">
                  <select name="fldsurgcateg" id="fldsurgcateg" class="form-control select2 select2genericname" required>
                    <option value="">-- Select Category --</option>
                    <option value="suture">Suture</option>
                    <option value="msurg">Surgical</option>
                    <option value="ortho">Orthopedic</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group form-row align-items-center">
                <label for="" class="col-sm-4">Item Name:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="fldsurgname" id="fldsurgname">
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group form-row align-items-center">
                <div class="col-sm-12">
                  <a href="#" id="surgicalNameClear" class="btn btn-action btn-primary">Clear</a>
                  <a href="#" id="surgicalNameSave" class="btn btn-action btn-primary">Add</a>
                  <a href="#" id="surgicalNameDelete" class="btn btn-action btn-primary">Delete</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="list-group mt-2">
              @foreach ($errors->all() as $error)
              <li class="list-group-item">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @if(Session::get('success_message'))
          <div class="alert alert-success containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            {{ Session::get('success_message') }}
          </div>
          @endif
          @if(Session::get('error_message'))
          <div class="alert alert-success containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            {{ Session::get('error_message') }}
          </div>
          @endif
          <div class="row">
            <div class="col-lg-4">
              <select name="surgicalNames" id="surgicalNames" class="form-control select2">
                <option value="">-- Select Surgical Names --</option>
                @foreach($surgical_names as $surgical_name)
                <option value="{{ $surgical_name->fldid }}">{{ $surgical_name->fldsurgname }}</option>
                @endforeach
              </select>
              <div class="res-table" style="max-height: 750px;" id="surgicalListingTable">
                  <ul class="list-group mt-2" id="surgicallistingtable">
                  </ul>
              </div>
            </div>
            <div class="col-lg-8">
              <ul class="nav nav-tabs" role="tablist" id="main-tab" style="display: none">
                <li class="nav-item">
                  <a class="nav-link active" id="msurg-ortho_tab" href="#msurg-ortho" role="tab" aria-controls="msurg-ortho" aria-selected="false">Surgical and Orthopedic</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="suture_tab" href="#suture" role="tab" aria-controls="suture" aria-selected="false">Suture </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="brand_tab" href="#brand" role="tab" aria-controls="brand" aria-selected="false">Brand</a>
                </li>
              </ul>
              <div class="tab-content" style="display: none">
                <div class="tab-pane fade show active" id="msurg-ortho" role="tabpanel" aria-labelledby="msurg-ortho">
                  @include('pharmacist::partials.msurg-ortho')
                </div>
                <div class="tab-pane fade" id="suture" role="tabpanel" aria-labelledby="suture">
                  @include('pharmacist::partials.suture')
                </div>
                <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand">
                  @include('pharmacist::partials.brand')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('after-script')
<script>
</script>
@include('pharmacist::surgical-js')
@endpush
