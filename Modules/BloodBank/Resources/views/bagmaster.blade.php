@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        .question-tr {
            font-weight: 600;
        }
        tbody tr td:first-child {
            text-align: center;
        }
    </style>
@endpush

@section('content')
    @include('frontend.common.alert_message')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Bag Master
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Description:</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" id="id" value="{{ request()->get('id') }}">
                                            <input type="text" name="description" id="description" value="{{ request()->get('description') }}" class="form-control">
                                            @if(isset($form_errors['description']))<div class="text-danger">{{ $form_errors['description'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">No. of Component:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="component" id="component" value="{{ request()->get('component') }}" class="form-control">
                                            @if(isset($form_errors['component']))<div class="text-danger">{{ $form_errors['component'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3 row">
                                <div class="col-1">
                                    <button id="form-btn" class="btn btn-primary">{{ request()->get('id') ? "Update" : "Add" }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive res-table">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center" style="width: 70%;">Description</th>
                                        <th class="text-center">Component</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bags as $bag)
                                        <tr data-id="{{ $bag->id }}" data-description="{{ $bag->description }}" data-component="{{ $bag->component }}">
                                            <td>{{ str_pad($loop->iteration, 3, 0, STR_PAD_LEFT) }}</td>
                                            <td>{{ $bag->description }}</td>
                                            <td>{{ $bag->component }}</td>
                                            <td><button type="button" class="btn btn-info btn-sm btn-edit" title="Edit"><i class="fa fa-edit"></i></button></td>
                                        </tr>
                                    @endforeach
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
{{--<script>--}}
{{--    $('.btn-edit').on('click', function () {--}}
{{--        var closestTr = $(this).closest('tr');--}}
{{--        var description =  $(closestTr).data('description');--}}
{{--        var component =  $(closestTr).data('component');--}}
{{--        var id =  $(closestTr).data('id');--}}

{{--        $('#description').val(description);--}}
{{--        $('#component').val(component);--}}
{{--        $('#id').val(id);--}}
{{--        $('#form-btn').text('Update');--}}
{{--    });--}}
{{--</script>--}}
@endpush
