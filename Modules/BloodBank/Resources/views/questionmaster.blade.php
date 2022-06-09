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
                                Question Master
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Question:</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" id="id" value="{{ request()->get('id') }}">
                                            <select name="parent_id" id="parent_id" class="select2">
                                                <option value="">Select</option>
                                                @if(isset($questions) && $questions)
                                                    @foreach($questions as $question)
                                                        <option value="{{ $question->id }}" @if (request()->get('parent_id') == $question->id) selected @endif>{{ $question->question }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(isset($form_errors['parent_id']))<div class="text-danger">{{ $form_errors['parent_id'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Add Question:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="question" id="question" value="{{ request()->get('question') }}" class="form-control">
                                            @if(isset($form_errors['question']))<div class="text-danger">{{ $form_errors['question'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Order:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="order" id="order" value="{{ request()->get('order') }}" class="form-control">
                                            @if(isset($form_errors['order']))<div class="text-danger">{{ $form_errors['order'] }} </div>@endif
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
                                        <th class="text-center">Order</th>
                                        <th class="text-center">Inactive</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $question)
                                        <tr data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-order="{{ $question->order }}" class="question-tr">
                                            <td>{{ str_pad($loop->iteration, 5, 0, STR_PAD_LEFT) }}</td>
                                            <td>{{ $question->question }}</td>
                                            <td>{{ $question->order }}</td>
                                            <td>
                                                <div class="custom-control custom-checkbox" onclick="changeStatus(this)">
                                                    <input type="checkbox" class="custom-control-input" @if (!$question->is_active) checked @endif>
                                                    <label class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td><button type="button" class="btn btn-info btn-sm btn-edit" title="Edit"><i class="fa fa-edit"></i></button></td>
                                        </tr>
                                        @foreach ($question->childs as $child)
                                            <tr data-id="{{ $child->id }}" data-question="{{ $child->question }}" data-order="{{ $child->order }}" data-parentid="{{ $child->parent_id }}"  >
                                                <td>{{ str_pad($loop->iteration, 3, 0, STR_PAD_LEFT) }}</td>
                                                <td>{{ $child->question }}</td>
                                                <td>{{ $child->order }}</td>
                                                <td>
                                                    <div class="custom-control custom-checkbox" onclick="changeStatus(this)">
                                                        <input type="checkbox" class="custom-control-input" @if (!$child->is_active) checked @endif>
                                                        <label class="custom-control-label"></label>
                                                    </div>
                                                </td>
                                                <td><button type="button" class="btn btn-info btn-sm btn-edit" title="Edit"><i class="fa fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
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
<script>
    function changeStatus(currentElem) {
        $.ajax({
            url: baseUrl + "/bloodbank/question-master/changeStatus",
            type: "POST",
            data: {
                id: $(currentElem).closest('tr').data('id'),
                status: $(currentElem).find('input.custom-control-input').prop('checked'),
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
            }
        });
    }
    $('.btn-edit').on('click', function () {
        var closestTr = $(this).closest('tr');
        var parentid =  $(closestTr).data('parentid');
        var question =  $(closestTr).data('question');
        var order =  $(closestTr).data('order');
        var id =  $(closestTr).data('id');

        $('#order').val(order);
        $('#question').val(question);
        $('#id').val(id);

        $('#parent_id').val(parentid).trigger('change');
        $('#parent_id').select2("close");
        $('#form-btn').text('Update');
    });
</script>
@endpush
