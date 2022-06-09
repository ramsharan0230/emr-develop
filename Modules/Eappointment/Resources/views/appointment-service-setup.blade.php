@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        {{--@include('frontend.common.alert_message')--}}
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Service Setup</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{ route('eappointment-add-service-setup') }}" method="POST" class="row">
                                @csrf
                                <input type="hidden" name="service_id" id="service_id"/>

                                <!-- <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Service</label>
                                    <div class="">
                                        <select class="form-control select2" id="service_id"  name="service_id" required>
                                            <option value="" disabled selected>--Select Service--</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->value }}" data-value="{{$service->label}}">{{ $service->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="service_name" id="service_name"/>
                                </div> -->
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">New Service Name</label>
                                    <div >
                                    <input name="new_service_name" id="new_service_name" type="text"
                                                        class="form-control"  
                                                         />
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">New Service code</label>
                                    <div >
                                    <input name="new_service_code" id="new_service_code" type="text"
                                                        class="form-control"  
                                                         />
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Service Types</label>
                                    <div class="">
                                        <select class="form-control select2" id="service_type"  name="service_type" required>
                                            <option value="" disabled selected>--Select Service Types--</option>
                                            @foreach ($service_types as $service)
                                                <option value="{{ $service->value }}" data-value="{{$service->label}}">{{ $service->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="service_name" id="service_name"/>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">New Registration Charge</label>
                                    <div >
                                    <input name="new_registration_charge" id="new_registration_charge" type="number"
                                                        class="form-control"  
                                                         />
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Follow Up Charge</label>
                                    <div >
                                    <input name="follow_up_charge" id="follow_up_charge" type="number"
                                                        class="form-control"  
                                                         />
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Registered Patient Charge</label>
                                    <div >
                                    <input name="registered_patient_charge" id="registered_patient_charge" type="number"
                                                        class="form-control"  
                                                         />
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Item Names</label>
                                    <div class="">
                                        <select id="item_id" class="form-control select2" name="item_id" required>
                                        <option value="" disabled selected>--Select Item Names--</option>
                                        @foreach ($item_names as $item)
                                                <option value="{{ $item->fldid }}">{{ $item->flditemname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Status</label>
                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="service-active" value="1" name="service_status" checked="checked" class="custom-control-input">
                                                        <label class="custom-control-label" for="service-active"> Active</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="service-inactive" value="0" name="service_status"    class="custom-control-input">
                                                        <label class="custom-control-label" for="service-inactive">In Active</label>
                                                    </div>
                                </div>
                              
                                <div class="col-3 mt-4">
                                    <button type="submit" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    <button type="button"  onclick="updateService()" class="btn btn-success">update </button>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-sm-12">
                            <div class="table-responsive table-container">
                               

                                <div class="res-table table-sticky-th">
                                    <table id="service-table"
                                    data-search="true"
                                    data-pagination="true"
                                    data-show-toggle="true"
                                    data-resizable="true"
                                    data-search-align="left"
                                    data-show-columns="true"
                                    >
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SN</th>
                                            <th>Service Name</th>
                                            <th>Item Name</th>
                                            <th>New Registration Charge</th>
                                            <th>Follow up Charge</th>
                                            <th>Registered Charge</th>

                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="js-user-share-item-tbody">
                                        @foreach($appointment_charges as $charge)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $charge->eapp_service_name }}</td>
                                                <td>{{ \App\ServiceCost::where('fldid',$charge->tblservicecost_id)->first()->flditemname }}</td>
                                                <td> {{ $charge->new_registration_charge}}</td>
                                                <td>{{ $charge->followup_charge}}</td>
                                                <td>{{ $charge->registered_patient_charge}}</td>
                                                <td> <a href="javascript:void(0)" class="btn btn-success btn-sm"  onclick="editService({{$charge->id}})">
                                <i class="fas fa-pen"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm"  onclick="deleteService({{$charge->id}})">
                                <i class="fas fa-trash"></i></a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


  <script>
      $(function() {
        $('#service-table').bootstrapTable();
    })

    $('#service_id').on('change', function(){
        $('#service_name').val($(this).find(':selected').data('value'));
});
function deleteService(id){
    Swal.fire({  
        title: 'Do you want to delete?',  
        showDenyButton: true,  
        confirmButtonText: `Yes`,  
        denyButtonText: `No`,
        }).then((result) => { 
            if (result.isConfirmed) {  
                $.ajax({
                url: '{{ route('eappointment-delete-service-setup') }}',
                type: "POST",
                data: {id:id},
                success: function (data) {
                   if(data){
                       location.reload(true);
                   }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


            } else if (result.isDenied) {    
                Swal.fire('Changes are not saved', '', 'info')  
            }
        });
    
}
function updateService(){
                  var service_name = $('#new_service_name').val();
                  var service_id =  $('#service_id').val();
                  var new_service_code = $('#new_service_code').val();
                  var follow_up_charge = $('#follow_up_charge').val();
                  var new_registration_charge = $('#new_registration_charge').val();
                  var service_type =  $('#service_type').val();
                  var registered_patient_charge = $('#registered_patient_charge').val();
                  var item_id = $('#item_id').val();
                  var service_status = $("input[name='service_status']:checked").val();;
    $.ajax({
                url: '{{ route('eappointment-update-service-setup') }}',
                type: "POST",
                data: {
                    id:service_id,
                    new_service_code:new_service_code,
                    new_service_name:service_name,
                    follow_up_charge:follow_up_charge,
                    new_registration_charge:new_registration_charge,
                    service_type:service_type,
                    registered_patient_charge:registered_patient_charge,
                    item_id:item_id,
                    service_status:service_status,
                },
                success: function (data) {
                   if(data){
                       location.reload(true);
                   }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
}
function editService(id){
    $.ajax({
                url: '{{ route('eappointment-edit-service-setup') }}',
                type: "POST",
                data: {id:id},
                success: function (data) {
                   console.log(data);
                   $('#new_service_name').val(data.eapp_service_name);
                   $('#service_id').val(data.id);
                   $('#new_service_code').val(data.code);
                   $('#follow_up_charge').val(data.followup_charge);
                   $('#new_registration_charge').val(data.new_registration_charge);
                   $('#service_type').val(data.service_type).trigger('change');
                   $('#registered_patient_charge').val(data.registered_patient_charge);
                   $('#item_id').val(data.tblservicecost_id).trigger('change');
                   console.log('service_status',data.service_status);
                   if(data.service_status == 'Y'){
                    $('#service-active').prop('checked',true);
                    $('#service-inactive').prop('checked',false);
                   }else{
                    $('#service-inactive').prop('checked',true);
                    $('#service-active').prop('checked',false);
                   }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
}

  </script>
@endsection

