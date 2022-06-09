<div class="dietarytable overflow-auto container p-0">
    <!-- <input type="text" class="text search-input" placeholder="Type here to search..." name="searchkeyword" id="searchmedicine"/>
        <input type="text" name="searchkeyword" id="searchmedicine" value="" class="form-control" placeholder="search medicine"> -->
        {{--    <i class="fa fa-search"></i>  --}}

        <ul class="list-group" id="medicinelisting">
            @php $codes = \App\Utils\Medicinehelpers::getAllDistinctCodeFromDrugs(); @endphp

            @forelse($codes as $k=>$code)
            <li class="table-menu list-group-item list-group-medicine" type="button" data-toggle="collapse" data-target="#collapse_{{ $k }}" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-angle-right"></i>&nbsp;{{ $code->fldcodename }}
            </li>
            @php
            $drugs = \App\Utils\Medicinehelpers::getDrugsFromCode($code->fldcodename);
            @endphp
            <div class="collapse" id="collapse_{{ $k }}" style="padding: 0px 0px 0px 6px;">
                <ul class="list-group">
                    @forelse($drugs as $i=>$drug)
                    <li class="table-menu list-group-item list-group-medicine" @if(count($drug->MedicineBrand) > 0) type="button" data-toggle="collapse" data-target="#collapsedrug_{{ $drug->flddrug.'_'.$i }}" aria-expanded="false" aria-controls="collapseExample" @endif>
                       <label for="">{{ $drug->flddrug }}</label>
                       <a type="button" href="{{ route('medicines.medicineinfo.editdrug', encrypt($drug->flddrug)) }}" style="margin-left: 6px;" title="edit {{ $drug->flddrug }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a type="button" href="{{ route('medicines.medicineinfo.brandinfo', encrypt($drug->flddrug)) }}" style="margin-left: 6px;" title="show {{ $drug->flddrug }} brands">
                        <i class="fa fa-arrow-alt-circle-right"></i>
                    </a>
                    <a type="button" href="{{ route('medicines.medicineinfo.labels', encrypt($drug->flddrug)) }}" style="margin-left: 6px;" title="show {{ $drug->flddrug }} labels">
                        <i class="fa fa-arrow-up"></i>
                    </a>
                    <button title="delete {{ $drug->flddrug }}" class="btn text-danger deletedrug" data-href="{{ route('medicines.medicineinfo.deletedrug', encrypt($drug->flddrug)) }}"><i class="fa fa-trash"></i></button>
                </li>
                {{--                        @if(count($drug->MedicineBrand) > 0)--}}
                {{--                            <div class="collapse" id="collapsedrug_{{ $drug->flddrug.'_'.$i }}" style="padding: 0px 50px;">--}}
                    {{--                                <ul class="list-group">--}}
                        {{--                                    @forelse($drug->MedicineBrand as $brand)--}}
                        {{--                                        <li class="list-group-item">--}}
                            {{--                                            <label for="">{{ $brand->fldbrandid }}</label>--}}
                            {{--                                            <a type="button" href="" style="margin-left: 15px;" title="edit">--}}
                                {{--                                                <i class="fa fa-edit"></i>--}}
                            {{--                                            </a>--}}
                            {{--                                            <button title="delete " class="deletefood" data-href=""><i class="fa fa-trash"></i></button>--}}
                        {{--                                        </li>--}}
                        {{--                                    @empty--}}

                        {{--                                    @endforelse--}}
                    {{--                                </ul>--}}
                {{--                            </div>--}}
                {{--                        @endif--}}
                @empty
                @endforelse
            </ul>
        </div>
        @empty
        @endforelse
    </ul>
</div>


<div class="form-group padding-none">
    <div class="form-inner">
     {{ $codes->links('vendor.pagination.bootstrap-4') }}
 </div>
</div>


<script>
    $(function() {
        $('#searchmedicine').keyup(function() {
            var searchkeyword = $(this).val();

            $.ajax({
                url: '{{ route('medicines.listing.search') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'searchkeyword' : searchkeyword,
                },
                success: function(res) {
                    if(res.message == 'error'){
                        alert(res.errormessage);
                    } else if(res.message == 'success') {
                        $('#medicinelisting').html(res.html);
                    }
                }
            });
        });
    });
</script>
