<div class="iq-search-bar custom-search">
    <form action="#" class="searchbox">
        <input type="text" id="genericinfo_listing" name="" class="text search-input" placeholder="Type here to search..." />
        <!-- <a class="search-link" href="#"><i class="ri-search-line"></i></a> -->
    </form>
</div>
<div class="res-table" style="max-height:578px;">
    <table class="table table-hovered table-striped mt-2" id="genericinfolistingtable">
        <tbody id="geninfolistingtable">
            @php
            $perpage = 100;
            $codes = \App\Utils\Medicinehelpers::getAllCodes($perpage);
            @endphp
            @forelse($codes as $code)
            <tr data-generic="{{ $code->fldcodename }}" class="editGeneric" data-url="{{ route('medicines.generic.edit', encrypt($code->fldcodename)) }}" style="cursor: pointer;">
                <td>{{ $code->fldcodename }}</td>
                <td class="d-flex">
                    <a  href="{{ route('medicines.generic.edit', encrypt($code->fldcodename)) }}"  title="edit {{ $code->fldcodename}}" class="text-primary">
                        <i class="fa fa-edit"></i>
                    </a>&nbsp;&nbsp;
                    {{-- <a  title="delete {{ $code->fldcodename }}" class="deletegenericinfo text-danger" data-href="{{ route('medicines.generic.delete', encrypt($code->fldcodename)) }}"><i class="ri-delete-bin-5-fill"></i></a> --}}
                </td>
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>

</div>

<div class="form-group padding-none mt-2">
    <div class="form-inner">
       <nav aria-label="...">
            {{ $codes->links('vendor.pagination.bootstrap-4') }}
        </nav>
    </div>
</div>
<script type="text/javascript">

    $(document).on('click','.editGeneric',function(){
        var url = $(this).data('url');
        window.location = url;
    });

    $("#genericinfo_listing").keyup(function () {
        var searchtext = $(this).val();
        // if()
        // var patientid = $('#patientID').val();
        // var resultDropdown = $(this).siblings("#allergicdrugss");
        // $('#allergicdrugss').hide();
        if (searchtext.length > 0) {
            // alert('kesdf');
            $.get(baseUrl+"/medicines/genericinfo/searchGenericinfo", {term: searchtext,'_token': '{{ csrf_token() }}'}).done(function (data) {
                // Display the returned data in browser
                $('#geninfolistingtable').html(data);
            });
        } else {
            $.get(baseUrl+"/medicines/genericinfo/searchGenericinfo", {term: searchtext,'_token': '{{ csrf_token() }}'}).done(function (data) {
                // Display the returned data in browser
                $('#geninfolistingtable').html(data);
            });
        }
    });
</script>
