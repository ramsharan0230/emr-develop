@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Auto Billing</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
                        <form action="{{ route('auto.billing') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-1">Target</div>
                                <div class="col-2">
                                    <select name="department" class="form-control" id="departmentList">
                                        <option value="">--Select--</option>
                                        @foreach ($hospital_departments as $hospital_department)
                                            @if(isset($hospital_department->fldcomp))
                                                <option value="{{ $hospital_department->id }}" @if(Session::has('selected_user_hospital_department')) @if(Session::get('selected_user_hospital_department')->id == $hospital_department->id) selected @else  @endif @endif>{{ $hospital_department->name }}({{ $hospital_department->fldcomp }})</option>    
                                            @endif
                                        @endforeach
                                        {{-- <option value="comp07" {{ isset($department) && $department == 'comp07'?'selected':'' }}>comp07</option> --}}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">Show</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Service</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Service')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Service" id="Service">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Service', 'Service')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Equipment</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Equipment')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Equipment" id="Equipment">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Equipment', 'Equipment')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Radiology</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Radio')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Radio" id="Radio">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Radio', 'Radio')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Procedure</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Procedure')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Procedure" id="Procedure">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Procedure', 'Procedure')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Pharmacy</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Pharmacy')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Pharmacy" id="Pharmacy">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Pharmacy', 'Pharmacy')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Others</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/Others')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/Others" id="Others">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/Others', 'Others')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group form-row">
                                        <div class="col-3">Auto Save Zero</div>
                                        <div class="col-2">
                                            @php
                                                if ($departmentData){
                                                    $auto = $departmentData->where('fldcategory', 'AutoIPBilling/AutoSaveZero')->first();
                                                }
                                            @endphp
                                            <select class="form-control" name="AutoIPBilling/AutoSaveZero" id="AutoSaveZero">
                                                <option value="">--Select--</option>
                                                <option value="Full" {{ $departmentData && $auto && $auto->fldvalue == 'Full' ? 'selected':'' }}>Full</option>
                                                <option value="Partial" {{ $departmentData && $auto && $auto->fldvalue == 'Partial'  ? 'selected':'' }}>Partial</option>
                                                <option value="No" {{ $departmentData && $auto && $auto->fldvalue == 'No'  ? 'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" onclick="autobill.insertUpdateData('AutoIPBilling/AutoSaveZero', 'AutoSaveZero')" class="btn btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var autobill = {
            insertUpdateData: function (fldcategory, selectid) {
                if ($("#departmentList").val() === "") {
                    showAlert('Select department.', 'error');
                    return false;
                }
                if ($('#' + selectid).val() === "") {
                    showAlert('Select ' + selectid, 'error');
                    return false;
                }
                $.ajax({
                    url: '{{ route('auto.billing.insert.update') }}',
                    type: "POST",
                    data: {department: $("#departmentList").val() , billingType: $('#' + selectid).val(), fldcategory:fldcategory},
                    success: function (response) {
                        // console.log(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }
    </script>
@endpush
