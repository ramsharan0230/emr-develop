<style>
    #billing-report-pagination > .pagination{
        .justify-content-center {
        justify-content: right!important;
    }


</style>
<table  style="width: 100%"
class="table expandable-table custom-table table-bordered table-striped mt-c-15"
id="myTableResponse" data-show-columns="true" data-search="true" data-show-toggle="true"

    data-pagination="false"
    data-resizable="true"
>
    <thead class="thead-light">
    <tr>
        <th>SN</th>
        <th>Date</th>
        <th>Time</th>
        <th>Invoice</th>
        <th>EnciD</th>
        <th>Name</th>
        <th>Particulars</th>
        <th>Rate</th>
        <th>Quantity</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody >
        @forelse ($results as $report )
            {{-- @dd($report) --}}
            <tr>
                <td>{{ $loop->index+1 }}</td>
                {{-- <td> {{ \Carbon\Carbon::parse($report->fldtime)->format('Y-m-d') }} </td> --}}
               <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($report->fldtime)->format('Y-m-d'))->full_date }}</td>
                <td> {{ \Carbon\Carbon::parse($report->fldtime)->format('H:i:s') }} </td>
                <td> {{ $report->fldbillno }} </td>
                <td> {{ $report->fldencounterval }} </td>
                <td> {{ $report->flduserid }} </td>
                <td> {{ $report->flditemtype }} </td>
                <td> {{ $report->flditemrate }} </td>
                <td> {{ $report->flditemqty }} </td>
                <td> {{ $report->fldditemamt }} </td>
            </tr>
        @empty
            
        @endforelse
    </tbody>
</table>
<div  id="billing-report-pagination">  {{ $results->appends(request()->all())->links() }}</div>



