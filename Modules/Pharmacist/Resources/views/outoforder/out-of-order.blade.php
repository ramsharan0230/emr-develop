@extends('frontend.layouts.master')
@push('after-styles')
@endpush
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Out Of Order
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="row">
            <div class="col-lg-3 col-md-4">
              <div class="form-group form-row align-items-center er-input">
                <label for="" class="col-sm-5">Department:</label>
               <div class="col-sm-7">
                  @php $allcomps = \App\Utils\Pharmacisthelpers::getAllComp(); @endphp
                  <select class="form-control" name="fldcomp" id="fldcomp">
                    <option value="" selected=selected>--Select--</option>
                    @forelse($allcomps as $allcomp)
                    <option value="{{ $allcomp->fldcomp }}">{{ $allcomp->name }}</option>
                    @empty
                    @endforelse
                  </select>
               </div>
              </div>
              <div class="form-group form-row align-items-center er-input">
                <label for="" class="col-sm-3 col-lg-5">Batch:</label>
                <div class="col-sm-7">
                  <input type="text" name="fldbatch" id="fldbatch" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4">
              <div class="form-group form-row align-items-center er-input">
                <label for="" class="col-sm-3">Route:</label>
                <div class="col-sm-9">
                   <select name="fldroute" id="fldroute" class="form-control">
                    <option value="">--Select--</option>
                    <option value="Medicines">Medicines</option>
                    <option value="Surgicals">Surgicals</option>
                    <option value="Extra Items">Extra Items</option>
                    {{-- <option value="oral">oral</option>
                    <option value="liquid">liquid</option>
                    <option value="fluid">fluid</option>
                    <option value="injection">injection</option>
                    <option value="resp">resp</option>
                    <option value="topical">topical</option>
                    <option value="eye/ear">eye/ear</option>
                    <option value="anal/vaginal">anal/vaginal</option>
                    <option value="suture">suture</option>
                    <option value="msurg">msurg</option>
                    <option value="ortho">ortho</option>
                    <option value="extra">extra</option> --}}
                  </select>
                </div>
              </div>
              <div class="form-group form-row align-items-center er-input">
                <label for="" class="col-sm-3">Expiry:</label>
                <div class="col-sm-9">
                  <!--  <input type="date" class="form-control" id="exampleInputdate" value="2019-12-18" /> -->
                  <input type="date" class="form-control" id="fldexpiry" value="">
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-4">
              <div class="form-group form-row align-items-center">
                <label for="" class="col-sm-2">Particulars:</label>
                <div class="col-sm-7">
                  <select name="select2medicinelist" id="select2medicinelist" class="select2medicinelist form-control">
                      <option value="">--Select--</option>
                  </select>
                </div>
                <div class="col-sm-3">
                  <a href="javascript:void(0)" class="btn btn-primary btn-action" type="button" id="loadmedicinegrouping"> <i class="fa fa-sync"></i></a>&nbsp;
                  <a href="javascript:void(0)" class="btn btn-primary btn-action" type="button" id="clear">Clear</a>
                </div>
              </div>
              <div class="form-group form-row align-items-center">
                <label>Order:</label>
                <div class="col-sm-2">
                  <input type="number" min="0" name="fldstatus" id="fldstatus" value="" placeholder="0" class="form-control">
                </div>
                <label>Rate:</label>
                <div class="col-sm-2">
                  <!-- <input type="number" class="form-control" placeholder="0" /> -->
                  <input type="number" min="0" step="any" name="fldsellpr" id="fldsellpr" value="" placeholder="Rs 0" class="form-control">
                </div>
                <div class="col-sm-10 col-lg-6">
                  <input type="hidden" name="fldstockno" id="fldstockno" value="">
                  <a href="javascript:void(0)" class="btn btn-primary btn-action" type="button" id="showallbutton"> <i class="fa fa-list"></i>&nbsp;Show All</a>&nbsp;
                  <a href="javascript:void(0)" class="btn btn-primary btn-action" type="button" id="uploadbutton"><i class="fa fa-check"></i>&nbsp;Update</a>
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
          <div id="table" class="table-responsive table-container">
            <table class="table table-bordered table-striped text-center " id="entrytable">
            </table>
            <div id="bottom_anchor"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .entryrow:hover {
    background-color: #88b9ed;
  }
</style>
<script>
  $(function() {

    function select2loading() {
      setTimeout(function() {

        $('.select2medicinelist').select2({});
      }, 3000);
    }
    select2loading();

    $('#fldroute').change(function() {
      var fldroute = $(this).val();
      var comp = $('#fldcomp').val();
      getMedicineslist(comp, fldroute);
    });

    $('#fldcomp').change(function() {
      var fldroute = $('#fldroute').val();
      var comp = $('#fldcomp').val();
      getMedicineslist(comp, fldroute);
    });

    function getMedicineslist(comp, fldroute) {
      $.ajax({
        type: 'post',
        url: '{{ route("pharmacist.outoforder.getmedicinesfromfldroute")}}',
        dataType: 'json',
        data: {
          '_token': '{{ csrf_token() }}',
          'fldroute': fldroute,
          'comp': comp
        },
        success: function(res) {
          if (res.message == 'error') {
            showAlert(res.messagedetail);
          } else if (res.message == 'success') {
            $('.select2medicinelist').html(res.html);

            select2loading();
          }
        }
      });
    }

    $('#loadmedicinegrouping').click(function() {
      var fldstockid = $('.select2medicinelist').val();
      var fldcomp = $('#fldcomp').val();
      $.ajax({
        type: 'post',
        url: '{{route("pharmacist.outoforder.loadentries")}}',
        dataType: 'json',
        data: {
          '_token': '{{ csrf_token() }}',
          'fldstockid': fldstockid,
          'fldcomp': fldcomp
        },
        success: function(res) {
          if (res.message == 'error') {
            showAlert(res.errormessage);
          } else if (res.message == 'success') {
            $('#fldbatch').val("");
            $('#fldexpiry').val("");
            $('#fldstatus').val("");
            $('#fldsellpr').val("");
            $('#fldstockno').val("");
            $('#entrytable').html(res.html);
          }
        }
      });
    });

    $('#showallbutton').click(function() {
      var fldcomp = $('#fldcomp').val();
      if(fldcomp == ""){
        showAlert("Please select department",'error');
        return false;
      }
      var fldstockid = $('.select2medicinelist').val();
      if(fldstockid == ""){
        showAlert("Please select particulars",'error');
        return false;
      }
      var showall = 'showall';
      $.ajax({
        type: 'post',
        url: '{{ route("pharmacist.outoforder.loadentries")}}',
        dataType: 'json',
        data: {
          '_token': '{{ csrf_token() }}',
          'fldstockid': fldstockid,
          'fldcomp': fldcomp,
          'show': showall
        },
        success: function(res) {
          if (res.message == 'error') {
            showAlert(res.errormessage);
          } else if (res.message == 'success') {
            $('#entrytable').html(res.html);
          }
        }
      });
    });

    $('#entrytable').on('click', '.entryrow', function() {
      var fldstockno = $(this).data('fldstockno');

      $.ajax({
        type: 'post',
        url: '{{ route("pharmacist.outoforder.populateentryforupdate")}}',
        dataType: 'json',
        data: {
          '_token': '{{ csrf_token() }}',
          'fldstockno': fldstockno,
        },
        success: function(res) {
          if (res.message == 'error') {
            showAlert(res.errormessage);
          } else if (res.message == 'success') {
            $('#fldbatch').val(res.fldbatch);
            $('#fldexpiry').val(res.fldexpiry);
            $('#fldstatus').val(res.fldstatus);
            $('#fldsellpr').val(res.fldsellpr);
            $('#fldstockno').val(res.fldstockno);
          }
        }
      });
    });

    $('#uploadbutton').click(function() {
      var fldstockno = $("#fldstockno").val();
      var fldbatch = $("#fldbatch").val();
      var fldexpiry = $("#fldexpiry").val();
      var fldstatus = $("#fldstatus").val();
      var fldsellpr = $("#fldsellpr").val();


      if (fldstockno == '') {
        alert('please select the item to be updated');
        return false;
      }

      if (fldbatch == '') {
        alert('Batch is required');
        return false;
      }

      if (fldexpiry == '') {
        alert('Expiry Date is required');
        return false;

      }

      if (fldstatus == '') {
        alert('Order is required');
        return false;
      }

      if (fldsellpr == '') {
        alert('Rate is required');
        return false;
      }

      $.ajax({
        type: 'post',
        url: '{{ route("pharmacist.outoforder.updateentry")}}',
        dataType: 'json',
        data: {
          '_token': '{{ csrf_token() }}',
          'fldstockno': fldstockno,
          'fldbatch': fldbatch,
          'fldexpiry': fldexpiry,
          'fldstatus': fldstatus,
          'fldsellpr': fldsellpr

        },
        success: function(res) {
          if (res.message == 'error') {
            showAlert(res.errormessage);
          } else if (res.message == 'success') {
            $('#loadmedicinegrouping').click();
          }
        }
      });
    });

    $(document).on('click','#clear',function(){
        $('#fldcomp').val("");
        $('#fldbatch').val("");
        $('#fldroute').val("");
        $('#fldexpiry').val("");
        $('#fldstatus').val("");
        $('#fldsellpr').val("");
        $('#select2medicinelist').empty().append("<option value=''>--Select--</option>");
    });

    $(document).on('change','#select2medicinelist',function(){
        $('#fldbatch').val("");
        $('#fldexpiry').val("");
        $('#fldstatus').val("");
        $('#fldsellpr').val("");
        $('#fldstockno').val("");
    });

  });
</script>
@stop
