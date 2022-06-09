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
                  <h4 class="card-title">Medicine Information</h4>
               </div>
            </div>
            <div class="iq-card-body">
               @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
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
               <div class="col-lg-4 col-md-12">
                    {{-- <div class="iq-search-bar custom-search">
                        <form action="#" class="searchbox">
                            <input type="text" id="medicine_listing" name="" class="text search-input" placeholder="Type here to search..." />
                        </form>
                    </div> --}}
                    <div class="form-group form-row align-items-center mt-2">
                        <label for="">Generic Name:</label>
                        <div class="col-sm-7">
                            <select name="fldcodename" class="form-control select2 select2genericname" id="genericCodeSelect" required="">
                                <option value=""> Select Generic Name </option>
                                @forelse($codes as $code)
                                <option value="{{ $code->fldcodename }}" data-id="{{ $code->fldcodename }}" {{ (old('fldcodename') && old('fldcodename') == $code->fldcodename) ? 'selected' : ''}}>{{ $code->fldcodename }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#code_modal" class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></a>
                            @include('medicine::layouts.modal.code')
                        </div>
                    </div>
                    <div class="res-table" style="max-height: 750px;" id="medicineListingTable">
                        <ul class="list-group" id="medicinelistingtable">
                         </ul>
                    </div>
               </div>
               <div class="col-lg-8 col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">    
                        <div class="iq-card-body p-0">
                            <ul class="nav nav-tabs" role="tablist" id="main-tab">
                                <li class="nav-item">
                                    <a class="nav-link active" id="medicine_tab" data-toggle="tab" href="#medicine" role="tab" aria-controls="medicine" aria-selected="false">Medicine</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="brand_tab"  href="#brand" role="tab" aria-controls="brand" aria-selected="false">Brand</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="label_tab"  href="#label" role="tab" aria-controls="label" aria-selected="false">Labelling</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="medicine" role="tabpanel" aria-labelledby="medicine">
                                    @include('medicine::medicineinfo.partials.medicines')
                                </div>
                                <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand">
                                    @include('medicine::medicineinfo.partials.brand')
                                </div>
                                <div class="tab-pane fade" id="label" role="tabpanel" aria-labelledby="label">
                                    @include('medicine::medicineinfo.partials.labeling')
                                </div>
                            </div>
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
    CKEDITOR.replace('flddetail',
            {
                height: '200px',
            });

    CKEDITOR.replace('fldmedinfo',
            {
                height: '200px',
            });
            
    CKEDITOR.replace('fldopinfo',
            {
                height: '200px',
            });

    CKEDITOR.replace('fldipinfo',
            {
                height: '200px',
            });

    CKEDITOR.replace('fldasepinfo',
            {
                height: '200px',
            });
            
</script>
@include('medicine::medicineinfo.medicine-info-js')
@endpush