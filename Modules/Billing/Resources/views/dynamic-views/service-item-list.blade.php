<style type="text/css">
    td .badge-success{
         background:#ffffff ;
         border: 1px solid #237e1f;
         color: #237e1f;
     }
 
 </style>
 
 <table class="table table-striped table-bordered table-hover table-serviceitem">
     <thead class="thead-light">
     <tr>
         <th>S.N.</th>
         <th >Items</th>
         <th class="text-center">Qty</th>
         <th class="text-center">Rate</th>
         <th class="text-center">Dis%</th>
         <th class="text-center">Tax%</th>
         <th class="text-center">Total Amount</th>
         <th class="text-center">Share</th>
         <th class="text-center">TP</th>
         <th class="text-center">Action</th>
     </tr>
     </thead>
     
     <tbody id="billing-body">
         @php
             $totaldiscountamount = 0;
         @endphp
     @if ($serviceData)
         @php
             $packagedata = array();
             $countItems = 1;
         @endphp
         @forelse ($serviceData as $service)
            @if(!is_null($service->package_name))
                @php
                    $packagedata[$service->package_name][] = $service;
                @endphp
            @endif
             @if (!empty($service->service_cost()->category) and is_null($service->package_name))
                 
                 <tr>
                     <td>{{$countItems++}}</td>
                     <td>{{ $service->serviceCost->fldbillitem }}</td>
                     <td class="text-center">
                         <input type="text" name="quantity[]" class="quantity-change form-control" fldid="{{$service->fldid}}" value="{{$service->flditemqty}}" style="width: 80%;">
                     </td>
                     <td class="text-center">
                         <input type="text" name="rate[]" class="rate-change form-control" fldid="{{$service->fldid}}" value="{{$service->flditemrate}}" style="width: 80%;" @if(\Helpers::getServiceCostRateFlag($service->flditemname) == '0' ) readonly @endif>
                     </td>
                     
                     @if($service->fldtempbillno =='')
                         
                         @if($service->serviceCost->discount == '0' or !is_null($service->noDiscount) or is_string($service->package_name))
                             @php
                                 $class = '';
                                 $disable = 'readonly';
                                 
                             @endphp
                         @else
                             @php
                                 $totaldiscountamount +=$service->flditemrate*$service->flditemqty;
                                 $class = 'discount-change';
                                 $disable = '';
                             @endphp
                         @endif
 
                     @else
                         @php
                             $class = '';
                             $disable = 'readonly';
                         @endphp
                     @endif
                     <td class="text-center">
                         <input type="text" name="dis[]" class="{{$class}} form-control" fldid="{{$service->fldid}}" value="{{$service->flddiscper}}" style="width: 80%;" {{$disable}}>
                     </td>
                     <td class="text-center">
                         <input type="text" name="tax[]" class="form-control" value="{{ $service->fldtaxper }}" style="width: 80%;" readonly>
                     </td>
                     <td class="text-center">{{number_format((float)($service->fldditemamt), 2, '.', '')}}</td>
                     <td class="text-center">
                         @php
                             $type = $service->service_cost()->category;
                         @endphp
                         @if(in_array('OPD Consultation',$type))
                             @php
                                 $class = 'department-doctor-share';
                             @endphp
                         @else
                             @php
                                 $class = 'doctor-share';
                             @endphp
                         @endif
                         @php
                         $shareablesetup = Options::get('shareable_setup');
                         @endphp
                         @if($shareablesetup == 'both' || $shareablesetup == 'grid')
                             <a title="Doctor Share" href="javascript:;" data-user-ids="{{ $service->pat_billing_shares()->select('type', 'user_id', 'ot_group_sub_category_id')->get() }}" data-type="{{ json_encode($service->service_cost()->category) }}" class="{{$class}}" data-id="{{ $service->fldid }}" data-itemname="{{ $service->flditemname }}">
                                 @if (count($service->service_cost()->category) == 1 && in_array('referable', $service->service_cost()->category))
                                 
                                 --
                                 @else
                                     @php
                                         $doctorlist = \App\Utils\Helpers::getPayableDoctorName($service->fldid);
                                     @endphp
                                     <span id="payable-doctors">{{$doctorlist}}</span><br/>
                                     <input type="hidden" name="share_check[]" value="{{ $service->fldid }}">
                                 <i class='fas fa-share-alt text-default'></i>
                                 <div class="share_check"></div>
                                 
                                     
                                         
                                     
                                 @endif
                             </a>
                         @endif
                     </td>
                     <td class="text-center">{{ $service->fldtempbillno }} </td>
                     <td class="text-center">
                         @if($service->fldsample == 'Sampled')
                             <span class="badge badge-success">Sampled</span>
                         @else
                             <a href="javascript:;" class="delete-billing-row"  rel="{{ $service->fldid }}">
                                 <i class='fa fa-trash text-danger'></i>
                             </a>
                         @endif
                     </td>
                 </tr>
 
             @endif
         @empty
         @endforelse
 
         @forelse ($serviceData as $service)
         
             @if (empty($service->service_cost()->category) and is_null($service->package_name))
                 <tr>
                     <td>{{$countItems++}}</td>
                     <td>{{ $service->serviceCost->fldbillitem }}</td>
                     <td class="text-center">
                         <input type="text" name="quantity[]" class="quantity-change form-control" fldid="{{$service->fldid}}" value="{{$service->flditemqty}}" style="width: 80%;">
                     </td>
                     <td class="text-center">
                         <input type="text" name="rate[]" class="rate-change form-control" fldid="{{$service->fldid}}" value="{{$service->flditemrate}}" style="width: 80%;">
                     </td>
                     
                     @if($service->fldtempbillno =='')
                         @if($service->serviceCost->discount == '0' or !is_null($service->noDiscount) or is_string($service->package_name))
                             @php
                                 $class = '';
                                 $disable = 'readonly';
                                 
                             @endphp
                         @else
                             @php
                                 $totaldiscountamount +=$service->flditemrate*$service->flditemqty;
                                 $class = 'discount-change';
                                 $disable = '';
                             @endphp
                         @endif
                     @else
                         @php
                             $class = '';
                             $disable = 'readonly';
                         @endphp
                     @endif
                     <td class="text-center">
                         <input type="text" name="dis[]" class="{{$class}} form-control" fldid="{{$service->fldid }}" value="{{$service->flddiscper }}" style="width: 80%;" {{$disable}}>
                     </td>
                     <td class="text-center">
                         <input type="text" name="tax[]" value="{{ $service->fldtaxper }}" class="form-control" style="width: 80%;" readonly>
                     </td>
                     <td class="text-center">{{number_format((float)($service->fldditemamt), 2, '.', '')}}</td>
 
                     <td class="text-center">
                         --
                     </td>
                     <td class="text-center">{{ $service->fldtempbillno }} </td>
                     <td class="text-center">
                         @if($service->fldsample == 'Sampled')
                             <span class="badge badge-success">Sampled</span>
                         @else
                             <a href="javascript:;" class="delete-billing-row"  rel="{{ $service->fldid }}">
                                 <i class='fa fa-trash text-danger'></i>
                             </a>
                         @endif
                         
                     </td>
                 </tr>
             @endif
         @empty
             <tr>
                 <td colspan="10">No Items Added</td>
             </tr>
         @endforelse

         @if(isset($packagedata) and !empty($packagedata))
                
            
            @foreach($packagedata as $key=>$pdata)
                @php
                    $totalsum = 0;
                    $itemdata = array();
                @endphp
                 @foreach($pdata as $ndata)
                    @php
                    $totalsum +=$ndata->fldditemamt;
                    $itemdata[] = $ndata->flditemname;
                    @endphp
                    
                 @endforeach
                    @php
                        $packageeditabledata = \App\ServiceGroup::where('fldgroup',$key)->whereIn('flditemname',$itemdata)->where('price_editable','1')->get();
                    @endphp
                <tr>
                    <td>{{$countItems++}}</td>
                    <td><a href="javascript:void(0)" class="health_package badge badge-primary" data-package="{{$key}}">{{ $key }}</a></td>
                    <td class="text-center">
                        <input type="text" name="quantity[]" class=" form-control" value="1" style="width: 80%;">
                    </td>
                    <td class="text-center">
                        
                    </td>
                    
                    <td class="text-center">
                        
                    </td>
                    <td class="text-center">
                        
                    </td>
                    <td class="text-center">{{number_format((float)($totalsum), 2, '.', '')}}</td>

                    <td class="text-center">
                        --
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center">
                        @if(count($pdata) == count($packageeditabledata))
                            <a href="javascript:;" class="delete-billing-row"  rel="{{ $ndata->fldid }}">
                                <i class='fa fa-trash text-danger'></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
     @endif
     <input type="hidden" name="allowed_discount_amount" id="allowed_discount_amount" value="{{$totaldiscountamount}}">
     </tbody>
     <thead class="thead-light d-none">
     <tr>
         <th>&nbsp;</th>
         <th>Total</th>
         <th colspan="2" class="text-right"></th>
         <th colspan="2" class="text-right"></th>
         <th class="text-right table-bill-total">{{number_format((float)(number_format((float)($subtotal), 2, '.', '') - number_format((float)($discount), 2, '.', '') + number_format((float)($tax), 2, '.', '')), 2, '.', '')}}</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
     </tr>
     </thead>
 </table>
 
 <script>
     
 
     $(document).ready(function () {
        $(".delete-billing-row").click(function () {
         var confirmDelete = confirm('Delete?');
         if (confirmDelete == false) {
             return false;
         }
         $.ajax({
             url: "{{ route('billing.delete.items.by.service') }}",
             type: "POST",
             data: {
                 fldid: $(this).attr('rel'),
                 temp_checked: 'no'
             },
             success: function (data) {
                 $("#billing-body").empty().append(data.message.tableData);
                 $("#billing-tp-body").empty().append(data.message.tptableData);
                 $("#sub-total-data").empty().val((parseFloat(data.message.total) + parseFloat(data.message.discount)));
                 $("#discount-amount").val(parseFloat(data.message.discount));
                 $("#table-bill-total").empty().val(parseFloat(data.message.total));
                 $("#tax-total-data").empty().val(parseFloat(data.message.tax));
                 $("#grand-total-data").empty().val(parseFloat(data.message.total));
                 $('.depAmount').text(data.message.depositAmount);
                 $('.tpAmount').text(data.message.tpAmount);
                 $('.remainingAmount').text(data.message.remainingAmount);
                 let encounter_for_received_amount = $("#encounter_id").val();
 
                 var paymode = $("input[name='payment_mode']:checked").val();
                
                 if (paymode == 'Credit' || paymode == undefined) {
                     $("#received-amount").val(0);
                 }else{
                     // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                 //     $("#received-amount").val(0);
                 // } else {
                     $("#received-amount").val(Number(data.message.total).toFixed(2));
                 // }
                 }
 
                 // $("#discount-scheme-change").prop('disabled', true);
                 showAlert('Delete successfully.');
                 if(data.message.package_name === '1'){
                     window.location.href = window.location.href;
                 }
             }
         });
     });

         $(".rate-change").blur(function () {
             fldid = $(this).attr('fldid');
             new_rate = $(this).val();
             if ($(this).val() < 0) {
                 showAlert('Number must be greater than 0.', 'error');
                 return false;
             }
             $.ajax({
                 url: "{{ route('billing.change.rate.service') }}",
                 type: "POST",
                 data: {
                     fldid: fldid,
                     new_rate: new_rate,
                     temp_checked: 'no'
                 },
                 success: function (data) {
                     $("#billing-body").empty().append(data.message.tableData);
                     $("#billing-tp-body").empty().append(data.message.tptableData);
                     $("#sub-total-data").empty().val((parseFloat(data.message.total) + parseFloat(data.message.discount)));
                     $("#discount-amount").val(parseFloat(data.message.discount));
                     $("#table-bill-total").empty().val(parseFloat(data.message.total));
                     $("#tax-total-data").empty().val(parseFloat(data.message.tax));
                     $("#grand-total-data").empty().val(parseFloat(data.message.total));
                     $('.depAmount').text(data.message.depositAmount);
                     $('.tpAmount').text(data.message.tpAmount);
                     $('.remainingAmount').text(data.message.remainingAmount);
                     let encounter_for_received_amount = $("#encounter_id").val();
 
                     var paymode = $("input[name='payment_mode']:checked").val();
                
                     if (paymode == 'Credit' || paymode == undefined) {
                         $("#received-amount").val(0);
                     }else{
                         // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                     //     $("#received-amount").val(0);
                     // } else {
                         $("#received-amount").val(Number(data.message.total).toFixed(2));
                     // }
                     }
 
                     // $("#discount-scheme-change").prop('disabled', true);
                     showAlert('Quantity change successfully.');
                     
                     
                 }
             });
         });
         $(".quantity-change").blur(function () {
             fldid = $(this).attr('fldid');
             new_quantity = $(this).val();
             if ($(this).val() <= 0) {
                 showAlert('Number must be greater than 0. Please change it to proper number.', 'error');
                 // $(this).val(1);
                 return false;
             }
             $.ajax({
                 url: "{{ route('billing.change.quantity.service') }}",
                 type: "POST",
                 data: {
                     fldid: fldid,
                     new_quantity: new_quantity,
                     temp_checked: 'no'
                 },
                 success: function (data) {
                     $("#billing-body").empty().append(data.message.tableData);
                     $("#billing-tp-body").empty().append(data.message.tptableData);
                     $("#sub-total-data").empty().val((parseFloat(data.message.total) + parseFloat(data.message.discount)));
                     $("#discount-amount").val(parseFloat(data.message.discount));
                     $("#table-bill-total").empty().val(parseFloat(data.message.total));
                     $("#tax-total-data").empty().val(parseFloat(data.message.tax));
                     $("#grand-total-data").empty().val(parseFloat(data.message.total));
                     $('.depAmount').text(data.message.depositAmount);
                     $('.tpAmount').text(data.message.tpAmount);
                     $('.remainingAmount').text(data.message.remainingAmount);
                     let encounter_for_received_amount = $("#encounter_id").val();
 
                     var paymode = $("input[name='payment_mode']:checked").val();
                
                     if (paymode == 'Credit' || paymode == undefined) {
                         $("#received-amount").val(0);
                     }else{
                         // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                     //     $("#received-amount").val(0);
                     // } else {
                         $("#received-amount").val(Number(data.message.total).toFixed(2));
                     // }
                     }
 
                     // $("#discount-scheme-change").prop('disabled', true);
                     showAlert('Quantity change successfully.');
                 }
             });
         });
         $(".discount-change").blur(function () {
             fldid = $(this).attr('fldid');
             new_discount = $(this).val();
             if(new_discount > 100 || new_discount < 0){
                 showAlert('Invalid Discount Amount');
                 $(this).val('');
                 return false;
             }
             $.ajax({
                 url: "{{ route('billing.change.discount.service') }}",
                 type: "POST",
                 data: {
                     fldid: fldid,
                     new_discount: new_discount,
                     temp_checked: 'no'
                 },
                 success: function (data) {
                     console.log(data);
                     $("#billing-body").empty().append(data.message.tableData);
                     $("#billing-tp-body").empty().append(data.message.tptableData);
                     $("#sub-total-data").empty().val((parseFloat(data.message.subtotal) + parseFloat(data.message.tax)));
                     $("#discount-amount").val(parseFloat(data.message.discount));
                     $("#table-bill-total").empty().val(parseFloat(data.message.total));
                     $("#tax-total-data").empty().val(parseFloat(data.message.tax));
                     $("#grand-total-data").empty().val(parseFloat(data.message.total));
                     $('.depAmount').text(data.message.depositAmount);
                     $('.tpAmount').text(data.message.tpAmount);
                     $('.remainingAmount').text(data.message.remainingAmount);
                     let encounter_for_received_amount = $("#encounter_id").val();
 
                     var paymode = $("input[name='payment_mode']:checked").val();
                
                     if (paymode == 'Credit' || paymode == undefined) {
                         $("#received-amount").val(0);
                     }else{
                         // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                     //     $("#received-amount").val(0);
                     // } else {
                         $("#received-amount").val(Number(data.message.total).toFixed(2));
                     // }
                     }
 
                     // $("#discount-scheme-change").prop('disabled', true);
                     showAlert('Discount added successfully.');
                 }
             });
         });
 
     })

     $(document).on('click','.health_package',function(){
         var encounter = $('#encounter_id').val();
         var packagename = $(this).data('package');
         $.ajax({
            url: "{{ route('billing.package.data') }}",
            type: "POST",
            data: {
                encounter: encounter,
                packagename: packagename
            },
            success: function (data) {
                $('#pacakge-modal-title').text(data.message.packagename);
                $('#package-table-body').empty().append(data.message.html);
                $('#package-item-modal').modal('show');
            }
        });
     })
     $(document).on('click','.tp-health_package',function(){
         var encounter = $('#encounter_id').val();
         var packagename = $(this).data('package');
         var tp = 1;
         $.ajax({
            url: "{{ route('billing.package.data') }}",
            type: "POST",
            data: {
                encounter: encounter,
                packagename: packagename,
                tp:tp
            },
            success: function (data) {
                $('#pacakge-modal-title').text(data.message.packagename);
                $('#package-table-body').empty().append(data.message.html);
                $('#package-item-modal').modal('show');
            }
        });
     })
 </script>
 
 
