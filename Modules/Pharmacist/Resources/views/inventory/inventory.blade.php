@extends('frontend.layouts.master')
@push('after-styles')
<style type="text/css">
    .purchase-table {
        background-color: #fff;
    }

    .purchase-table2 {
        min-height: 350px;
        height: 350px;
        overflow: scroll;
        border: 1px solid #ccc;
        background-color: #fff;
    }

    .table-purchase-left {
        width: 100%;
        height: 1000px;
        min-height: 1000px;
        overflow-y: scroll;
        background-color: #fff;
    }

    .table-purchase-left tbody td {
        padding: 6px;
    }

    .full-width {
        width: 100%;
    }
</style>
@endpush

@section('content')
<section class="cogent-nav">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active active-back" id="outPatient" data-toggle="tab" href="#out-patient" role="tab" aria-controls="home" aria-selected="true"><span></span>Inventory Report</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="out-patient" role="tabpanel" aria-labelledby="home-tab">
         

        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="row mt-2">
                    <div class="col-md-8">
                        <div class="group__box half_box">
                            <div class="radio-1">&nbsp;&nbsp;
                                <input type="radio" name="inventory_type" value="medical" checked>
                                <label>Med</label>&nbsp;&nbsp;

                                <input type="radio" name="inventory_type" value="Surgery">
                                <label>Surg</label>&nbsp;&nbsp;

                                <input type="radio" name="inventory_type" value="dosage_form">
                                <label>Extra</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <button type="button">Load Data</button>
                    </div>
                    <div class="col-md-8">
                        <div class="group__box half_box">
                            <div class="radio-1">&nbsp;&nbsp;
                                <input type="radio" name="items" value="selected_items" checked>
                                <label>Selected Items</label>&nbsp;&nbsp;

                                <input type="radio" name="items" value="all_items">
                                <label>All Items</label>&nbsp;&nbsp;
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group-consult">
                            <label for="address" class="col-sm-3 col-form-label col-form-label-sm">From:</label>
                            <div class="col-sm-8">
                                <input type="text" name="from_date" class="form-control form-control-sm" id="from_date" value="{{date('Y-m-d H:i')}}">
                            </div>

                        </div>
                        <div class="form-group-consult">
                            <label for="address" class="col-sm-3 col-form-label col-form-label-sm">To:</label>
                            <div class="col-sm-8">
                                <input type="text" name="to_date" class="form-control form-control-sm" id="to_date" value="{{date('Y-m-d H:i')}}">
                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop