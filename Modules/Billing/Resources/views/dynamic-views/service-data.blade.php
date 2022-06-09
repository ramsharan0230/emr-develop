<input type="text" class="form-control mb-2" id="searchService" onkeyup="searchServiceItems()" placeholder="Search for names..">
<form action="javascript:;" style="height: 400px; overflow-y: scroll" id="pharmacy-form">
    <input type="hidden" name="encounter_id_payment" id="encounter_id_payment">
    <input type="hidden" name="billing_type_payment" id="billing_type_payment">
    <table class="table table-striped" id="searchServiceTable">
        <thead>
        <tr>
            <th></th>
            <th width="100">Item Name</th>
            <th>Report</th>
            <th>Type</th>
            <th>Cost</th>
        </tr>
        </thead>
        <tbody class="service-tr">
        @if($services)
            @forelse($services as $service)
                <tr>
                    <td>
                        <input type="checkbox" name="serviceItem[]" class="service-inside-tr" value="{{ $service->flditemname }}">
                    </td>
                    <td>{{ $service->flditemname}}</td>
                    <td>{{$service->fldreport}}</td>
                    <td>{{$service->flditemtype}}</td>
                    <td>{{$service->flditemcost}}</td>
                </tr>
            @empty

            @endforelse
        @endif
        </tbody>
    </table>
</form>
<div class="form-horizontal pt-3">
    <div class="form-group float-right">
        <button type="button" class="btn btn-primary" onclick="saveServiceCosting()">Save</button>
    </div>
</div>

<script>
    $("#encounter_id_payment").val($("#encounter_id").val());
    $("#billing_type_payment").val($("#billingmode").val());

    function searchServiceItems() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchService");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchServiceTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    $(document).on('click', '.service-tr tr', function () {
        // console.log($(this).children().find(".service-inside-tr").prop('checked'));
        if ($(this).children().find(".service-inside-tr").prop('checked')) {
            $(this).children().find(".service-inside-tr").prop('checked', false);
        } else {
            $(this).children().find(".service-inside-tr").prop('checked', true);
        }

    });
</script>

