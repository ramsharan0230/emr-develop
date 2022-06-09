@extends('frontend.layouts.master') @section('content')

<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
      <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Sampling
                    </h4>
                </div>
                <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <form method="post" id="js-sampling-search-form">
                    @csrf
                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-4"><label>Name</label></div>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="js-sampling-search-name-input" class="form-control js-lab-module-name-search-input">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-4"><label>Encounter</label></div>
                        <div class="col-sm-8">
                            <input type="text" name="encounterId" id="js-sampling-search-encounter-input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-4"><label>Category</label></div>
                        <div class="col-sm-6">
                            <select name="category" id="js-sampling-category-select" class="form-control">
                                <option value="">%</option>
                                @foreach ($categories as $category)
                                <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button id="js-sampling-test-report" type="button" class="btn btn-primary"><i class="fa fa-code"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center pl-2">
                        <div class="col-sm-5">
                            <input type="text" name="fromdate" value="{{ request()->get('fromdate') ?: $date }}" class="form-control nepaliDatePicker" id="js-fromdate-input-nepaliDatePicker">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="todate" value="{{ request()->get('todate') ?: $date }}" class="form-control nepaliDatePicker" id="js-todate-input-nepaliDatePicker">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary" type="button" id="js-sampling-search-submit-btn"><i class="fa fa-sync"></i></button>
                        </div>
                    </div>

                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="department" value="Patient Ward" class="custom-control-input" />
                                    <label class="custom-control-label"> IPD </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="department" value="Consultation" class="custom-control-input"/>
                                    <label class="custom-control-label"> OPD </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="department" value="Emergency" class="custom-control-input"/>
                                    <label class="custom-control-label"> Emergency </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline align-items-center p-0">
                                    <input type="checkbox" name="rejected" value="Rejected" id="rejected" class="mr-2"/>
                                    <label class="control-label"> Rejected </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-12 rightdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-row">
                            <label class="col-sm-4">Encounter</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="js-sampling-encounterid-input">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-4">Full Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="js-sampling-fullname-input">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-4">Address</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="js-sampling-address-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-row">
                            <label class="col-3 padding-none">Age/Sex</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="js-sampling-agesex-input">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-3 padding-none">Location</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="js-sampling-location-input">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <button class="btn btn-primary" id="js-sampling-view-btn"><i class="fas fa-play"></i>&nbsp;View</button>&nbsp;
                            <button class="btn btn-primary" id="get_test_pdf" url="{{ route('laboratory.view.test.pdf') }}">Export</button>&nbsp;
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="js-sampling-show-all-checkbox">
                                <label class="custom-control-label">Show All</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body" id="js-sampling-patientList-div">
                @include('laboratory::tests.samplingPatientList')
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 rightdiv">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="res-table table-sticky-th border mb-2" style="min-height: 478px; ">
                        <table class="table table-bordered table-striped table-hover" id="sampling-labtest-list">
                            <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">&nbsp;</th>
                                    <th class="tittle-th"><input type="checkbox" id="js-sampling-select-all-checkbox">&nbsp;</th>
                                    <th class="tittle-th">W&nbsp;<input type="checkbox" id="js-sampling-select-all-w-checkbox"></th>
                                    <th class="tittle-th">Account</th>
                                    <th class="tittle-th">Test Name</th>
                                    <th class="tittle-th">Sample Date</th>
                                    <th class="tittle-th">Sample ID</th>
                                    <th class="tittle-th">Specimen</th>
                                    <th class="tittle-th">Vial</th>
                                    <th class="tittle-th">Referral</th>
                                </tr>
                            </thead>
                            <tbody id="js-sampling-labtest-tbody"></tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <textarea id="js-sampling-comment-textarea" class="form-control" placeholder="Comment"></textarea>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-3">Referal:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="js-sampling-referal-input">
                                        <option value="">-- Select user --</option>
                                        @foreach($refer_by as $user)
                                        <option value="{{ $user->flduserid }}">{{ $user->fldtitlefullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-3">Diagnosis:</label>
                                <div class="col-sm-9">
                                    <select multiple id="js-lab-common-diagnosis-ul" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-4">Specimen:</label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="js-sampling-specimen-input">
                                        <option value="">-- Select --</option>
                                        @foreach($specimens as $specimen)
                                        <option value="{{ $specimen->fldsampletype }}">{{ $specimen->fldsampletype }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="javascript:void(0)" id="js-sampling-update-specimen" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i></a>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-4">SampleID:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="js-sampling-sampleid-input" value="">
                                </div>
                                <div class="col-sm-2 padding-none">
                                    <a href="javascript:void(0)" id="js-sampling-nextid" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i></a>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-6">Sampled Location:</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2" id="js-sampling-fldsamplelocation-select">
                                        <option value="Hospital">Hospital</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group er-input">
                                <label>Date Time:</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly id="js-sampling-date-input" style="width: 60%;float: left;" value="{{ $date }}" class="form-control">
                                    <input type="text" readonly id="js-sampling-time-input" style="width: 39%;float: left;" value="{{ $time }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    @if(Options::get('check_worksheet') == 'No')
                                        <input type="checkbox" class="custom-control-input" id="id-generate-worksheet">
                                    @else
                                        <input type="checkbox" class="custom-control-input" id="id-generate-worksheet" checked>
                                    @endif
                                    <label class="custom-control-label">W</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    @if(Options::get('check_barcode') == 'No')
                                        <input type="checkbox" class="custom-control-input" id="id-generate-barcode">
                                    @else
                                        <input type="checkbox" class="custom-control-input" id="id-generate-barcode" checked>
                                    @endif
                                    <label class="custom-control-label">B</label>
                                </div>
                                <button id="js-sampling-sample-barcode-reprint" class="btn btn-primary btn-sm-in"><i class="fa fa-sync"></i>&nbsp;Reprint</button>
                                <button id="js-sampling-test-update-btn" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i>&nbsp;Sample</button>
                                <button id="js-sampling-test-unsampled-btn" class="btn btn-danger btn-sm-in"><i class="ri-edit-fill"></i>&nbsp;Unsample</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="specimen-list-modal" tabindex="-1" role="dialog" aria-labelledby="HistoryModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Test List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="specimen-list-content">
                    <div class="form-group form-row">
                        <input type="hidden" value="" id="modal-testid">
                        <label class="col-2">Specimen:</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" id="js-sampling-specimen-input-modal">
                                <option value="">-- Select --</option>
                                @foreach($specimens as $specimen)
                                <option value="{{ $specimen->fldsampletype }}">{{ $specimen->fldsampletype }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <a href="javascript:void(0)" id="js-sampling-update-specimen-modal" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('frontend.common.pcr-test-form')
@endsection

@push('after-script')
<script src="{{ asset('js/print.js') }}"> </script>
<script src="{{asset('js/laboratory_form.js')}}"></script>
<script>
    $('#rejected').change(function () {
        if ($(this).prop('checked')){
            $('#js-sampling-search-submit-btn').trigger('click');
        }else{
        }
    });
    $(document).on("keydown", "#search_from_encounter", function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13')
            $( "#sampling-form-on" ).submit();
    });

    $('#js-sampling-test-unsampled-btn').click(function(){
        var testids = [];
        // var hasSpecimen = true;
        var test_value={};
        var billing_groupid=[];
        var isPcr = false;
        $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
            var test_id = $(ele).data('test');
            if(test_id.includes('PCR')){
                isPcr = true;
            }

            test_value[i]={
                fldbillgrouping:$(ele).attr("patbillingid"),
                fldtest:$(ele).data('test')
            }
            testids.push($(ele).val());
            billing_groupid.push($(ele).attr("patbillingid"));
        });
        console.log(test_value);
        console.log(testids);
        if (isPcr) {
            showAlert("Cannot unsampled the pcr test", 'fail');
            return;
        }
        if (testids.length == 0) {
            showAlert("Please select one or more test to update.", 'fail');
            return false;
        }
        Swal.fire({  
        title: 'Do you want to unsampled?',  
        showDenyButton: true,  
        confirmButtonText: `Yes`,  
        denyButtonText: `No`,
        }).then((result) => { 
            if (result.isConfirmed) {  
                $.ajax({
                    url: baseUrl + '/admin/laboratory/unsampled/add/unsampled/patlabtest',
                    type: "GET",
                    data: {
                        // rejected_checkbox: $('#rejected:checked').val(),
                        encounter_id : $('#js-sampling-encounterid-input').val(),
                        testids:testids,
                        test_value:test_value,
                        billing_groupid:billing_groupid,
                    },
                    dataType: 'json',
                    success: function (response, status, xhr) {
                        $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
                            $(ele).closest('tr').remove();
                        });
                        setTimeout(() => {
                            if ($('#js-sampling-labtest-tbody tr').length == 0) {
                                var encid = $('#js-sampling-encounterid-input').val();
                                $('#js-sampling-patient-tbody tr[data-encounterid="' + encid + '"]').remove();
                            }
                        }, 200);
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
    });
</script>
@endpush
