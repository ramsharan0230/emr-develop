@extends('frontend.layouts.master')
@push('after-styles')
<style type="text/css">
    .variables-box-target{
        height: 300px;
        overflow-y: scroll;
        background-color: #fff;
        border: 1px solid #ccc;
    }
    .variables-box-list{
        list-style: none;
        padding: 0;
    }
    .variables-box-list > li {
        display: block;
        padding: 9px 3px;
    }
    .variables-box-list > li:hover,{
        cursor: pointer;
        background-color: #f3f3f3;
    }
    .variables-box-list input{
        display: none;
    }
    .variables-box-list label{
        border: none;
        display: block;
        width: 100%;
        height: 100%;
    }
    input[name="selected_target_variable"]:checked+li, input[name="selected_itemName"]:checked+li{
        background-color: #3f7cde;
        color: #fff;
    }
</style>
@endpush

@section('content')

<section class="cogent-nav">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#purchaseEntry" role="tab" aria-controls="home" aria-selected="true"><span></span>Purchase Entry</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#stockreturn" role="tab" aria-controls="stockreturn" aria-selected="true"><span></span>Stock Return</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#stocktransfer" role="tab" aria-controls="stocktransfer" aria-selected="true"><span></span>Stock Transfer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#stockconsume" role="tab" aria-controls="stockconsume" aria-selected="true" id="onclick_stock_consume"><span></span>Stock Consume</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#stockadjust" role="tab" aria-controls="stockadjust" aria-selected="true"><span></span>Stock Asumtion</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        @include('store::tabs.purchase-entry')
        @include('store::tabs.stock-return')
        @include('store::tabs.stock-transfer')
        @include('store::tabs.stock-consume')
        @include('store::tabs.stock-asumition')
    </div>
</section>

@stop

@push('after-script')
    <!-- script here -->
    <script src="{{ asset('js/store.js') }}"></script>
@endpush