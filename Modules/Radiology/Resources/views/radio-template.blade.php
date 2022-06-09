@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Radio Template
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-row">
                        <div class="col-lg-4 col-md-12">
                            <div style="margin-bottom: 2px;">
                                <input type="text" class="form-control" placeholder="search" id="js-radio-template-search">
                            </div>
                            <div class="radioTem-table table-sticky-th ">
                                <table class="table-striped table-hover table datatable-radiology">
                                    <thead>
                                        <tr>
                                            <th width="70%">Name</th>
                                            <th width="30%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="radiologylistingbody">
                                        @forelse($templates as $template)
                                        <tr data-fldid="{{ $template->fldid }}" fldtestid="{{ $template->fldtestid }}" flddescription="{{ $template->flddescription }}">
                                            <td class="dietary-td border-none">{{ $template->fldtestid }}</td>
                                            <td class="dietary-td border-none">
                                                <button class="btn btn-primary editradiotemplate" type="button">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger deleteradiotemplate" type="button" data-href="{{ route('radiology.template.delete', $template->fldid) }}"><i class="far fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <form method="POST" action="{{ route('radiology.template.saveUpdate') }}" class="form-horizontal">
                                @csrf
                                <div class="form-group form-row">
                                    <label class="col-sm-2">Test Id</label>
                                    <div class="col-sm-10">
                                        <input name="testid" type="text" class="form-control" id="js-radio-template-fldtestid-input" value="{{ old('testid') }}" required>
                                        <input name="fldid" type="hidden" class="form-control" id="js-radio-template-fldid-input" value="{{ old('fldid') }}">
                                    </div>
                                    <small class="help-block text-danger">{{$errors->first('testid')}}</small>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" id="js-radio-template-description-input" required>{{ old('description') }}</textarea>
                                    <small class="help-block text-danger">{{$errors->first('description')}}</small>
                                </div>
                                <div class="diagnosis-btn">
                                    <button class="btn btn-action btn-primary float-right" id="js-radio-template-submit-button">
                                        <i class="fa fa-plus"></i>&nbsp;ADD
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="delete_form" method="POST">
    @csrf
    @method('delete')
</form>
@endsection

@push('after-script')
<script>
    $(function () {
        CKEDITOR.replace('js-radio-template-description-input', {
            height: '600px',
        });

        $('.deleteradiotemplate').click(function () {
            var really = confirm("You really want to delete this radio diagnostic?");
            var href = $(this).data('href');
            if (!really) {
                return false
            } else {
                $('#delete_form').attr('action', href);
                $('#delete_form').submit();
            }
        });

        $('.editradiotemplate').click(function() {
            var trElem = $(this).closest('tr');
            $('#js-radio-template-fldtestid-input').val($(trElem).attr('fldtestid'));
            $('#js-radio-template-fldid-input').val($(trElem).data('fldid'));
            CKEDITOR.instances['js-radio-template-description-input'].setData($(trElem).attr('flddescription'));

            $('#js-radio-template-submit-button').html("<i class='fa fa-edit'></i>&nbsp;UPDATE");
        });

        $('#js-radio-template-search').keyup(function() {
            var searchText = $(this).val().toUpperCase();
            $.each($('#radiologylistingbody tr'), function(i, e) {
                var tdText = $(e).text().trim().toUpperCase();

                if (tdText.search(searchText) >= 0)
                    $(e).show();
                else
                    $(e).hide();
            });
        });
    });
</script>
@endpush
