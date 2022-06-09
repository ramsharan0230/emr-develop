@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Supplier Information
            </h4>
          </div>
        </div>
        <form action="{{ route('insert.supplier.info') }}" method="post" id="supplierInfoForm">
          @csrf
          <div class="iq-card-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group form-row">
                  <label class="col-sm-7 col-lg-5">Supplier Name:</label>
                  <div class="col-sm-5 col-lg-7">
                  <input type="text" class="form-control" name="suppname" id="suppname" value="{{ old('suppname') }}" required>
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-7 col-lg-5 ">Supplier Address:</label>
                <div class="col-sm-5 col-lg-7">
                <input type="text" class="form-control" name="suppaddress" id="suppaddress" value="{{ old('suppaddress') }}">
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-7 col-lg-5 ">Payment Mode:</label>
              <div class="col-sm-5 col-lg-7">
                <select class="form-control" name="paymentmode" id="paymentmode">
                  <option value="Cash Payment" {{ (old('paymentmode') && old('paymentmode') == "Cash Payment") ? 'selected' : ''}}>Cash Payment</option>
                  <option value="Credit Payment" {{ (old('paymentmode') && old('paymentmode') == "Credit Payment") ? 'selected' : ''}}>Credit Payment</option>
                </select>
              </div>
            </div>
            <div class="form-group form-row">
                <label class="col-sm-7 col-lg-5 ">PAN No.:</label>
                <div class="col-sm-5 col-lg-7">
                  <input type="text" class="form-control" name="pan_no" id="pan_no">
                </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group form-row">
              <label class="col-sm-6 col-lg-4">Curr Status:</label>
              <div class="col-sm-6 col-lg-8">
                <select class="form-control" name="active" id="active" required>
                  <option value="Active" {{ (old('active') && old('active') == "Active") ? 'selected' : ''}}>Active</option>
                  <option value="Inactive" {{ (old('active') && old('active') == "Inactive") ? 'selected' : ''}}>Inactive</option>
                </select>
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-6 col-lg-4">Starting Date:</label>
              <div class="col-sm-6 col-lg-8">
                <input type="date" class="form-control" name="startdate" id="startdate">
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-6 col-lg-4">Credit Days:</label>
              <div class="col-sm-6 col-lg-8">
                <input type="number" class="form-control" name="creditday" id="creditday">
              </div>
            </div>
            <div class="form-group form-row">
                <label class="col-sm-6 col-lg-4 ">VAT No.:</label>
                <div class="col-sm-6 col-lg-8">
                  <input type="text" class="form-control" name="vat_no" id="vat_no">
                </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group form-row">
              <label class="col-sm-7 col-lg-5 ">Contact Name:</label>
              <div class="col-sm-5 col-lg-7">
                <input type="text" class="form-control" name="contactname" id="contactname">
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-7 col-lg-5 ">Supplier Phone:</label>
              <div class="col-sm-5 col-lg-7">
                <input type="tel" class="form-control" name="suppphone" id="suppphone" pattern="^\d{10}$" oninvalid="setCustomValidity('Contact number must be atleast 10 digits')">
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-7 col-lg-5 ">Contact Phone:</label>
              <div class="col-sm-5 col-lg-7">
                <input type="tel" class="form-control" name="contactphone" id="contactphone" pattern="^\d{10}$" oninvalid="setCustomValidity('Contact number must be atleast 10 digits')">
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group form-row float-right">
              <button type="submit" class="btn btn-primary btn-action" id="addSupply"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</button>&nbsp;
              <button type="submit" class="btn btn-primary btn-action" id="updateSupply"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update</button>&nbsp;
              <button type="submit" class="btn btn-primary btn-action" id="resetSupply"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Reset</button>&nbsp;
              <button type="submit" class="btn btn-primary btn-action" id="pdfSupply"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;Pdf</button>&nbsp;
              <button type="submit" class="btn btn-primary btn-action" id="exportSupply"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Export</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{{-- <div class="col-sm-12">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="form-group form-row">
        <div class="col-sm-4 col-lg-4">
          <div class="form-group form-row">
            <label class="col-sm-3 col-lg-2">Debit:</label>
            <div class="col-sm-9 col-lg-10">
              <input type="text" class="form-control" placeholder="0" name="" value="{{ $total_debit_sum }}"/>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4">
          <div class="form-group form-row">
            <label class="col-sm-3 col-lg-2">Credit:</label>
            <div class="col-sm-9 col-lg-10">
              <input type="text" class="form-control" placeholder="0" name="" value="{{ $total_credit_sum }}"/>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-lg-4">
          <div class="form-group form-row">
            <label class="col-sm-4 col-lg-3">Balance:</label>
            <div class="col-sm-8 col-lg-9">
              <input type="text" class="form-control" placeholder="0" name="" value="{{ $total_credit_sum - $total_debit_sum }}"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> --}}
<div class="col-sm-12">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
        <div class="form-group form-row align-items-center">
            <div class="col-sm-3">
                <input type="text" class="form-control" name="supplier-name" id="supplier-name">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary" id="searchSupplier" onclick="searchSupplier()">Search&nbsp;<i class="fa fa-search"></i></button>
            </div>
        </div>
      <div class="table-responsive table-container" id="supplier-table-container">
        <table class="table table-bordered table-hover table-striped ">
          <thead class="thead-light">
            <tr>
              <th></th>
              <th>Supplier</th>
              <th>Address</th>
              <th>Status</th>
              {{-- <th>Paid</th>
              <th>To Pay</th>
              <th>NET</th> --}}
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="supplierLists">
            @foreach($get_supplier_info as $key=>$supplier_info)
              <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $supplier_info->fldsuppname }}</td>
                <td>{{ $supplier_info->fldsuppaddress }}</td>
                @if($supplier_info->fldactive == "Active")
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-success changeStatus" data-supply="{{ $supplier_info->fldsuppname }}">Active</button></td>
                @else
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger changeStatus" data-supply="{{ $supplier_info->fldsuppname }}">Inactive</button></td>
                @endif
                {{-- <td>{{ $supplier_info->fldactive }}</td> --}}
                {{-- <td>{{ $supplier_info->fldpaiddebit }}</td>
                <td>{{ $supplier_info->fldleftcredit }}</td>
                <td>{{ $supplier_info->fldleftcredit - $supplier_info->fldpaiddebit }}</td> --}}
                <td>
                    <button type="button" class="btn btn-primary editsupply" data-supply="{{ $supplier_info->fldsuppname }}"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                    <button type="button" class="btn btn-primary viewsupply" data-supply="{{ $supplier_info->fldsuppname }}"><i class="fas fa-eye"></i>&nbsp;View</button>
                  {{-- <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Edit {{ $supplier_info->fldsuppname }}" class="editsupply text-primary"><i class="fa fa-edit"></i></a>&nbsp; --}}
                  {{-- <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Delete {{ $supplier_info->fldsuppname }}" class="deletesupply text-danger"><i class="ri-delete-bin-5-fill"></i></a> --}}
                </td>
              </tr>
            @endforeach
            <tr>
                <td colspan="5">{{ $get_supplier_info->links() }}</td>
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
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        searchSupplier(page);
    });

    function searchSupplier(page){
        var url = baseUrl + "/purchase/supplier-info/search-supplier";
        $.ajax({
            url: url+"?page="+page,
            type: "GET",
            data:  {
                'search': $('#supplier-name').val()
            },
            success: function(response) {
                if(response.data.status){
                    $('#supplierLists').html(response.data.html);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

  $(document).on('click','#exportSupply',function(e){
    e.preventDefault();
    $('#supplierInfoForm').attr('action','{{ route("export.supplier.info") }}');
    $('#supplierInfoForm').submit();
  });

  $(document).on('click','#pdfSupply',function(e){
    e.preventDefault();
    $('#supplierInfoForm').attr('action','{{ route("pdf.all.supplier") }}');
    $('#supplierInfoForm').submit();
  });

  $(document).on('click','#addSupply',function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });
    if($('#suppname').val() != ""){
        $.ajax({
            url:"{{ route('insert.supplier.info') }}",
            method:"POST",
            data: new FormData($('#supplierInfoForm')[0]),
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            success:function(data){
                if(data.status){
                $('#supplier-table-container').html(data.html);
                clearFields();
                showAlert(data.message);
                }else{
                    showAlert("Something went wrong!!", 'Error');
                }
            }
        });
    }else{
        alert("Supplier name is required");
    }
  });

  $(document).on('click','.viewsupply',function(){
    var supplyName = $(this).attr('data-supply');
    var urlReport = baseUrl + "/purchase/supplier-info/get-suppliers-detail/" + supplyName;
    window.open(urlReport, '_blank');
  });

  $(document).on('click','.editsupply',function(){
    var supplyName = $(this).attr('data-supply');
    $.ajax({
        url: '{{ route("edit.supplier.info") }}',
        type: 'get',
        dataType: 'json',
        data: {
            'suppname' : supplyName,
        },
        success: function(res) {
          if(!res.status){
            alert(res.errormessage);
          } else if(res.status == true) {
            $('#suppname').val(res.supplierInfo.fldsuppname);
            $('#suppaddress').val(res.supplierInfo.fldsuppaddress);
            $('#paymentmode').val(res.supplierInfo.fldpaymentmode).change();
            $('#active').val(res.supplierInfo.fldactive).change();
            if(res.supplierInfo.fldstartdate != null){
              $('#startdate').val(res.supplierInfo.fldstartdate.substring(0,10));
            }
            $('#creditday').val(res.supplierInfo.fldcreditday);
            $('#contactname').val(res.supplierInfo.fldcontactname);
            $('#suppphone').val(res.supplierInfo.fldsuppphone);
            $('#contactphone').val(res.supplierInfo.fldcontactphone);
            $('#pan_no').val(res.supplierInfo.fldpanno);
            $('#vat_no').val(res.supplierInfo.fldvatno);
          }
        }
    });
  });

  $(document).on('click','#updateSupply',function(e){
    e.preventDefault();
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{csrf_token()}}'
      }
    });
    if($('#suppname').val() != ""){
        $.ajax({
            url:"{{ route('update.supplier.info') }}",
            method:"POST",
            data: new FormData($('#supplierInfoForm')[0]),
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            success:function(data){
                if(data.status){
                    $('#supplier-table-container').html(data.html);
                    clearFields();
                    showAlert(data.message);
                }else{
                    showAlert("Something went wrong!!", 'Error');
                }
            }
        });
    }else{
        alert("Supplier name is required!");
    }
  });

  $(document).on('click','#resetSupply',function(e){
    e.preventDefault();
    clearFields();
  });

  function clearFields(){
    $('#supplierInfoForm').find("input[type=text], input[type=number], input[type=tel], input[type=date]").val("");
    $('#supplierInfoForm').find("select").prop('selectedIndex',0).change();
  }

  $(document).on('click','.deletesupply',function(){
    if(!confirm("Delete?")){
      return false;
    }
    var supplyName = $(this).attr('data-supply');
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{csrf_token()}}'
      }
    });
    $.ajax({
      url: '{{ route("delete.supplier.info") }}',
      type: 'POST',
      dataType: 'json',
      data: {
        'suppname' : supplyName,
      },
      success: function(res) {
        if(!res.status){
          alert(res.errormessage);
        } else if(res.status == true) {
          $('#supplier-table-container').html(res.html);
          showAlert(res.message);
        }
      }
    });
  });

  $(document).on('click','.changeStatus',function(){
    var supplier = $(this).attr('data-supply');
    var current = $(this);
    if($(this).html() == 'Active'){
        var fldstatus = "Inactive";
        var updateText = "Inactive";
        var updateClass = "btn-outline-danger";
    }else{
        var fldstatus = "Active";
        var updateText = "Active";
        var updateClass = "btn-outline-success";
    }
    $.ajax({
        type : 'get',
        url  : '{{ route("change.supplier.status") }}',
        dataType : 'json',
        data : {
            '_token': '{{ csrf_token() }}',
            'fldactive': fldstatus,
            'supplier': supplier
        },
        success: function (res) {
            if(res.status){
                current.html(updateText);
                if(updateClass == "btn-outline-danger"){
                    current.removeClass('btn-outline-success');
                    current.addClass('btn-outline-danger');
                }else{
                    current.removeClass('btn-outline-danger');
                    current.addClass('btn-outline-success');
                }
            }
        }
    });
  });

</script>
@endpush
