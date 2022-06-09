@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<!-- <link rel="stylesheet" href="{{ asset('styles/nutritionmodal.css') }}"> -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Body Fluid</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <div class="col-md-5">
                            <input type="text" id="bodyfluidname" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0)" class="btn btn-primary" id="bodyfluidaddbutton"><i class="ri-add-line"></i></a>
                            <input type="hidden" id="bodyfluidtobedeleteroute" value="">
                            <input type="hidden" id="bodyfluididtobedeleted" value="">
                            <a href="javascript:void(0)" class="btn btn-danger" id="bodyfluiddeletebutton"><i class="ri-delete-bin-5-fill"></i></a>
                        </div>
                           <!--  <div class="col-md-2">
                                <a href="javascript:void(0)" class="btn btn-danger" id="bodyfluiddeletebutton"><i class="ri-delete-bin-5-fill"></i></a>
                            </div> -->
                        </div>
                        <div class="table-responsive">
                            <table class="dietary-table table table-hover table-bordered table-striped ">
                                @php $bodyfluids = \App\Utils\Variablehelpers::getAllBodyfluids() @endphp
                                <ul id="bodyfluidlistingmodal" class="list-group">
                                    @forelse($bodyfluids as $bodyfluid)
                                    <li class="bodyfluid-list bodyfluid_item list-group-item list-group-medicine"><a href="javascript:void(0)"  data-href="{{ route('variables.bodyfluid.delete', $bodyfluid->fldid) }}" data-id="{{ $bodyfluid->fldid }}">{{ $bodyfluid->fldfluid }}</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style type="text/css">
        .bodyfluid-list a:focus {
            background-color:#88b9ed;
        }
    </style>

    <script>

        $('#bodyfluidaddbutton').click(function() {
            var fldfluid = $('#bodyfluidname').val();


            if(fldfluid != '') {
                $.ajax({
                    type : 'post',
                    url  : '{{ route('variables.bodyfluid.add') }}',
                    dataType : 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                        'fldfluid': fldfluid
                    },
                    success: function (res) {

                        showAlert(res.alertmsg);
                        if(res.message == 'Success') {
                            $('#bodyfluidname').val('');
                            var deleteroutename = "{{ url('/variables/bodyfluids/delete') }}/"+res.fldid;
                            $('#bodyfluidlistingmodal').append('<li class="bodyfluid-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="bodyfluid_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.fldfluid+'</li>');
                        }

                    }
                });
            } else {
                alert('Body Fluid Name is required');
            }
        });

        $('#bodyfluidlistingmodal').on('click', '.bodyfluid_item', function() {
            $('#bodyfluidlistingmodal').find('li').removeClass('li-selected');
            $(this).addClass('li-selected');
            $('#bodyfluidtobedeleteroute').val($(this).find('a').data('href'));
            $('#bodyfluididtobedeleted').val($(this).find('a').data('id'));
        });


        $('#bodyfluiddeletebutton').click(function() {
            var deletebodyfluidroute = $('#bodyfluidtobedeleteroute').val();
            var deletebodyfluidid = $('#bodyfluididtobedeleted').val();

            if(deletebodyfluidroute == '') {
                alert('no body fluid selected, please select the body fluid.');
            }

            if(deletebodyfluidroute != '') {
                var really = confirm("You really want to delete this body fluid?");
                if(!really) {
                    return false
                } else {
                    $.ajax({
                        type : 'delete',
                        url : deletebodyfluidroute,
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function (res) {

                            if(res.message == 'success') {
                                showAlert(res.successmessage);
                                $("#bodyfluidlistingmodal").find(`[data-href='${deletebodyfluidroute}']`).parent().remove();
                                $('#bodyfluidtobedeleteroute').val('');
                                $('#bodyfluididtobedeleted').val('');
                            } else if(res.message == 'error') {
                                showAlert(res.errormessage);
                            }
                        }
                    });
                }
            }
        });
    </script>
    @stop
