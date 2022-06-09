<div class="modal fade" id="add_surname_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Select Surnames</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 col-sm-2">
                            <input type="text" id="surnamefield" class="form-control">
                            <input type="hidden" id="ethnicgroupvalue" value="">
                        </div>
{{--                        <div class="col-md-3 col-sm-3">--}}
{{--                            <a href="javascript:void(0)" class="btn btn-light pull-left" id="categoryaddaddbutton" style="border: 1px solid #ced4da; width: 85px; margin-right: 242px;"><img src="{{ asset('assets/images/tick.png') }}" style="width: 16px;"> &nbsp;Select</a>--}}
{{--                        </div>--}}
                        <div class="col-md-4 col-sm-4">
                            <a href="javascript:void(0)" class="btn btn-primary" id="surnameaddbutton" style="border: 1px solid #ced4da; width: 105px;"><i class="ri-add-line"></i> Save</a>
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12 mt-1">
                            <div class="res-table">
                                <table class="table table-bordered">
                                    @php $surnames = \App\Utils\Variablehelpers::getAllSurnames(); @endphp
                                    <ul id="surnamelistforadd">
                                        @forelse($surnames as $surname)
                                            <li style="border: 1px solid #ced4da;"><input type="checkbox" value="{{ $surname->fldid }}" class="flag-check" name="surnames"/>&nbsp;&nbsp; {{ $surname->flditem }}</li>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <input type="checkbox" class="checkall"> select all
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<style type="text/css">--}}
{{--    .category-list a:focus {--}}
{{--        background-color:#88b9ed;--}}
{{--    }--}}
{{--</style>--}}

<script>
    $(function() {

        $('.checkall').click(function () {
            var boxes = $('#surnamelistforadd').find('.flag-check');
            boxes.prop('checked', $(this).is(':checked'));
        });

        var checked = [];
        $('#surnameaddbutton').click(function() {

            $("input:checkbox[name=surnames]:checked").each(function(){
                checked.push($(this).val());
            });
            console.log(checked);
            if(checked.length > 0) {
                var ethnicgroupvalue = $('#ethnicgroupvalue').val();
                console.log(ethnicgroupvalue);
                if(ethnicgroupvalue.length > 0 && ethnicgroupvalue != '') {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('variables.ethnicgroup.addsurname') }}',
                        dataType: 'json',
                        data: {
                            '_token' : '{{ csrf_token() }}',
                            'checked' : checked,
                            'fldgroupname' : ethnicgroupvalue
                        },
                        success: function(res) {
                            if(res.message == 'success') {


                                $('#add_surname_modal').modal('toggle');
                                var boxes = $('#surnamelistforadd').find('.flag-check');
                                boxes.prop('checked', false);
                                checked = [];
                                $('#surnamefield').val('');
                                $('#loadsurnamefromethnicgroup').click();
                            } else if(res.message == 'error') {
                                showAlert(res.error);
                            }
                        }
                    });
                } else {
                    alert('ethnicgroup undefined, choose the ethnic group.')
                }

            } else {
                alert('nothing selected');
            }
        });

        $('#surnamefield').keyup(function() {

            var keyword = $(this).val();

            if(keyword.length > 0 &&  keyword != '') {
                $.ajax({
                   type : 'post',
                   url : '{{ route('variables.ethnicgroup.surnamefilter') }}',
                   dataType : 'json',
                   data: {
                      '_token' : '{{ csrf_token() }}',
                      'keyword' : keyword
                   },
                   success: function(res) {
                       if(res.message == 'success') {
                           $('#surnamelistforadd').html(res.html);
                       } else if(res.message == 'error') {
                        showAlert(res.error);
                       }
                   }
                });
            }
        });
    })
</script>
