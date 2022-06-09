<table class="table table-bordered table-hover table-striped ">
    <thead class="thead-light">
      <tr>
        <th></th>
        <th>Supplier</th>
        <th>Address</th>
        <th>Status</th>
        {{-- <th>Paid</th>
        <th>To Pay</th>
        <th>NET</th> --}}
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="supplierLists">
      @foreach($get_supplier_info as $key=>$supplier_info)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $supplier_info->fldsuppname }}</td>
            <td>{{ $supplier_info->fldsuppaddress }}</td>
            @if($supplier_info->fldactive == "Active")
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-success changeStatus" data-supply="{{ $supplier_info->fldsuppname }}">Active</button></td>
            @else
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger changeStatus" data-supply="{{ $supplier_info->fldsuppname }}">Inactive</button></td>
            @endif
            {{-- <td>{{ $supplier_info->fldactive }}</td> --}}
            {{-- <td>{{ $supplier_info->fldpaiddebit }}</td>
            <td>{{ $supplier_info->fldleftcredit }}</td>
            <td>{{ $supplier_info->fldleftcredit - $supplier_info->fldpaiddebit }}</td> --}}
            <td>
                <button type="button" class="btn btn-primary editsupply" data-supply="{{ $supplier_info->fldsuppname }}"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                <button type="button" class="btn btn-primary viewsupply" data-supply="{{ $supplier_info->fldsuppname }}"><i class="fas fa-eye"></i>&nbsp;View</button>
            {{-- <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Edit {{ $supplier_info->fldsuppname }}" class="editsupply text-primary"><i class="fa fa-edit"></i></a>&nbsp; --}}
            {{-- <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Delete {{ $supplier_info->fldsuppname }}" class="deletesupply text-danger"><i class="ri-delete-bin-5-fill"></i></a> --}}
            </td>
        </tr>
        {{-- <tr>
          <td>{{ ++$key }}</td>
          <td>{{ $supplier_info->fldsuppname }}</td>
          <td>{{ $supplier_info->fldsuppaddress }}</td>
          <td>{{ $supplier_info->fldactive }}</td>
          <td>{{ $supplier_info->fldpaiddebit }}</td>
          <td>{{ $supplier_info->fldleftcredit }}</td>
          <td>{{ $supplier_info->fldleftcredit - $supplier_info->fldpaiddebit }}</td>
          <td>
            <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Edit {{ $supplier_info->fldsuppname }}" class="editsupply text-primary"><i class="fa fa-edit"></i></a>&nbsp;
            <a href="#" data-supply="{{ $supplier_info->fldsuppname }}" title="Delete {{ $supplier_info->fldsuppname }}" class="deletesupply text-danger"><i class="ri-delete-bin-5-fill"></i></a>
          </td>
        </tr> --}}
      @endforeach
      <tr>
        <td colspan="5">{{ $get_supplier_info->links() }}</td>
      </tr>
    </tbody>
  </table>
  <div id="bottom_anchor"></div>
