@extends('frontend.layouts.master')
@section('content')
<style type="text/css">
    .highlight { background-color: red; }
</style>
<div class="container-fluid">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-12">

            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Insurance List</h4>
                    </div>
                </div>

                <div class="iq-card-body">

                    <form action="javascript:;" id="insurance-form" method="post">
                        @csrf

                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Insurance Type</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input name="insurance" id="insurance" type="text" list="insurances" class="form-control" />
                                                <datalist id="insurances">
                                                 @if(isset($insurance_list) && count($insurance_list) > 0)
                                                 @foreach($insurance_list as $i)
                                                 <option value="{{$i->insurancetype}}">{{$i->insurancetype}}</option>
                                                 @endforeach
                                                 @endif
                                             </datalist>
                                         </div>

                                     </div>

                                 </div>
                                 <div class="form-group form-row">
                                    <label class="col-sm-4">Nepali Government</label>
                                    <div class="col-sm-6">
                                        <div class="radio d-inline-block mr-2">
                                          <input type="radio" name="is_nep_govt" id="is_nep_govt" value="1" checked="">
                                          <label >Yes</label>
                                      </div>
                                      <div class="radio d-inline-block mr-2">
                                          <input type="radio" name="is_nep_govt" id="is_nep_govt" value="0">
                                          <label >No</label>
                                      </div>
                                      <!-- <div class="input-group">

                                        <input type="radio" name="is_nep_govt" id="is_nep_govt" value="1" checked=""><label>Yes</label>
                                        <input type="radio" name="is_nep_govt" id="is_nep_govt" value="0">No
                                    </div> -->
                                </div>
                            </div>
                         
                         <div class="form-group form-row">
                                <label class="col-sm-3">Claim Code</label>
                                <div class="col-sm-5 claim-code">
                                    <div class="input-group ">
                                        <input name="claim_code_from" id="claim_code_from" type="number"  class="form-control" />

                                    {{-- </div> --}}
                                    <label class="col-sm-3 m-1">To</label>
                                    {{-- <div class="input-group "> --}}
                                        <input name="claim_code_to" id="claim_code_to" type="number"  class="form-control" />

                                    </div>

                                </div>
                                {{-- <div class="col-sm-5">
                                    <div class="input-group">
                                     <button type="button" class="btn btn-primary" onclick="addClaim()">Add More Claim Code</button>

                                 </div>
                             </div> --}}

                             <div class="col-sm-5">
                                <div class="input-group">
                                 <button type="button" class="btn btn-primary" onclick='addInsurance()'>Add</button>

                             </div>
                         </div>
                         
                         {{-- <div class="form-group form-row">
                             <div class="col-sm-5">
                                <div class="input-group">
                                 <button type="button" class="btn btn-primary" onclick='addInsurance()'>Add</button>

                             </div>
                         </div>
                     </div> --}}
                     </div>
                 </div>
             </div>
         </div>
     </form>
     <div class="table-reponsive">
        <table class="table table-striped table-hover table-bordered sortable">
            <thead class="thead-light">
                <tr>
                    <th>S.No.</th>
                    <th>Claim Code</th>
                    <th>Insurance Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="insurance-table-list">
                @forelse($claims as $c)
                <tr class="clickable-row">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$c->claim_code}}</td>
                    <td>{{ isset($c->insurancetype) ? $c->insurancetype->insurancetype : ''}}</td>
                    @if($c->has_used == 1)
                        <td><span class="badge badge-warning">used</span></td>
                    @else
                        <td><span data-id="{{ $c->id }}" class="changeStatus badge @if($c->fldstatus == "active") badge-success @else badge-danger @endif">{{ $c->fldstatus }}</span></td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <em>No data available in table ...</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">{{ $claims->links() }}</td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>

</div>
</div>
</div>
</div>
@endsection

@push('after-script')
<script>

    function addClaim(){
        markupclaim = '<div class="input-group"><input name="claim_code[]" type="text"  class="form-control claim_code" /><span id="reference_remove_current" class="close" >&nbsp; [X] </span></div>';
        tablerefBody = $(".claim-code");
        tablerefBody.append(markupclaim);
    }
    $(document).on('click','.close', function(e){
        var whichtr = $(this).closest("div");
        whichtr.remove();
    });
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
    });
    $(".clickable-row").click(function() {
        var ethnic = $(this).attr("data-ethnic");
        var fldid = $(this).attr('data-fldid');
        // alert(fldid);
        // var selected = $(this).hasClass("highlight");
        // $(".ethnic-table-list tr").removeClass("highlight");
        // if(!selected)
        //     $(this).addClass("highlight");

        $('#ethnic').val(ethnic);
        $('#updatevalue').val(fldid);
    });

    $('#claim_code_from,#claim_code_to').keydown(function (e) {
    if (!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8 || e.keyCode == 9))
        return false;
    });

    function addInsurance(){
        
        $.ajax({
            url: '{{ route('store-insurance') }}',
            type: "POST",
            data: $("#insurance-form").serialize(),
            success: function (response) {
                    // console.log(response);
                    if (response.success.status) {
                        $(".insurance-table-list").empty().append(response.success.claimhtml);
                        //$(".insurance-table-list").html(response.success.claimhtml);
                        $("#insurance").empty().append(response.success.insurancehtml);
                        $("#claim_code_from").val("");
                        $("#claim_code_to").val("");
                        //$('.claim_code').each(function(i, obj) {
                           // $(this).closest("div").remove();
                        //});
                        showAlert('Successfully data inserted.')

                    } else {
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("{{ __('messages.error') }}", 'error')
                }
            });
    }

    $('.insurance-table-list').on('click','.changeStatus',function() {
        var claim_id = $(this).attr('data-id');
        var current_element = $(this);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route("change-insurance-status") }}',
            data: {'claim_id': claim_id},
            success: function(data){
                current_element.closest("td").html(data.status);
            }
        });
    })
</script>

@endpush
