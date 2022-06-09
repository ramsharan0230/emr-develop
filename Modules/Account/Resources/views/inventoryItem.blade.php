@extends('frontend.layouts.master')
@section('content')
<style>
    .accordion {
        font-weight: 400;
        background-color: #fafafa; 
        max-height: 400px;
        overflow: auto;
    }
    .accordion-item {
        position: relative;
    }
    .acc-header-content::after {
        content: "\25BE";
        font-size: 1.5rem;
        position: absolute;
        right: 1rem;
        transition: transform 0.2s ease-in-out;
    }
    .acc-header-content.active::after {
        transform: rotate(180deg);
    }
    .acc-body {
        display: none;
        line-height: 1.5rem;
    }
    .acc-header-content.active + .acc-body {
        display: block;
    }
    .acc-header-contentmed::after {
        content: "\25BE";
        font-size: 1.5rem;
        position: absolute;
        right: 1rem;
        transition: transform 0.2s ease-in-out;
    }
    .acc-header-contentmed.active::after {
        transform: rotate(180deg);
    }

    .acc-header-contentmed.active + .acc-body {
        display: block;
    }

</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Insurance Mapping
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>


            <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">

                    <div class="row">
                        <div class="col-lg-2">
                                <label>Billing Mode<span class="text-danger">*</span></label>
                        </div>
                         <div class="col-lg-4">
                                
                                    <select name="fldbillingmode" id="js-inventory-bill-mode-select" class="form-control select2 billingmode" required>
                                        <option value="">-- Select --</option>
                                        <option value="%">%</option>
                                        @foreach($billingset as $b)
                                            {{-- <option value="{{strtolower($b->fldsetname)}}" @if(isset($enpatient) && ($enpatient->fldbillingmode == strtolower($b->fldsetname)) ) selected="selected" @endif >{{$b->fldsetname}}</option> --}}
                                            <option value="{{($b->fldsetname)}}" @if(isset($enpatient) && ($enpatient->fldbillingmode == strtolower($b->fldsetname)) ) selected="selected" @endif >{{$b->fldsetname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1">
                                    <button class="btn btn-primary js-inventory-add-item" data-variable="tblbillsection" id="js-inventory-refresh-btn">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                </div>
                            
                            <!-- <div class="form-group form-row align-items-center">
                                <label class="col-sm-2">Particulars</label>
                                <div class="col-sm-6">
                                    <input type="text" name="flditemname" id="js-inventory-particulars-input" class="form-control" required>
                                </div>
                                <label class="col-sm-1">Rate<span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" name="fldrate" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <select class="form-control col-2 " name="fldbillitem" id="js-inventory-route-select">
                                    <option value="">-- Select --</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route }}">{{ $route }}</option>
                                    @endforeach
                                </select>
                                <div class="col-sm-6">
                                    <select name="flddrug" id="js-inventory-medicine-input" class="form-control">
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="fldcategory" id="js-inventory-category-input" class="form-control">
                                </div>
                            </div> -->
                            {{-- <div class="form-group d-flex justify-content-end">    --}}
                                <div class="form-group col-lg-5 text-right">
                                    {{-- <button type="button" id="" class="btn btn-primary btn-action"><i class="far fa-file-pdf"></i>&nbsp;PDF</button>
                                    <button type="button" id="" class="btn btn-primary btn-action"><i class="far fa-file-excel"></i>&nbsp;Excel</button> --}}
                                    <button type="button" id="export" value="export" class="btn btn-primary btn-action"><i class="far fa-file-alt"></i>&nbsp;Export</button>
                                    <button type="button" id="mapitem" value="mapitem" class="btn btn-primary btn-action"><i class="far fa-file-alt"></i>&nbsp;Mapped Items</button>
                                </div>
                            {{-- </div>
  --}}
                    </div>

                    
                </div>
            </div>
            </div>

                    
             
            <!-- <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" style="background-color: unset;" aria-current="page" href="#">View Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="background-color: unset;" aria-current="page" href="#">Map Items</a>
                </li>            
            </ul> -->
            <div class="d-flex flex-row align-items-start" style="width: 100%">
                <div class="col-md-5">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div id="">
                                <div class="card">
                                    <div class="d-flex flex-column mb-2">
                                        <h5 class="mb-1">Particulars</h5>
                                        <div class="input-group">
                                            <div class="form-outline" style="width: 90%">
                                                <input type="search" id="search_stockrate" class="form-control" placeholder="Search..." />
                                            </div>
                                            <button type="button" id="btnsearch_stockrate" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="accordion" id="stockratelist">
                                      
                                   
                                    </div> 
                                </div>
                            </div>
                            <!-- <div class="res-table table-sticky-th">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Item name</th>
                                        <th>Type</th>
                                        <th>Generic</th>
                                        <th>Brand</th>
                                        <th>Rate</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-inventory-item-tbody"></tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div> -->
                            {{-- <nav aria-label="..." class="mt-2  d-flex justify-content-center">
                                <ul class="pagination mb-0">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav> --}}
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mt-5">

                    <div class="d-flex flex-row justify-content-center align-items-center">
                        <button type="button" class="btn btn-primary btn-action m-1" id="arrow-left"><i class="fas fa-angle-double-left"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-action m-1" id="arrow-right"><i class="fas fa-angle-double-right"></i>

                        </button>
                    </div>                    
                </div>
                
                <div class="col-md-5">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div id="">
                                <div class="card">
                                <div class="d-flex flex-column mb-2">
                                    <div class="d-flex flex-row justify-content-start mb-1">
                                        <div class="custom-control custom-radio mr-3">
                                            <input type="radio" id="js-itemtype" value="Medicines" name="search_type" class="custom-control-input" checked>
                                            <label class="custom-control-label" for=""> Medicine</label>
                                        </div>
                                        <div class="custom-control custom-radio mr-3">
                                            <input type="radio" id="js-itemtype" value="Surgicals" name="search_type"  class="custom-control-input">
                                            <label class="custom-control-label" for=""> Surgical</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="js-itemtype" value="Extra Items" name="search_type" class="custom-control-input">
                                            <label class="custom-control-label" for=""> Extra</label>
                                        </div>
                                    </div>                                    
                                    <div class="input-group">
                                        <div class="form-outline" style="width: 90%">
                                            <input type="search" id="search_meds" class="form-control" placeholder="Search..." />
                                        </div>
                                        <button type="button" id="btnsearch_meds" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                    <div id="medicinelist">
                           
                                    </div>                                       
                                </div>
                            </div>
                            <!-- <div class="res-table table-sticky-th">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Item name</th>
                                        <th>Type</th>
                                        <th>Generic</th>
                                        <th>Brand</th>
                                        <th>Rate</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-inventory-item-tbody"></tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div> -->
                            {{-- <nav aria-label="..." class="mt-2 d-flex justify-content-center">
                                <ul class="pagination mb-0">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav> --}}
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('js/inventory_form.js')}}"></script>
   
   
@endpush

