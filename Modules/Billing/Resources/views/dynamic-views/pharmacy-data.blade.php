<input type="text" class="form-control" id="searchPharmacy" onkeyup="searchPharmacyItems()" placeholder="Search for names..">
<form action="javascript:;" style="height: 400px; overflow-y: scroll" id="pharmacy-form">

    <table class="table table-striped" id="searchPharmacyTable">
        <thead>
        <tr>
            <th width="20"></th>
            <th width="150">Item Name</th>
            <th width="40">Quantity</th>
            <th width="40">Order Qty</th>
            <th width="40">Cost</th>
        </tr>
        </thead>
        <tbody>
        @if($services)
            @forelse($services as $service)
                <tr>
                    <td>
                        <input type="checkbox" name="pharmacyItem[]" value="{{ $service->fldstockno }}">
                    </td>
                    <td>{{ $service->fldstockid}}</td>
                    <td><input type="text" name="quantity[]" value="{{$service->fldqty}}"></td>
                    <td>
                        @if($service->fldqty != 0)
                            <input type="number" name="ItemQuantity[]" class="form-control" style="width: 80px; height: 25px" min="1" max="{{$service->fldqty}}">
                        @endif
                    </td>
                    <td>{{$service->fldsellpr}}</td>
                </tr>
            @empty

            @endforelse
        @endif
        </tbody>
    </table>

</form>
<div class="form-horizontal pt-3">
    <div class="form-group float-right">
        <button type="button" class="btn btn-primary" onclick="savePharmacy()">Save</button>
    </div>
</div>

<script>
    function searchPharmacyItems() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchPharmacy");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchPharmacyTable");
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
</script>
