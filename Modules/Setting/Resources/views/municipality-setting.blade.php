@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid extra-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <div class="form-group">
                            <label class="card-title">
                                Municipality List
                            </label>
                      </div>
                  </div>
                  <a href="{{ route('municipality.add') }}" class="btn btn-primary"><i
                        class="ri-add-fill"><span class="pl-1">Add New</span></i>
                    </a>
              </div>
              <div class="iq-card-body">

                <div class="res-table">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>SNo</th>
                                <th>Province</th>
                                <th>District</th>
                                <th>Municipality</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody  id="municipality-table">
                            {!! $municipalities !!}
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-script')
    <script>
    var municipality = {
        editMunicipality:function (id) {
            window.location.href = baseUrl+"/setting/municipality/edit/"+id;
        },
        deleteMunicipality:function (id) {
                if(!confirm("Delete?")){
                    return false;
                }
                $.ajax({
                    url: '{{ route('municipality.delete') }}',
                    type: "POST",
                    data: {id:id},
                    success: function (response) {
                        if (response.success.status) {
                            $("#municipality-table").empty().append(response.success.html);
                            showAlert('Successfully data deleted.')
                        } else {
                            showAlert("__('messages.error')", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("__('messages.error')", 'error')
                    }
                });
            }
        }
    </script>
@endpush



