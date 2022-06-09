@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Unsampled List
                        </h4>
                    </div>
                    <button class="btn btn-primary" id="js-toggle-filter"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        @if(Session::get('success_message'))
            <div class="alert alert-success containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {{ Session::get('success_message') }}
            </div>
        @endif

        @if(Session::get('error_message'))
            <div class="alert alert-success containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {{ Session::get('error_message') }}
            </div>
        @endif

        <div class="col-md-12" id="js-unsampled-list-filter">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="registration-list-filter">
                        <form id="unsampled_test">
                            <div class="col-md-12 form-row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Patient Id</label>
                                        <input type="text" name="patient_id" value="{{ request('patient_id') }}" placeholder="Patient Id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Encounter Id</label>
                                        <input type="text" name="encounter_id" value="{{ request('encounter_id') }}" placeholder="Encounter Id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($_GET['from_date']) ?$_GET['from_date']: $date }}"/>
                                    <input type="hidden" name="eng_from_date" id="eng_from_date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($_GET['to_date']) ? $_GET['to_date']: $date }}"/>
                                    <input type="hidden" name="eng_to_date" id="eng_to_date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" id="status" class="form-control select2">
                                            <option value="">---select---</option>
                                            <option value="Removed" @if(isset($_GET['status']) && $_GET['status'] == 'Removed') selected @endif>Accepted</option>
                                            <option value="Unsampled" @if(isset($_GET['status'])  && $_GET['status'] == 'Unsampled')selected @endif>Requested</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                <a href="{{route('laboratory.unsampled.index')}}" type="button" class="btn btn-light btn-action" ><i class="fa fa-redo"></i>&nbsp;Reset</a>
                                <button class="btn btn-primary btn-action"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs d-flex justify-content-between" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" type="button" btn-action onclick="pdfUnsampledTest()" class="btn btn-primary btn-action pull-left" ><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                            <a href="javascript:void(0);" onclick="exportExcelUnsampledTest()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a>
                        </li>
                    </ul>
                    <div class="iq-card-body">
                        <table 
                        class="table table-bordered" 
                        id="myTable1" 
                        data-show-columns="true"
                        data-search="true"
                        data-search-align="left"
                        data-show-toggle="true"
                        data-pagination="true"
                        data-resizable="true"
                        >
                            <thead class="thead-light">
                                <tr>
                                    <th>S.N.</th>
                                    <th>Bill No.</th>
                                    <th>Patient ID/Enc ID</th>
                                    <th>Patient Detail</th>
                                    <th>UserId</th>
                                    <th>Date Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="js-unsampled-list">
                                @if(!$unsampled_test->isEmpty())
                                    @php
                                        $count=1;
                                    @endphp
                                    @foreach ($unsampled_test as $list)
                                    <tr data-encounterid="{{ $list->encounter_id }}" data-status="{{$list->fldstatus}}">
                                        <td>{{$count++}}</td>
                                        <td>{{$list->fldbillno}}</td>
                                        <td>{{$list->fldpatientval}}/{{$list->encounter_id}}</td>
                                        <td>
                                            {{strtoupper($list->fldptnamefir) ?? ''}} {{strtoupper($list->fldmidname) ?? ''}} {{strtoupper($list->fldptnamelast) ?? ''}} <br>
                                            {{Carbon\Carbon::parse($list->fldptbirday)->age ?? ''}} Y/{{$list->fldptsex ?? ''}}<br> 
                                            {{$list->fldptcontact ?? ''}}
                                        </td>
                                        <td>{{$list->user_id}}</td>
                                        <td>{{$list->date}}</td>
                                        <td>{{$list->fldstatus}}</td>
                                        <td class="unsampled_request">
                                            @if($list->fldstatus=='Unsampled')
                                            <button type="button" class="btn btn-info unsampled_request" value="{{$list->encounter_id}}" class="requested">Requested</button>
                                            @else
                                            <a type="button" class="btn btn-info">Accepted</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('laboratory::modal.test_list')
@endsection

@push('after-script')
<script src="{{ asset('js/print.js') }}"> </script>
<script>
    $('#eng_from_date').val(BS2AD($('#from_date').val()));
    $('#eng_to_date').val(BS2AD($('#to_date').val()));
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

    $('#js-toggle-filter').click(function () {
        if (document.getElementById('js-unsampled-list-filter').style.display == 'none') {
            $('#js-unsampled-list-filter').show();
            // $('#js-toggle-filter').text('H');
        } else {
            $('#js-unsampled-list-filter').hide();
            // $('#js-toggle-filter').text('Show Filter');
        }
    });
    $(function() {
        $('#myTable1').bootstrapTable()
    })

    function pdfUnsampledTest() {
        var data = $("#unsampled_test").serialize();
        // var urlReport = baseUrl + "/admin/laboratory/unsampled/pdf/unsampled/test?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        $.PrintPlugin({
            remotefetch: {
                loadFormRemote : true,
                requestType : "GET",
                origin : baseUrl + "/admin/laboratory/unsampled/pdf/unsampled/test?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}",
                // responseProperty : 'printview',
                responseProperty : null,
            }
        });
    }

    function exportExcelUnsampledTest() {
        var data = $("#unsampled_test").serialize();
        var urlReport = baseUrl + "/admin/laboratory/unsampled/export/unsampled/test?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }

    $('#unsampled-test').click(function(){
        alert('test');
    });
    $(document).on('click', '#js-unsampled-list tr > td.unsampled_request', function (event) {
            let encounter_id = $(this).closest('tr').data('encounterid');
            let status = $(this).closest('tr').data('status');
            if(status == 'Unsampled'){
                Swal.fire({  
            title: 'Do you want to accepted the test?',  
            showDenyButton: true,  
            confirmButtonText: `Yes`,  
            denyButtonText: `No`,
            }).then((result) => { 
                if (result.isConfirmed) {  
                    $.ajax({
                        url: baseUrl + '/admin/laboratory/unsampled/change/status',
                        type: "GET",
                        data: {
                            encounter_id : encounter_id
                        },
                        dataType: 'json',
                        success: function (response, status, xhr) {
                            location.reload();  
                        }, 
                        error: function (xhr, status, error) {
                            showAlert(error);
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                        }
                    });


                } else if (result.isDenied) {    
                    Swal.fire('Changes are not saved', '', 'info')  
                }
            });
            }
            
    });

    $(document).on('click', '#js-unsampled-list tr > td:not(.unsampled_request)', function (event) {
            let encounterid = $(this).closest('tr').data('encounterid');
            $.ajax({
                url: baseUrl + '/admin/laboratory/unsampled/test-list',
                type: "GET",
                data:{
                    encounterid:encounterid
                },
                success: function (response, status, xhr) {
                    $('.test-list-content').empty();
                    $('.test-list-content').html(response);
                    $('#test-list').modal('show');
                    setTimeout(() => {
                        ($('#myTable12').bootstrapTable())
                    }, 50);
                }, 
                error: function (xhr, status, error) {
                    showAlert(error);
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                }
            });
    });
</script>
@endpush
