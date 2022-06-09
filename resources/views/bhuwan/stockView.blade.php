@extends('frontend.layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Stock like design</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- <div class="table-responsive" id="stockTable">
                        <table class="table table-bordered" id="mainStockTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Price1</th>
                                    <th>Price2</th>
                                    <th>Price3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="back-red">Bhuwan</td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                                <tr>
                                    <td class="back-green">Bhuwan</td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div> -->
                    <!-- <div class="table-responsive" id="stockTable">
                        <table class="table table-bordered" id="mainStockTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>marks</th>
                                    <th>Item</th>
                                    <th>Price1</th>
                                    <th>Price2</th>
                                    <th>Price3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bhuwan</td>
                                    <td class="td-green"><i class="ri-arrow-up-s-fill h2"></i></td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                                <tr>
                                    <td>Bhuwan</td>
                                    <td class="td-red"><i class="ri-arrow-down-s-fill h2"></i></td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div> -->
                    <div class="table-responsive" id="stockTable">
                        <table class="table table-bordered" id="mainStockTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Price1</th>
                                    <th>Price2</th>
                                    <th>Price3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bhuwan <i class="ri-arrow-up-s-fill h2 td-green"></i></td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                                <tr>
                                    <td>Bhuwan <i class="ri-arrow-down-s-fill h2 td-red"></i></td>
                                    <td>2020/01/01</td>
                                    <td>1k</td>
                                    <td>2k</td>
                                    <td>3k</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
