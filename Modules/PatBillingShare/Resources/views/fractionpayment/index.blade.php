@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Fraction Payment Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('fraction-payment.index') }}" id="billing_filter_data" method="GET">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">From:</label>
                                        <div class="col-sm-10">
                                            <input type="text" autocomplete="off" class="form-control" name="from_date" id="from_date" value="{{request()->get('from_date')}}"/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    {{-- <div class="form-group form-row">
                                        <select name="doctor_id" class="form-control select2" id="doctor_id">
                                            <option value="">-- Select Doctor --</option>
                                            @foreach ($consultants as $consultant)
                                                <option @if($consultant->id == request()->get('doctor_id')) @endif value="{{$consultant->id}}">{{$consultant->fldtitlefullname}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    {{-- <div class="form-group form-row">
                                        <input type="text" name="doctor_name" class="form-control" placeholder="Doctor Name" id="doctor_name" value="{{ request()->get('doctor_name') }}" />
                                    </div> --}}
                                    <div class="form-group form-row">
                                        <input type="text" name="bill_no" class="form-control" id="bill_no" value="{{ request()->get('bill_no') }}" placeholder="Bill Number"/>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="itemname" id="itemname" class="select2 form-control">
                                                <option value="">--All Item Name--</option>
                                                @forelse ($itemnames as $itemname)
                                                    <option value="{{ $itemname->flditemname }}" {{ (request()->get('itemname') == $itemname->flditemname)?'selected':'' }}>{{ $itemname->flditemname }}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">To:</label>
                                        <div class="col-sm-10">
                                            <input type="text" autocomplete="off" class="form-control" name="to_date" id="to_date" value="{{request()->get('to_date')}}"/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <input type="text" name="encounter_id" class="form-control" id="encounter_id" value="{{ request()->get('encounter_id') }}" placeholder="Encounter"/>
                                    </div>
                                </div>
                               <div class="col-lg-5">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="type" value="OT" {{ (request()->get('type') == 'OT')?'checked="checked"':'checked="checked"' }} name="customRadio-1" class="custom-control-input">
                                            <label class="custom-control-label" for=""> OT </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="type" value="payable" {{ (request()->get('type') == 'payable')?'checked="checked"':'' }}   name="customRadio-1" class="custom-control-input">
                                            <label class="custom-control-label" for=""> Payable </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"  name="type"  name="type"  value="IPD" {{ (request()->get('type') == 'IPD')?'checked="checked"':'' }} name="customRadio-1" class="custom-control-input">
                                            <label class="custom-control-label" for=""> IPD </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"   name="type" value="OPD" {{ (request()->get('type') == 'OPD')?'checked="checked"':'' }} name="customRadio-1" class="custom-control-input">
                                            <label class="custom-control-label" for=""> OPD </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"  name="type"  value="referable" {{ (request()->get('type') == 'referable')?'checked="checked"':'' }}  name="customRadio-1" class="custom-control-input">
                                            <label class="custom-control-label" for=""> Referral </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="parttime" type="checkbox" id="parttime" value="1">
                                            <label class="form-check-label" for="parttime">Is Part Time?</label>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-action" onclick=""><i class="fa fa-search"></i>&nbsp;
                                            Search
                                        </button>&nbsp;

                                        <a href="{{ route('fraction-payment.index') }}" type="button" class="btn btn-primary btn-action">
                                            <i class="fa fa-sync"></i>&nbsp;Reset</a>&nbsp;


                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Report</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table-long">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>S.N</th>
                                            <th>Bill Number</th>
                                            <th>Enc</th>
                                            <th>Patient</th>
                                            <th>Billing Date</th>
                                            <th>Item name</th>
                                            <th>Total Amount (Rs.)</th>

                                            <th>Doctor Share Payment (Rs.)</th>
                                            <th>Doctor Share (Rs.)</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="billing_result">
                                        @if($billing_share_reports)
                                            @forelse($billing_share_reports as $k => $report)
                                                <tr data-billno="{{ $report->fldbillno }}">
                                                    <td>{{ $k + 1 }}</td>
                                                    <td>{{ $report->fldbillno }}</td>
                                                    <td>{{ $report->fldencounterval }}</td>
                                                    <td>{{\App\Utils\Helpers::getPatientName($report->fldencounterval)}}</td>
                                                    <td>{{ $report->created_at }}</td>
                                                    <td>{{ $report->flditemname }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->item_amt) }}</td>

                                                    @php
                                                        $tax_amt = $report->tax_amt ?? 0;
                                                        $payment = $report->doctor_share - $tax_amt;
                                                    @endphp
                                                    <td>{{App\Utils\Helpers::getshareamount($report->pat_billing_id)}}</td>
                                                    <td>{{App\Utils\Helpers::getshareamountDr($report->pat_billing_id)}}</td>
                                                    <td>
                                                        <a href="#" data-bill="{{$report->pat_billing_id}}" class="btn btn-success btn-sm editShare" title="Edit"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        <em>No data available in table ...</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="10">No data to show.</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between;padding: 12px;">
                                <div id="bottom_anchor" style="display:inline-flex;">

                                {{ $billing_share_reports->appends(request()->input())->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('billing::modal.user-list')
    {{-- Doctor Share Modal --}}
    <div class="modal fade" id="doctor-share-modal" tabindex="-1" role="dialog" aria-labelledby="doctor-share" aria-hidden="false">
        <div class="modal-dialog modal-lg bg-white" role="document">
            <div class="modal-content">
                <form id="doctor-share-form" action="{{ route("fraction-payment.update-doctor-share") }}" method="POST">
                    @csrf
                    <input id="share-type" name="type" type="hidden">
                    <input id="bill-no" type="hidden">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center;">Doctor Share<span id="doc-modal-title"></span></h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="false">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="doc-share-category-block">
                            <div class="table-responsive res-table">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Item Name</th>
                                        <th>Hospital %</th>
                                        <th>Hospital amount</th>
                                        <th>Item Amt</th>
                                        {{-- <th>Share Amt</th> --}}
                                        <th>Shares</th>
                                        {{-- <th>Doctor Share %</th> --}}
                                    </tr>
                                    </thead>
                                    <tbody id="share_result">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End of doctor share modal --}}
@endsection
@push('after-script')
    <script src="{{ asset('js/search-ajax.js')}}"></script>
    <script>
        const DOC_SHARE_MODAL = $("#doctor-share-modal");
        let select_boxes = []; //dynamically generate select boxes for type.
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{request()->get('eng_to_date')??date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{request()->get('eng_from_date')??date('Y-m-d')}}'));
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
            $('#eng_from_date').val(BS2AD($('#from_date').val()));
            if(getUrlParameter('parttime')){
                $("#parttime").attr("checked", true);
            }else{
                $("#parttime").attr("checked", false);
            }
        });

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
        $(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });

            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_from_date').val(BS2AD($('#from_date').val()));
                }
            });
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_to_date').val(BS2AD($('#to_date').val()));
                }
            });

            $("#customSearch").searchAjax({
                url: '{!! route("usershare.filter") !!}',
                paginate: true,
                paginateId: "bottom_anchor", // anchor tag encapsulated div
                onResult: function(res) {
                    let tbody = $("#js-user-share-item-tbody");
                    let tr_data = "";
                    let sn = res.data.current_page * (res.data.per_page - 1);
                    $.each(res.data.data, function(i, v) {
                        tr_data += '<tr>\
                        <td>'+ sn++ +'</td>\
                        <td>'+v.user.fldfullname+'</td>\
                        <td>'+v.flditemname+'</td>\
                        <td>'+numberFormat(v.flditemshare)+'</td>\
                        <td>'+numberFormat(v.flditemtax)+'</td>\
                        <td>'+v.category+'</td>\
                    </tr>';
                    });

                    tbody.html(tr_data);
                    $("#bottom_anchor").html(res.paginate_view);
                }
            });
        });

        $(document).on('click','.editShare',function(){
            var billno = $(this).data('bill');
            $.ajax({
                url: baseUrl + '/fraction-payment/get-bill-details',
                type: "GET",
                data: {
                    billno: billno
                },
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        $('#bill-no').val(response.billnoget);
                        var tbody = "";
                        var j = 1;
                        select_boxes = [];
                        $.each(response.patbills, function (index, patbill) {
                            var category_block = "";
                            var doctorids = [];
                            var doctors = [];
                            $.each(response.data, function (inx, data) {
                                hospitalsharepercent = data.hospitalsharepercent;
                                hospitalshareamount = data.hospitalshareamount;
                                if(data.patbillid == patbill.patbillid){
                                    doctorids = data.doctor_id;
                                    doctors = data.doctors[patbill.patbillid];
                                }
                            });
                            $.each(patbill.sharetype, function (typeindex, sharetype) {
                                var user_ids = (doctorids[sharetype] == undefined) ? [] : doctorids[sharetype];
                                var type = sharetype.replace(/ /g, '-');
                                category_block += '<div class="mainform"><div class="form-group row mb-2 align-items-center">\
                                                <label for="" class="control-label col-sm-12 col-lg-12 mb-0" style="text-transform:capitalize;"><strong>' + sharetype + '</strong></label>\
                                                <div class="col-lg-12 col-sm-12">\
                                                    <input type="hidden" class="form-control" name="share_category['+patbill.flditemname+'][' + typeindex + '][type]" value="' + sharetype + '">\
                                                    <input type="hidden" class="form-control" name="patbillids[]" value="' + patbill.patbillid + '">\
                                                </div>\
                                            </div>\
                                            <div class="form-group row mb-2 align-items-center">\
                                                <div class="col-lg-12 col-sm-12">\
                                                    <select class="form-control select2 shareables" data-user-ids="['+user_ids+']" data-fldid="' + patbill.patbillid + '" data-type="' + sharetype + '" multiple  name="share_category['+patbill.flditemname+'][' + typeindex + '][doctor_ids][]">\
                                                    </select>\
                                                </div>\
                                            </div>\
                                            <table class="table border mt-2">\
                                                <tbody class="shareholders shareholders_'+patbill.patbillid+'" data-type="'+sharetype+'" id="shareholders_'+type+'_'+patbill.patbillid+'">';
                                if(sharetype == "OT Dr. Group"){
                                    if(doctors[sharetype]){
                                        $.each(user_ids, function (o, p) {
                                            var group_id = p;
                                            $.each(response.otgroups[group_id], function (m, n) {
                                                var midname = (n.middlename) ? n.middlename : "";
                                                var ot_group_sub_category_name = n.ot_group_sub_category_name;
                                                var fullname = n.firstname + " " + midname + " " + n.lastname + " ("+ot_group_sub_category_name+")";
                                                if(response.patbillOtGroups[patbill.patbillid][group_id]){
                                                    $.each(response.patbillOtGroups[patbill.patbillid][group_id], function (s, t) {
                                                        if(t.user_id == n.userid){
                                                            var usershare = t.share;


                                                            var sharepercent = t.shareval;
                                                            category_block += '<tr data-user="'+n.ot_group_sub_category_id+'">\
                                                            <td>\
                                                                <input type="hidden" class="form-control userid" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][userid]" value="'+n.userid+'">\
                                                                <input type="hidden" class="form-control" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][ot_group_sub_category_id]" value="'+n.ot_group_sub_category_id+'">\
                                                                <input type="text" class="form-control" readonly name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][name]" value="'+fullname+'">\
                                                            </td>\
                                                            <td>\
                                                                <input type="number" step="any" class="form-control shareper" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][sharevalue]" value="'+sharepercent+'" placeholder="Share Percent %">\
                                                                <input type="number"  class="form-control" value="'+usershare+'" readonly>\
                                                            </td>\
                                                        </tr>';
                                                        }
                                                    });
                                                }else{
                                                    category_block += '<tr data-user="'+n.ot_group_sub_category_id+'">\
                                                            <td>\
                                                                <input type="hidden" class="form-control userid" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][userid]" value="'+n.userid+'">\
                                                                <input type="hidden" class="form-control" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][ot_group_sub_category_id]" value="'+n.ot_group_sub_category_id+'">\
                                                                <input type="text" class="form-control" readonly name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][name]" value="'+fullname+'">\
                                                            </td>\
                                                            <td>\
                                                                <input type="number" step="any" class="form-control shareper" name="shares['+patbill.patbillid+'][' + sharetype + ']['+n.userid+'][sharevalue]" placeholder="Share Percent %">\
                                                                <input type="number"  class="form-control" value="'+usershare+'" readonly>\
                                                            </td>\
                                                        </tr>';
                                                }
                                            });
                                        });
                                    }
                                }else{
                                    if(doctors[sharetype]){
                                        $.each(doctors[sharetype], function (e, r) {
                                            $.each(r, function (y, z) {
                                                var usershare = z.share;


                                                            var sharepercent = z.shareval;
                                                category_block += '<tr data-user="'+z.userid+'">\
                                                                <td>\
                                                                    <input type="hidden" class="form-control userid" name="shares['+patbill.patbillid+'][' + sharetype + ']['+z.userid+'][userid]" value="'+z.userid+'">\
                                                                    <input type="hidden" class="form-control" name="shares['+patbill.patbillid+'][' + sharetype + ']['+z.userid+'][ot_group_sub_category_id]" value="'+z.ot_group_sub_category_id+'">\
                                                                    <input type="text" class="form-control" readonly name="shares['+patbill.patbillid+'][' + sharetype + ']['+z.userid+'][name]" value="'+y+'">\
                                                                </td>\
                                                                <td>\
                                                                    <input type="number" step="any" class="form-control shareper" name="shares['+patbill.patbillid+'][' + sharetype + ']['+z.userid+'][sharevalue]" value="'+sharepercent+'" placeholder="Share Percent %">\
                                                                    <input type="number"  class="form-control" value="'+usershare+'" readonly>\
                                                                </td>\
                                                            </tr>';
                                            });
                                        });
                                    }
                                }

                                category_block += '</tbody>\
                                            </table>\
                                            <hr/></div>';
                                let name = 'share_category['+patbill.flditemname+']' + '[' + typeindex + '][doctor_ids][]';
                                // name of select box list for later iteration.
                                select_boxes.push(name);
                            });

                            tbody += "<tr>\
                                    <td>"+j+"</td>\
                                    <td>"+patbill.flditemname+"</td>\
                                    <td>"+hospitalsharepercent+"</td>\
                                    <td>"+numberFormat(hospitalshareamount)+"</td>\
                                    <td>"+numberFormat(patbill.fldditemamt)+"</td>\
                                    <td>"+category_block+"</td>\
                                </tr>";
                            j++;
                        });
                        $('#share_result').html(tbody);
                        $(".select2").select2();
                    }
                    $.each(select_boxes, function (j, k) {
                        let select_box = $("select[name='" + k + "']");
                        let type = select_box.data('type');
                        let fldid = select_box.data('fldid');
                        let old_ids = select_box.data('user-ids');
                        if(old_ids == ""){
                            old_ids = [];
                        }
                        if (type == "OT Dr. Group") {
                            let item_types = getOTGroupList().then(function (res) {

                                // loop through doctor list.
                                let options = "";
                                $.each(res.data, function (i, v) {
                                    let selected = "";
                                    if(old_ids.length > 0){
                                        $.each(old_ids, function (c, t) {
                                            if (t == v.id) {
                                                selected = 'selected';
                                                return;
                                            }
                                        });
                                    }
                                    options += '<option value="' + v.id + '" ' + selected + '>' + v.name + '</option>';
                                });

                                // populate options to selectbox.
                                select_box.html(options);
                            });
                        } else {
                            // get doctor list.
                            // id id pat_billing_id
                            let item_types = getDoctorList(fldid, type).then(function (res) {

                                // loop through doctor list.
                                let options = "";
                                $.each(res, function (i, v) {
                                    let selected = "";
                                    if(old_ids.length > 0){
                                        $.each(old_ids, function (c, t) {
                                            if (t == v.flduserid) {
                                                selected = 'selected';
                                                return;
                                            }
                                        });
                                    }
                                    options += '<option value="' + v.flduserid + '" ' + selected + '>' + v.user.fldfullname + '</option>';
                                });

                                // populate options to selectbox.
                                select_box.html(options);
                            });
                        }
                    });
                    $("#doctor-share-form .modal-footer").css("display", "block");
                    DOC_SHARE_MODAL.modal('show');
                }
            });
        });

        async function getDoctorList(patId, type) {
            let route = "{!! route('billing.doctor-list', ['billingId' => ':PATBILLING_ID', 'category' => ':CATEGORY']) !!}";
            route = route.replace(':PATBILLING_ID', patId);
            route = route.replace(':CATEGORY', type);
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

        // getOTGroupList
        async function getOTGroupList() {
            let route = "{!! route('usershare.ot-group-sub-categories') !!}";
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

        $(document).on('select2:select','.shareables', function (e) {
            var result = e.params.data;
            var cur = $(this);
            if(cur.data('type') != "OT Dr. Group"){
                $.ajax({
                    url: baseUrl + '/billing/service/get-doctor-share',
                    type: "GET",
                    data: {
                        userid: result.id,
                        patbillid: cur.data('fldid'),
                        type:cur.data('type')
                    },
                    dataType: "json",
                    success: function (response) {
                        var shramt = response.share_amt;
                        var itmamt = response.item_amt;
                        var value = parseFloat((((shramt * 100) / itmamt))) || 0;
                        if(value == 0){
                            var shareholdersTr = $(cur).closest('.mainform').find('.shareholders tr');
                            var tempTotalSharePer = 0;
                            if(shareholdersTr.length > 0){
                                $.each($(shareholdersTr), function (r, s) {
                                    tempTotalSharePer += parseFloat($(s).find('.shareper').val());
                                });
                            }
                            value = 0;// 100 - parseFloat(tempTotalSharePer);
                        }
                        var html = "";
                        html += '<tr data-user="'+result.id+'">\
                                <td>\
                                    <input type="hidden" class="form-control userid" name="shares['+cur.data('fldid')+'][' + cur.data('type') + ']['+result.id+'][userid]" value="'+result.id+'">\
                                    <input type="hidden" class="form-control userid" name="shares['+cur.data('fldid')+'][' + cur.data('type') + ']['+result.id+'][ot_group_sub_category_id]" value="">\
                                    <input type="text" class="form-control" readonly name="shares['+cur.data('fldid')+'][' + cur.data('type') + ']['+result.id+'][name]" value="'+result.text+'">\
                                </td>\
                                <td>\
                                    <input type="number" step="any" class="form-control shareper" name="shares['+cur.data('fldid')+'][' + cur.data('type') + ']['+result.id+'][sharevalue]" value="'+value+'" placeholder="Share Percent %">\
                                </td>\
                            </tr>';
                        cur.closest('.mainform').find('.shareholders').append(html);
                    }
                });
            }else{
                $.ajax({
                    url: baseUrl + '/billing/service/get-ot-doctor-share',
                    type: "GET",
                    data: {
                        ot_group_sub_category_id: result.id,
                        patbillid: cur.data('fldid')
                    },
                    dataType: "json",
                    success: function (response) {
                        cur.closest('.mainform').find('.shareholders').append(response.html);
                    }
                });
            }
        });

        $(document).on('select2:unselect','.shareables', function (e) {
            var data = e.params.data;
            var userid = data.id;
            $.each(($(this).closest('.mainform').find('.shareholders tr')), function (i, v) {
                if($(v).attr('data-user') == userid){
                    $(v).remove();
                }
            });
        });

        $('#doctor-share-form').on('submit', function(event){
            event.preventDefault();
            var formData = new FormData($('#doctor-share-form')[0]);
            formData.append("eng_from_date", $('#eng_from_date').val());
            formData.append("eng_to_date", $('#eng_to_date').val());
            // formData.append("doctor_name", $('#doctor_name').val());
            // formData.append("doctor_username", $('#doctor_username').val());
            // formData.append("doctor_id", $('#doctor_id').val());
            formData.append("bill_no", $('#bill_no').val());
            formData.append("itemname", $('#itemname').val());
            formData.append("encounter_id", $('#encounter_id').val());
            formData.append("type", $('input[name=type]:checked').val());
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('fraction-payment.update-doctor-share') }}",
                method:"POST",
                data: formData,
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success:function(data){
                    if(data.status){
                        showAlert("Successfully updated bill no: "+$('#bill-no').val()+" !!");
                        $('#billing_result').html(data.html);
                        $('#bottom_anchor').html(data.pagination);
                        DOC_SHARE_MODAL.modal('hide');

                        var selectedTr = $('#billing_result tr[data-billno="'+$('#bill-no').val()+'"]');
                        $(selectedTr).css("background","#ffff99");
                    }else{
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        });

        function round(value, exp) {
            if (typeof exp === 'undefined' || +exp === 0)
                return Math.round(value);

            value = +value;
            exp = +exp;

            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
                return NaN;

            // Shift
            value = value.toString().split('e');
            value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

            // Shift back
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
        }

    </script>
@endpush
