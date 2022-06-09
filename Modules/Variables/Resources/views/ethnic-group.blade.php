@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Ethnic Group</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <label class="label-width-food-mixture col-sm-2">Group Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="ethnicgroupname" id="ethnicgroupname"  class="form-control">
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" id="loadsurnamefromethnicgroup" class="btn btn-primary" title="load surnames"><i class="ri-refresh-line"></i></a>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#add_surname_modal" id="addsurnamestoethnicgroup" class="btn btn-primary"><i class="ri-add-box-fill h5"></i></a>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label for="" class="col-sm-2"></label>
                        <div class="col-sm-8">
                            @php $ethnicgroups = \App\Utils\Variablehelpers::getAllEthnicGroups(); @endphp
                            <select name="ethnicgroupselect" id="ethnicgroupselect" class="form-input-food-mix mt-2" readonly="" style="width: 85%;">
                                <option value=""></option>
                                @forelse($ethnicgroups as $ethnicgroup)
                                <option value="{{ $ethnicgroup->fldgroupname }}"> {{ $ethnicgroup->fldgroupname }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="res-table table-sticky-th">
                        <table class="table table-bordered table-striped table-hover" id="surnamelists">
                        </table>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary"  data-toggle="modal" data-target="#duplicate_items" id="duplicateitems"> <i class="fa fa-square"></i> &nbsp; Dublicates</button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#missing_items" id="missingitems"> <i class="fa fa-square"></i> &nbsp; Missing</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('variables::layouts.modal.add-surname')
@include('variables::layouts.modal.duplicate')
@include('variables::layouts.modal.missing')
<script>
    $(function() {
        $('#ethnicgroupselect').change(function() {
            var ethnicgroup = $(this).val();

            $('#ethnicgroupname').val(ethnicgroup);
        });

        $('#loadsurnamefromethnicgroup').click(function() {

            var groupname =  $('#ethnicgroupname').val();

            $.ajax({
                type: 'post',
                url: '{{ route('variables.ethnicgroup.getsurname') }}',
                dataType: 'json',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'fldgroupname' : groupname,
                },
                success: function (res) {
                    if(res.message == 'success') {
                        $('#surnamelists').html(res.html);
                    } else if(res.message == 'error') {
                        showAlert(res.error);
                    }

                }
            });
        })

        $('#addsurnamestoethnicgroup').click(function() {
            var ethnicgroupname = $('#ethnicgroupname').val();

            $('#ethnicgroupvalue').val(ethnicgroupname);
        });

        $('#surnamelists').on('click', '.deletethnicgroup', function() {
            var really = confirm("You really want to delete this ethic group?");
            var href = $(this).data('href');
            if(!really) {
                return false
            } else {
                $.ajax({
                    type : 'delete',
                    url : href,
                    dataType: 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        if(res.message == 'success') {
                                // alert('ethnicgroup deleted successfully.');
                                $('#loadsurnamefromethnicgroup').click();
                            } else if(res.message == 'error') {
                                showAlert(res.error);
                            }
                        }
                    });
            }
        });
    });
</script>

@stop
