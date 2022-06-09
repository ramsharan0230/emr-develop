<div class="modal fade" id="med_category_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <input type="text" id="dosageformfield" class="form-control">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <a href="javascript:void(0)" class="btn btn-light pull-left" id="dosageaddbutton" style="border: 1px solid #ced4da; width: 85px; margin-right: 242px;"><img src="{{ asset('assets/images/plus.png') }}" style="width: 15px;"> &nbsp;<u>A</u>dd</a>
                            <a href="javascript:void(0)" class="btn btn-light pull-right" id="dosagedeletebutton" style="border: 1px solid #ced4da; width: 105px;"><img src="{{ asset('assets/images/cancel.png') }} " style="width: 15px;"> &nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="dosagetobedeletedroute" value="">
                            <input type="hidden" id="dosageidtobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    <ul id="dosagelistingmodal">
                                        @forelse($dosageforms as $dosageform)
                                        <li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="{{ route('medicines.deletedosageform', $dosageform->fldid) }}" data-id="{{ $dosageform->fldid }}">{{ $dosageform->flforms }}</a></li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </table>
                            </div>
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
    .dosage-list a:focus {
        background-color: #88b9ed;
    }
</style>

<script>
    $(function() {
        $('#dosageformfield').keyup(function() {

            var keyword = $(this).val();

            $.ajax({
                type: 'post',
                url: "{{ route('medicines.dosagenamefilter')}}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'keyword': keyword
                },
                success: function(res) {
                    if (res.message == 'success') {
                        $('#dosagelistingmodal').html(res.html);
                    } else if (res.message == 'error') {
                        alert(res.error);
                    }
                }
            });

        });
    });
</script>
