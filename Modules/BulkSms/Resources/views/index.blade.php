@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                            <h4 class="card-title tittle-resp"> Bulk SMS</h4>
                        </div>
                        <a href="{{ route('bulksms.create') }}" class="btn btn-primary btn-action">Add</a>
                    </div>
                    <div class="iq-card-body">
                        <div class="">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.N.</th>
                                        <th>Type</th>
                                        <th>Sub Type</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="js-bulksms-list">
                                    @if ($bulksms)
                                        @foreach ($bulksms as $bs)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bs->fldtype }}</td>
                                            <td>{{ $bs->fldsubtype }}</td>
                                            <td>{{ $bs->fldmessage }}</td>
                                            <td>{{ $bs->status }}</td>
                                            <td>
                                                <a href="{{ route('bulksms.edit', $bs->fldid) }}" type="button" class="btn btn-primary btn-action">
                                                    <i class="fa fa-"></i>&nbsp;Edit</a>&nbsp;
                                                <a href="{{ route('bulksms.delete', $bs->fldid) }}" type="button" id="{{ $bs->fldid }}" class="btn btn-danger btn-action delete">
                                                    <i class="fa fa-"></i>&nbsp;Delete</a>&nbsp;
                                                <form action="{{ route('bulksms.delete', $bs->fldid) }}" method="post" id="delete_form_{{ $bs->fldid }}" style="display:none;"> 
                                                    <input class="btn btn-danger" type="submit" value="Delete" />
                                                    @method('DELETE')@csrf
                                                </form>
                                                <a href="{{ route('bulksms.send', $bs->fldid) }}" type="button" class="btn btn-success btn-action" @if($bs->status == 1) disabled @endif>
                                                    <i class="fa fa-"></i>&nbsp;Send</a>&nbsp;
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <br>
                                <br>
                            </table>
                            <tr>
                                <td colspan="20">{{ $pagination }}</td>
                            </tr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script>
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var self = $(this);
            var id = self.attr('id');
            if (confirm('Are you sure to delete?')) {
                $("#delete_form_" + id).submit();
            }
        });
    </script>
@endpush
