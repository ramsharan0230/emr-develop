@extends('frontend.layouts.master-stock')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="table-responsive">
                            <form action="{{ route('medicine.stock') }}" method="get">
                                <div class="form-group row">
                                    <div class="col-2">
                                        <input type="text" name="itemStockName" id="itemStockName" placeholder="Medicine Name" class="form-control" value="{{ ( Request::get('itemStockName')  != '' )?Request::get('itemStockName'):'' }}">
                                    </div>
                                    <div class="col-sm-4 form-row">
                                        <label for="department" class="col-lg-4 col-sm-5">Dept</label>
                                        <select name="department" id="departmentWiseDataDisplay" class="form-control col-6">
                                            @php
                                                $hospital_department = Helpers::getDepartmentAndComp();
                                            @endphp
                                            <option value="">Select Department</option>
                                            @if($hospital_department)
                                                @forelse($hospital_department as $dept)
                                                    <option value="{{ $dept->fldcomp }}" {{ app('request')->input('department') == $dept->fldcomp ?"selected":'' }}>{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }}) ({{ $dept->fldcomp }})</option>
                                                @empty

                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <input type="radio" name="stockType" id="sales" value="sales" {{ !Request::get('stockType') || Request::get('stockType')  == 'sales' ? 'checked':'' }}>
                                        <label for="sales">Sales</label>
                                    </div>
                                    <div class="col-1">
                                        <input type="radio" name="stockType" id="purchase" value="purchase" {{ Request::get('stockType')  == 'purchase' ? 'checked':'' }}>
                                        <label for="purchase">Purchase</label>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync"></i></button>
                                    </div>
                                </div>
                            </form>

                            <div id="list-stock-table"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-script')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var currentRequest = null;

        function ajaxCall() {
            currentRequest = $.ajax({
                url: '{{ route('medicine.stock.ajax.list') }}',
                type: "GET",
                data: {itemStockName: $('#itemStockName').val(), department: $('#departmentWiseDataDisplay').val(), stockType: $("input[name='stockType']:checked").val()},
                beforeSend: function () {
                    if (currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function (response) {
                    // console.log(response)
                    $('#list-stock-table').empty().append(response);
                    setTimeout(function () {
                        ajaxCall();
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                ajaxCall();
            }, 3000);
        });

    </script>
@endpush
