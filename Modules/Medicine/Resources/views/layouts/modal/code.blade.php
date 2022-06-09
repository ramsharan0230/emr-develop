<div class="modal fade" id="code_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <input type="text" id="genericnamefield" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <div class="form-row">
                            <a href="javascript:void(0)" class="btn btn-primary btn-action" id="genericnameaddaddbutton"><i class="fa fa-plus"></i> &nbsp;<u>A</u>dd</a>&nbsp;
                            <a href="javascript:void(0)" class="btn btn-primary btn-action" id="genericnamedeletebutton"><i class="fa fa-trash"></i>&nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="genericnametobedeletedroute" value="">
                            <input type="hidden" id="genericidtobedeleted" value="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="res-table mt-2">
                            <table class="dietary-table">
                                <ul id="genericnamelistingmodal" class="list-group">
                                    @forelse($codes as $code)
                                    <li class="list-group-item"><a href="javascript:void(0)" class="generic_item" data-href="{{ route('medicines.deletegeneric', $code->fldcodename) }}" data-id="{{ $code->fldcodename }}">{{ $code->fldcodename }}</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12">
                        {{-- <input type="text"  style="margin-right: 117px;">--}}
                        {{-- <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .generic-list a:focus {
        background-color: #88b9ed;
    }
</style>

<script>
    $(function() {
        $('#genericnamefield').keyup(function() {

            var keyword = $(this).val();

            $.ajax({
                type: 'post',
                url: "{{ route('medicines.genericnamefilter')}}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'keyword': keyword
                },
                success: function(res) {
                    if (res.message == 'success') {
                        $('#genericnamelistingmodal').html(res.html);
                    } else if (res.message == 'error') {
                        alert(res.error);
                    }
                }
            });

        });
    });
</script>