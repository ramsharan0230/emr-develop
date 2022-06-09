@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-header-title">
                <h4 class="card-title mt-2 ml-2">
                    {{$supplier}}
                </h4>
            </div>
            <div class="iq-card-body">
                <div class="form-group form-row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-4">From:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($from_date) ? $from_date : ''}}" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="" class="col-sm-4">To:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($to_date) ? $to_date : ''}}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 offset-sm-1">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-5">Purchase Ref. No.:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="purchase_ref" id="purchase_ref" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="" class="col-sm-5">Bill No.:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="bill_no" id="bill_no" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 offset-sm-1">
                        <div class="form-group form-row">
                            <div class="col-sm-12">
                                <div class="d-flex mb-1">
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="searchSupplierPurchase()"><i class="fa fa-code"></i>&nbsp;
                                    Refresh</a>
                                </div>
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
            <div class="table-responsive table-container" id="supplier-table-container">
              <table class="table table-bordered table-hover table-striped ">
                <thead class="thead-light">
                  <tr>
                    <th></th>
                    <th>Purchase Date</th>
                    <th>Purchase Ref. No.</th>
                    <th>Bill No.</th>
                     <th>Pur Type</th>
                    <th>Total Item</th>
                      <th>SubTotal Amount</th>
                    <th>Individual Discount</th>
                    <th>Group Discount</th>
                      <th>Individual Tax</th>
                    <th>Group Tax</th>
                    <th>Final Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="supplier_purchases">
                    @foreach ($purchaseDatas as $key=>$purchaseData)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{ \Carbon\Carbon::parse($purchaseData->Pur_Date)->format('Y-m-d')}}</td>
                            <td>{{$purchaseData->Purchase_BillNo}}</td>

                            <td>{{$purchaseData->Bill_No}}</td>
                            <td>{{$purchaseData->Purchase_Type}}</td>

                            <td>{{$purchaseData->Total_Item}}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat(($purchaseData->Total_Amount)) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat(($purchaseData->Individual_Discount)) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat(($purchaseData->Group_discount)) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat(($purchaseData->Individual_Tax)) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat(($purchaseData->Group_Tax)) }}</td>
                            <td>Rs. {{  \App\Utils\Helpers::numberFormat(($purchaseData->Final_Amount)) }}</td>
                            <td>
                                <button type="button" class="btn btn-primary viewpurchasedetails" data-purreference="{{ $purchaseData->Purchase_BillNo }}"><i class="fas fa-eye"></i>&nbsp;View</button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="13">{{$paginations}}</td>
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
@push('after-script')
<script>
    $('#from_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
    });

    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
    });

    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        searchSupplierPurchase(page);
    });

    function searchSupplierPurchase(page){
        var supplier = "{{$supplier}}";
        var url = baseUrl + "/purchase/supplier-info/get-suppliers-detail-ajax/" + supplier;
        $.ajax({
            url: url+"?page="+page,
            type: "GET",
            data:  {
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        purchase_ref: $('#purchase_ref').val(),
                        bill_no: $('#bill_no').val()
                    },
            success: function(response) {
                if(response.data.status){
                    $('#supplier_purchases').html('');
                    $('#supplier_purchases').html(response.data.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    $(document).on('click','.viewpurchasedetails',function(){
        window.open(baseUrl + '/purchaseentry/export?fldreference=' + $(this).attr('data-purreference'), '_blank');
    });
</script>
@endpush
