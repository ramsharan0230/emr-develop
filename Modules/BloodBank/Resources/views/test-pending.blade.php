@extends('frontend.layouts.master')

@section('content')
    @include('frontend.common.alert_message')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Test Pending</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST" id="js-result-authorization-form" >
                            @csrf
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Branch</label>
                                        <div class="col-sm-8">
                                            <select name="branch" id="branch" class="form-control">
                                                <option value="">--- Select ---</option>
                                                @foreach ($hospitalbranches as $hospitalbranch)
                                                    <option value="{{ $hospitalbranch->id }}" @if (request()->get('branch') == $hospitalbranch->id) selected @endif>{{ $hospitalbranch->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($form_errors['branch']))<div class="text-danger">{{ $form_errors['branch'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">From Date <span style="color: red">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="from_date" value="{{ request()->get('from_date') ? request()->get('from_date') :$dates }}" id="from_date" class="form-control nepaliDatePicker col-sm-10"  required>
                                            @if(isset($form_errors['from_date']))<div class="text-danger">{{ $form_errors['from_date'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">To Date <span style="color: red">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="to_date" value="{{ request()->get('to_date') ? request()->get('to_date') :$dates }}" id="to_date" class="form-control nepaliDatePicker col-sm-10" required>
                                            @if(isset($form_errors['to_date']))<div class="text-danger">{{ $form_errors['to_date'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Department</label>
                                        <div class="col-sm-9">
                                            <select name="department" id="department" class="form-control">
                                                <option value="">--- All ---</option>
                                                @foreach ($departments as $department)
                                                    <option value='{{ $department->fldid }}' @if (request()->get('department') == $department->flddept) selected @endif>{{ $department->flddept }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($form_errors['department']))<div class="text-danger">{{ $form_errors['department'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="code" value="{{ request()->get('code') }}" id="code" class="form-control col-sm-10" placeholder="Test Name">
                                            @if(isset($form_errors['code']))<div class="text-danger">{{ $form_errors['code'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group form-row align-items-center">
                                        <div class="col-sm-9">
                                            <a class="search-link" id="searchBtn" name="searchBtn" href="javascript:;"><i class="ri-search-line"></i></a>
                                            {{--                                            <button type="button" name="searchBtn"  id="searchBtn" class="form-control col-sm-10"><i class="ri-search-line"></i> </button>--}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>

                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <div class="iq-card-body">
                                        <div class="form-group">
                                            <div class="table-responsive res-table">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">Test Name</th>
                                                        <th class="text-center">Branch</th>
                                                        <th class="text-center">Bag No</th>
                                                        <th class="text-center">SID Date</th>
                                                        <th class="text-center">Patient Name</th>
                                                        <th class="text-center">Age/Sex</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="component_tbody">
                                                    @forelse($component_details as $detail)
                                                        <tr>
                                                            <td align="center"> {{ $detail->component_name }}</td>
                                                            <td align="center">{{ $detail->component->bloodbag->branch ? $detail->component->bloodbag->branch->name :'' }} </td>
                                                            <td align="center"> {{ $detail->component ? $detail->component->bag_no :'' }}</td>
                                                            <td align="center"></td>
                                                            <td align="center">{{ $detail->component->bloodbag->donor ? $detail->component->bloodbag->donor->fullname :'' }}</td>
                                                            <td align="center">{{ $detail->component->bloodbag->donor ? ($detail->component->bloodbag->donor->dob ? \Carbon\Carbon::parse($detail->component->bloodbag->donor->dob)->age.'/' :'') :'' }} {{ $detail->component->bloodbag->donor ? $detail->component->bloodbag->donor->gender :'' }} </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td align="center"></td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-sm-2">
                                <button  class="btn btn-primary"><i class="ri-save-3-line"></i></button>
                                <button  class="btn btn-primary"><i class="fa fa-check"></i></button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('after-script')
    <script>
        $(document).ready(function () {
            var nepaliDateConverter = new NepaliDateConverter();
            $('#searchBtn').click(function () {
                var code = $('#code').val();
                var from_date =  $('#from_date').val();
                var to_date = $('#to_date').val();
                var branch = $('#branch').val();
                var department = $('#department').val();

                if (from_date !== '' || to_date) {
                    $.ajax({
                        url: baseUrl + "/bloodbank/test-pending/search",
                        type: "GET",
                        data: {
                            code: code,
                            from_date: from_date,
                            to_date: to_date,
                            branch: branch,
                            department:department,
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response) {
                                $('#component_tbody').empty().append(response);
                            }
                            if(response.error){
                                showAlert(response.error,'error');
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
