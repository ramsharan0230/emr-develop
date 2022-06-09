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
      
      <tbody>
          @php
              $totaldiscountamount = 0;
          @endphp
      @if ($serviceTpData)
          @php
              $tppackagedata = array();
              $countItems = 1;
          @endphp
          @forelse ($serviceTpData as $serviceTp)
                @if(!is_null($serviceTp->package_name))
                    @php
                        $tppackagedata[$serviceTp->package_name][] = $serviceTp;
                    @endphp
                @endif
              @if (!empty($serviceTp->service_cost()->category) and is_null($serviceTp->package_name))
                  
                  <tr>
                      <td>{{$countItems++}}</td>
                      <td>{{ $serviceTp->serviceCost->fldbillitem }}</td>
                      <td class="text-center">
                          <input type="text" name="quantity[]" class="quantity-change form-control" fldid="{{$serviceTp->fldid}}" value="{{$serviceTp->flditemqty}}" style="width: 80%;">
                      </td>
                      <td class="text-center">
                          <input type="text" name="rate[]" class="rate-change form-control" fldid="{{$serviceTp->fldid}}" value="{{$serviceTp->flditemrate}}" style="width: 80%;" @if(\Helpers::getServiceCostRateFlag($serviceTp->flditemname) == '0' ) readonly @endif>
                      </td>
                      
                      @if($serviceTp->fldtempbillno =='')
                          
                          @if($serviceTp->serviceCost->discount == '0' or !is_null($serviceTp->noDiscount) or is_string($serviceTp->package_name))
                              @php
                                  $class = '';
                                  $disable = 'readonly';
                                  
                              @endphp
                          @else
                              @php
                                  $totaldiscountamount +=$serviceTp->flditemrate*$serviceTp->flditemqty;
                                  $class = 'discount-change';
                                  $disable = '';
                              @endphp
                          @endif
  
                      @else
                            @if($serviceTp->serviceCost->discount == '0' or !is_null($serviceTp->noDiscount) or is_string($serviceTp->package_name))
                                @php
                                    $class = '';
                                    $disable = 'readonly';
                                    
                                @endphp
                            @else
                                @php
                                    $totaldiscountamount +=$serviceTp->flditemrate*$serviceTp->flditemqty;
                                    $class = 'discount-change';
                                    $disable = '';
                                @endphp
                            @endif
                      @endif
                      <td class="text-center">
                          <input type="text" name="dis[]" class="{{$class}} form-control" fldid="{{$serviceTp->fldid}}" value="{{$serviceTp->flddiscper}}" style="width: 80%;" {{$disable}}>
                      </td>
                      <td class="text-center">
                          <input type="text" name="tax[]" class="form-control" value="{{ $serviceTp->fldtaxper }}" style="width: 80%;" readonly>
                      </td>
                      <td class="text-center">{{number_format((float)($serviceTp->fldditemamt), 2, '.', '')}}</td>
                      <td class="text-center">
                          @php
                              $type = $serviceTp->service_cost()->category;
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
                              <a title="Doctor Share" href="javascript:;" data-user-ids="{{ $serviceTp->pat_billing_shares()->select('type', 'user_id', 'ot_group_sub_category_id')->get() }}" data-type="{{ json_encode($serviceTp->service_cost()->category) }}" class="{{$class}}" data-id="{{ $serviceTp->fldid }}" data-itemname="{{ $serviceTp->flditemname }}">
                                  @if (count($serviceTp->service_cost()->category) == 1 && in_array('referable', $serviceTp->service_cost()->category))
                                  
                                  --
                                  @else
                                      @php
                                          $doctorlist = \App\Utils\Helpers::getPayableDoctorName($serviceTp->fldid);
                                      @endphp
                                      <span id="payable-doctors">{{$doctorlist}}</span><br/>
                                      <input type="hidden" name="share_check[]" value="{{ $serviceTp->fldid }}">
                                  <i class='fas fa-share-alt text-default'></i>
                                  <div class="share_check"></div>
                                  
                                      
                                          
                                      
                                  @endif
                              </a>
                          @endif
                      </td>
                      <td class="text-center">{{ $serviceTp->fldtempbillno }} </td>
                      <td class="text-center">
                          @if($serviceTp->fldsample == 'Sampled')
                              <span class="badge badge-success">Sampled</span>
                          @else
                              <a href="javascript:;" class="delete-billing-row"  rel="{{ $serviceTp->fldid }}">
                                  <i class='fa fa-trash text-danger'></i>
                              </a>
                          @endif
                          
                      </td>
                  </tr>
  
              @endif
          @empty
          @endforelse
  
          @forelse ($serviceTpData as $serviceTp)
          
              @if (empty($serviceTp->service_cost()->category) and is_null($serviceTp->package_name))
                  <tr class="tp">
                      <td>{{$countItems++}}</td>
                      <td>{{ $serviceTp->serviceCost->fldbillitem }}</td>
                      <td class="text-center">
                          <input type="text" name="quantity[]" class="quantity-change form-control" fldid="{{$serviceTp->fldid}}" value="{{$serviceTp->flditemqty}}" style="width: 80%;">
                      </td>
                      <td class="text-center">
                          <input type="text" name="rate[]" class="rate-change form-control" fldid="{{$serviceTp->fldid}}" value="{{$serviceTp->flditemrate}}" style="width: 80%;">
                      </td>
                      
                      @if($serviceTp->fldtempbillno =='')
                          @if($serviceTp->serviceCost->discount == '0' or !is_null($serviceTp->noDiscount) or is_string($serviceTp->package_name))
                              @php
                                  $class = '';
                                  $disable = 'readonly';
                                  
                              @endphp
                          @else
                              @php
                                  $totaldiscountamount +=$serviceTp->flditemrate*$serviceTp->flditemqty;
                                  $class = 'discount-change';
                                  $disable = '';
                              @endphp
                          @endif
                      @else
                            @if($serviceTp->serviceCost->discount == '0' or !is_null($serviceTp->noDiscount) or is_string($serviceTp->package_name))
                                @php
                                    $class = '';
                                    $disable = 'readonly';
                                    
                                @endphp
                            @else
                                @php
                                    $totaldiscountamount +=$serviceTp->flditemrate*$serviceTp->flditemqty;
                                    $class = 'discount-change';
                                    $disable = '';
                                @endphp
                            @endif
                      @endif
                      <td class="text-center">
                          <input type="text" name="dis[]" class="{{$class}} form-control" fldid="{{$serviceTp->fldid }}" value="{{$serviceTp->flddiscper }}" style="width: 80%;" {{$disable}}>
                      </td>
                      <td class="text-center">
                          <input type="text" name="tax[]" value="{{ $serviceTp->fldtaxper }}" class="form-control" style="width: 80%;" readonly>
                      </td>
                      <td class="text-center">{{number_format((float)($serviceTp->fldditemamt), 2, '.', '')}}</td>
  
                      <td class="text-center">
                          --
                      </td>
                      <td class="text-center">{{ $serviceTp->fldtempbillno }} </td>
                      <td class="text-center">
                          @if($serviceTp->fldsample == 'Sampled')
                              <span class="badge badge-success">Sampled</span>
                          @else
                              <a href="javascript:;" class="delete-billing-row"  rel="{{ $serviceTp->fldid }}">
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

          @if(isset($tppackagedata) and !empty($tppackagedata))
                
            {{-- dd($tppackagedata); --}}
            @foreach($tppackagedata as $tkey=>$tpdata)
                    
                @php
                    $tptotalsum = 0;
                    $itemdata = array();
                @endphp
                 @foreach($tpdata as $tndata)
                    @php
                    $tptotalsum +=$tndata->fldditemamt;
                    $itemdata[] = $tndata->flditemname;
                    @endphp
                    
                 @endforeach
                 @php
                    $tppackageeditabledata = \App\ServiceGroup::where('fldgroup',$tkey)->whereIn('flditemname',$itemdata)->where('price_editable','1')->get();
                @endphp
                <tr>
                    <td>{{$countItems++}}</td>
                    <td><a href="javascript:void(0)" class="tp-health_package badge badge-primary" data-package="{{$tkey}}">{{ $tkey }}</a></td>
                    <td class="text-center">
                        <input type="text" name="quantity[]" class=" form-control" value="1" style="width: 80%;">
                    </td>
                    <td class="text-center">
                        
                    </td>
                    
                    <td class="text-center">
                        
                    </td>
                    <td class="text-center">
                        
                    </td>
                    <td class="text-center">{{number_format((float)($tptotalsum), 2, '.', '')}}</td>

                    <td class="text-center">
                        --
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center">
                        @if(count($tpdata) == count($tppackageeditabledata))
                            <a href="javascript:;" class="delete-billing-row"  rel="{{ $tndata->fldid }}">
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
  
  
  
