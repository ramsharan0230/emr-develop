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
     @php
        $totaldiscountamount = 0;
    @endphp
     <tbody id="package-item-body">
         
     @if ($serviceData)
         @php
            $countItems = 1;
         @endphp
         @forelse ($serviceData as $service)
            
             @if (!empty($service->service_cost()->category))
                 
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
                                 @if (count($service->service_cost()->category) > 0 && in_array('referable', $service->service_cost()->category))
                                 
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
                         @php
                            $groupdata = \App\ServiceGroup::where('fldgroup',$packagename)->where('flditemname',$service->flditemname)->first();
                         @endphp
                         @if($service->fldsample == 'Sampled')
                             <span class="badge badge-success">Sampled</span>
                         @elseif($groupdata->price_editable == 1)

                             <a href="javascript:;" class="delete-billing-row-tp"  rel="{{ $service->fldid }}">
                                 <i class='fa fa-trash text-danger'></i>
                             </a>
                         @else

                         @endif
                     </td>
                 </tr>
 
             @endif
         @empty
         @endforelse

        
         @forelse ($serviceData as $service)
           
             @if (empty($service->service_cost()->category))
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
                        @php
                            $groupdata = \App\ServiceGroup::where('fldgroup',$packagename)->where('flditemname',$service->flditemname)->first();
                        @endphp
                         @if($service->fldsample == 'Sampled')
                             <span class="badge badge-success">Sampled</span>
                        @elseif($groupdata->price_editable == 1)

                             <a href="javascript:;" class="delete-billing-row"  rel="{{ $service->fldid }}">
                                 <i class='fa fa-trash text-danger'></i>
                             </a>
                         @else

                         @endif
                         
                     </td>
                 </tr>
             @endif
         @empty
             <tr>
                 <td colspan="10">No Items Added</td>
             </tr>
         @endforelse
        

     @endif
     <input type="hidden" name="allowed_discount_amount" id="allowed_discount_amount" value="{{$totaldiscountamount}}">
     </tbody>
     
 </table>
 
<script>
    $(document).ready(function () {
        $(".delete-billing-row-tp").click(function () {
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
    });
</script>
 
 